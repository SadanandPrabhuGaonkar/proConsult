<?php

namespace A3020\ImageOptimizer\Handler;

use A3020\ImageOptimizer\Entity\ProcessedFile;
use Concrete\Core\File\File;
use Concrete\Core\File\Image\Thumbnail\Type\Version;
use Concrete\Core\File\StorageLocation\Configuration\LocalConfiguration;

class Thumbnail extends BaseHandler
{
    /**
     * Optimizes a thumbnail.
     *
     * @param array $body
     *
     * @return ProcessedFile|null
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
        $fileVersion = $file->getVersion((int) $body['fileVersionId']);
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

        // Get the corresponding thumbnail type object.
        $thumbnailType = Version::getByHandle($body['thumbnailTypeHandle']);
        if (!$thumbnailType) {
            $this->logger->error('Thumbnail Type not found: ' . $body['thumbnailTypeHandle']);

            return null;
        }

        $processedFile = $this->processedFilesRepository->findOrCreateThumbnail(
            $file->getFileID(),
            $fileVersion->getFileVersionID(),
            $thumbnailType->getHandle()
        );

        if ($processedFile->isProcessed()) {
             $this->logger->error('File is already processed' . $body['fileId']);

            return null;
        }

        // This includes the path to the storage location.
        $relativePath = $fileVersion->getThumbnailURL($thumbnailType);

        $rootPath = $storage->getConfigurationObject()->getRootPath();
        $webRootRelativePath = $storage->getConfigurationObject()->getWebRootRelativePath();

        // From: \a3020.com\public\application\files\2343\2343\2343\plastic.jpg
        // To: \2343\2343\2343\plastic.jpg
        $relativePath = str_replace($webRootRelativePath, '', $relativePath);

        $this->logger->debug('Relative path: ' . $relativePath);
        $this->logger->debug('Absolute path: ' . $rootPath . $relativePath);

        $processedFile->setAbsolutePath($rootPath . $relativePath);
        $processedFile->setProperties();

        $this->logger->debug('File size before: ' . $processedFile->getFileSizeOriginal());

        $this->checker($processedFile);
        $this->optimize($processedFile);
        $this->save($processedFile);

        $this->logger->debug('File size after: ' . $processedFile->getFileSizeNew());

        return $processedFile;
    }

    /**
     * @param ProcessedFile $processedFile
     *
     * @return void
     */
    public function optimize(ProcessedFile $processedFile)
    {
        if ($processedFile->getSkipReason()) {
            $this->logger->debug('Skip reason code: ' . $processedFile->getSkipReason());

            return;
        }

        $this->optimizerChain->optimize($processedFile->getAbsolutePath());

        $processedFile->setFileSizeReduction(
            $this->calculateReduction($processedFile)
        );
    }
}
