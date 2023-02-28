<?php

namespace A3020\ImageOptimizer\Handler;

use A3020\ImageOptimizer\Entity\ProcessedFile;
use Concrete\Core\Cache\Level\ExpensiveCache;
use Concrete\Core\File\File;
use Concrete\Core\File\StorageLocation\Configuration\LocalConfiguration;
use League\Flysystem\Cached\Storage\Psr6Cache;

class Original extends BaseHandler
{
    /**
     * Optimize an image from the File Manager.
     *
     * It's called 'original', because this was the file the user originally uploaded.
     *
     * @param array $body
     *
     * @return ProcessedFile
     */
    public function process($body)
    {
        $file = File::getByID($body['fileId']);

        // In theory it's possible that the queued file has been deleted.
        if (!is_object($file)) {
            $this->logger->error('File missing: ' . $body['fileId']);

            return null;
        }

        // In theory it's possible that this version has been deleted in the meanwhile.
        $fileVersion = $file->getVersion();
        if (!is_object($fileVersion)) {
            $this->logger->error('File version missing: ' . $body['fileId']);

            return null;
        }

        // Only files stored locally will be processed.
        $storage = $file->getFileStorageLocationObject();
        if (!$storage->getConfigurationObject() instanceof LocalConfiguration) {
            $this->logger->error('File is on a remote storage: ' . $body['fileId']);

            return null;
        }

        $processedFile = $this->processedFilesRepository->findOrCreateOriginal(
            $file->getFileID(), $fileVersion->getFileVersionID()
        );

        if ($processedFile->isProcessed()) {
            $this->logger->error('File is already processed' . $body['fileId']);

            return null;
        }

        $relativePath = $fileVersion->getRelativePath();

        $rootPath = $storage->getConfigurationObject()->getRootPath();
        $webRootRelativePath = $storage->getConfigurationObject()->getWebRootRelativePath();

        // From: \a3020.com\public\application\files\2343\2343\2343\plastic.jpg
        // To: \2343\2343\2343\plastic.jpg
        $relativePath = str_replace($webRootRelativePath, '', $relativePath);

        $this->logger->debug('Relative path: ' . $relativePath);
        $this->logger->debug('Absolute path: ' . $rootPath . $relativePath);

        $processedFile->setAbsolutePath($rootPath . $relativePath);
        $processedFile->setFileSizeOriginal($fileVersion->getFullSize());
        $processedFile->setProperties();

        $this->logger->debug('File size before: ' . $processedFile->getFileSizeOriginal());

        $this->checker($processedFile);
        $this->optimize($processedFile, $file, $fileVersion);
        $this->save($processedFile);

        $this->logger->debug('File size after: ' . $processedFile->getFileSizeNew());

        return $processedFile;
    }

    /**
     * @param ProcessedFile $processedFile
     * @param \Concrete\Core\Entity\File\File $file
     * @param \Concrete\Core\Entity\File\Version $fileVersion
     *
     * @return void
     */
    public function optimize(ProcessedFile $processedFile, $file, $fileVersion)
    {
        if ($processedFile->getSkipReason()) {
            $this->logger->debug('Skip reason code: ' . $processedFile->getSkipReason());

            return;
        }

        $this->optimizerChain->optimize($processedFile->getAbsolutePath());

        // Otherwise refreshAttributes isn't accurate.
        $this->clearFlysystemCache($file);

        // Do not rescan thumbnails automatically otherwise
        // user changes to a thumbnail will be lost!
        $fileVersion->refreshAttributes(false);

        $processedFile->setFileSizeReduction(
            $this->calculateReduction($processedFile)
        );
    }

    /**
     * Clears cache for flysystem, needed to get updated filesize.
     *
     * Only applies to c5 v8.2.x or higher.
     *
     * @param \Concrete\Core\Entity\File\File $file
     */
    private function clearFlysystemCache($file)
    {
        if (!class_exists(\League\Flysystem\Cached\Storage\Psr6Cache::class)) {
            return;
        }

        $fslId = $file->getFileStorageLocationObject()->getID();
        $pool = $this->app->make(ExpensiveCache::class)->pool;
        $cache = new Psr6Cache($pool, 'flysystem-id-' . $fslId);
        $cache->flush();
    }
}
