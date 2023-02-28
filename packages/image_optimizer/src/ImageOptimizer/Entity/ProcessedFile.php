<?php

namespace A3020\ImageOptimizer\Entity;

use Concrete\Core\File\File;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *   name="ImageOptimizerProcessedFiles",
 * )
 */
class ProcessedFile
{
    /**
     * @see https://github.com/concrete5/concrete5/issues/3999
     * TinyPNG might return an 8 bit PNG, however, concrete5 / Imagine
     * can't handle PNG-8 properly as it can loose transparency.
     */
    const SKIP_REASON_PNG_8_BUG = 1;
    const SKIP_REASON_FILE_TOO_BIG = 2;
    const SKIP_REASON_FILE_EXCLUDED = 3;
    const SKIP_REASON_EMPTY_FILE = 4;

    const TYPE_ORIGINAL = 'original';
    const TYPE_THUMBNAIL = 'thumbnail';
    const TYPE_CACHE_FILE = 'cache_file';

    /**
     * @ORM\Id @ORM\Column(type="integer", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $originalFileId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $fileVersionId;

     /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $thumbnailTypeHandle;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $path;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $type = self::TYPE_ORIGINAL;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $processedAt;

    /**
     * The file size before optimization
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $fileSizeOriginal = 0;

    /**
     * The saved / gained file size after optimization
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $fileSizeReduction = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $skipReason;

    protected $makeTimeOriginalFile = null;
    protected $relativePath = null;
    protected $absolutePath = null;

    /**
     * @return int
     */
    public function getId()
    {
        return (int) $this->id;
    }

    /**
     * @return integer
     */
    public function getOriginalFileId()
    {
        return (int) $this->originalFileId;
    }

    /**
     * @return \Concrete\Core\Entity\File\File|null
     */
    public function getOriginalFile()
    {
        return File::getByID($this->getOriginalFileId());
    }

    /**
     * @param integer $originalFileId
     */
    public function setOriginalFileId($originalFileId)
    {
        $this->originalFileId = (int) $originalFileId;
    }

    /**
     * Get date the image was processed
     *
     * Can be null in case the image is being processed...
     *
     * @return \DateTimeImmutable|null
     */
    public function getProcessedAt()
    {
        return $this->processedAt;
    }

    /**
     * @param \DateTimeImmutable $processedAt
     */
    public function setProcessedAt($processedAt)
    {
        $this->processedAt = $processedAt;
    }

    public function touch()
    {
        $this->processedAt = new \DateTimeImmutable();
    }

    /**
     * @return int
     */
    public function getFileVersionId()
    {
        return (int) $this->fileVersionId;
    }

    /**
     * @param int $fileVersionId
     */
    public function setFileVersionId($fileVersionId)
    {
        $this->fileVersionId = (int) $fileVersionId;
    }

    /**
     * @return bool
     */
    public function isProcessed()
    {
        return (bool) $this->processedAt;
    }

    /**
     * @return int
     */
    public function getFileSizeOriginal()
    {
        return (int) $this->fileSizeOriginal;
    }

    /**
     * @return int
     */
    public function getFileSizeNew()
    {
        return max($this->getFileSizeOriginal() - $this->getFileSizeReduction(), 0);
    }

    /**
     * @return int
     */
    public function getFileSizeReduction()
    {
        return (int) $this->fileSizeReduction;
    }

    /**
     * @param int $fileSizeReduction
     */
    public function setFileSizeReduction($fileSizeReduction)
    {
        $this->fileSizeReduction = max((int) $fileSizeReduction, 0);
    }

    /**
     * @param int $fileSizeOriginal
     */
    public function setFileSizeOriginal($fileSizeOriginal)
    {
        $this->fileSizeOriginal = max((int) $fileSizeOriginal, 0);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns computer path of the file
     *
     * Used on the Optimized Images page and in the CLI.
     *
     * @return string|null
     */
    public function getComputedPath()
    {
        if ($this->getType() === ProcessedFile::TYPE_CACHE_FILE) {
            return $this->path;
        }

        $file = $this->getOriginalFile();
        if (!is_object($file)) {
            // File may have been deleted
            return null;
        }

        $version = $file->getVersion($this->getFileVersionId());
        if (!$version) {
            return null;
        }

        if ($this->isOriginal()) {
            return $version->getRelativePath();
        }

        return $version->getThumbnailURL($this->getThumbnailTypeHandle());
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = (string) $path;
    }

    /**
     * @return int|null
     */
    public function getSkipReason()
    {
        return $this->skipReason;
    }

    /**
     * @param int|null $skipReason
     */
    public function setSkipReason($skipReason)
    {
        $this->skipReason = $skipReason;
    }

    /**
     * @return string|null
     */
    public function getThumbnailTypeHandle()
    {
        return $this->thumbnailTypeHandle;
    }

    /**
     * @param string $thumbnailTypeHandle
     */
    public function setThumbnailTypeHandle($thumbnailTypeHandle)
    {
        $this->thumbnailTypeHandle = $thumbnailTypeHandle;
    }

    public function isOriginal()
    {
        return $this->getType() === self::TYPE_ORIGINAL;
    }

    public function setMakeTimeOriginalFile($time)
    {
        $this->makeTimeOriginalFile = $time;
    }

    /**
     * UNIX timestamp of when the original file was created on the file system.
     *
     * @return int|null
     */
    public function getMakeTimeOriginalFile()
    {
        return $this->makeTimeOriginalFile;
    }

    public function setAbsolutePath($path)
    {
        $this->absolutePath = $path;
    }

    public function getAbsolutePath()
    {
        return $this->absolutePath;
    }

    public function setProperties()
    {
        if (!file_exists($this->getAbsolutePath())) {
            return;
        }

        $this->setMakeTimeOriginalFile(
            filemtime($this->getAbsolutePath())
        );

        if (!$this->getFileSizeOriginal()) {
            $this->setFileSizeOriginal(
                filesize($this->getAbsolutePath())
            );
        }
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
