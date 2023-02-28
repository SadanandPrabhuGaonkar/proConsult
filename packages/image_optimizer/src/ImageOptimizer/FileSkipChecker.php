<?php

namespace A3020\ImageOptimizer;

use A3020\ImageOptimizer\Entity\ProcessedFile;
use Concrete\Core\Config\Repository\Repository;

class FileSkipChecker
{
    /**
     * @var Repository
     */
    private $config;

    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * Check whether the file should be skipped or not.
     *
     * If it needs to be skipped, we set a property to the passed file object.
     *
     * @param ProcessedFile $file
     */
    public function check(ProcessedFile $file)
    {
        $extension = strtolower(pathinfo($file->getAbsolutePath(), PATHINFO_EXTENSION));
        if ((bool) $this->config->get('image_optimizer::settings.tiny_png.enabled')
            && $extension === 'png'
            && version_compare($this->config->get('concrete.version_installed'), '8.5.0a2', '<')
        ) {
            $file->setSkipReason(ProcessedFile::SKIP_REASON_PNG_8_BUG);
            return;
        }

        if ($this->getMaxSize() && $file->getFileSizeOriginal() >= $this->getMaxSize()) {
            $file->setSkipReason(ProcessedFile::SKIP_REASON_FILE_TOO_BIG);
            return;
        }

        $fileObject = $file->getOriginalFile();
        if ($fileObject) {
            if ($file->getFileVersionId()) {
                $version = $fileObject->getVersion($file->getFileVersionId());
            } else {
                $version = $fileObject->getApprovedVersion();
            }

            if ($version) {
                if ($this->isExcluded($version)) {
                    $file->setSkipReason(ProcessedFile::SKIP_REASON_FILE_EXCLUDED);
                    return;
                }
            }
        }

        if ($file->getFileSizeOriginal() === 0) {
            $file->setSkipReason(ProcessedFile::SKIP_REASON_EMPTY_FILE);
            return;
        }

        return;
    }

    /**
     * @return int Max size in bytes
     */
    private function getMaxSize()
    {
        return (int) $this->config->get('image_optimizer::settings.max_image_size') * 1024;
    }

    /**
     * Returns true if the file is excluded from image optimization
     *
     * @param \Concrete\Core\Entity\File\Version $fileVersion
     *
     * @return bool
     */
    private function isExcluded($fileVersion)
    {
        $exclude = $fileVersion->getAttribute('exclude_from_image_optimizer');

        return $exclude === true;
    }
}
