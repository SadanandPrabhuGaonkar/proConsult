<?php

namespace A3020\ImageOptimizer\Handler;

use A3020\ImageOptimizer\DummyLogger;
use A3020\ImageOptimizer\Entity\ProcessedFile;
use A3020\ImageOptimizer\FileSkipChecker;
use A3020\ImageOptimizer\OptimizerChain;
use A3020\ImageOptimizer\Repository\ProcessedFilesRepository;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Psr\Log\LoggerInterface;

abstract class BaseHandler implements ApplicationAwareInterface, HandlerInterface
{
    use ApplicationAwareTrait;

    /**
     * @var \A3020\ImageOptimizer\OptimizerChain
     */
    protected $optimizerChain;

    /**
     * @var ProcessedFilesRepository
     */
    protected $processedFilesRepository;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var FileSkipChecker
     */
    private $fileSkipChecker;

    public function __construct(OptimizerChain $optimizerChain, ProcessedFilesRepository $processedFilesRepository, FileSkipChecker $fileSkipChecker)
    {
        $this->optimizerChain = $optimizerChain;
        $this->processedFilesRepository = $processedFilesRepository;
        $this->fileSkipChecker = $fileSkipChecker;

        $this->useLogger(new DummyLogger());
    }

    /**
     * Store entity in database.
     *
     * @param ProcessedFile $processedFile
     */
    protected function save(ProcessedFile $processedFile)
    {
        $processedFile->touch();
        $this->processedFilesRepository->flush($processedFile);
    }

    /**
     * Sets a property if the file should be skipped.
     *
     * @param ProcessedFile $processedFile
     */
    protected function checker(ProcessedFile $processedFile)
    {
        $this->fileSkipChecker->check($processedFile);
    }

    /**
     * Calculates file size reduction.
     *
     * Suitable for thumbnails and cache files.
     *
     * @param ProcessedFile $processedFile
     *
     * @return int
     */
    protected function calculateReduction(ProcessedFile $processedFile)
    {
        // Results of file size can be cached
        clearstatcache();

        $after = filesize($processedFile->getAbsolutePath());

        return $processedFile->getFileSizeOriginal() - $after;
    }

    public function useLogger(LoggerInterface $log)
    {
        $this->logger = $log;

        return $this;
    }
}
