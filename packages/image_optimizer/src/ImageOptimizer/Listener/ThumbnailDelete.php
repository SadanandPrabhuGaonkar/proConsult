<?php

namespace A3020\ImageOptimizer\Listener;

use A3020\ImageOptimizer\Repository\ProcessedFilesRepository;
use Exception;
use Psr\Log\LoggerInterface;

class ThumbnailDelete
{
    /**
     * @var ProcessedFilesRepository
     */
    private $repository;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(ProcessedFilesRepository $repository, LoggerInterface $logger)
    {
        $this->repository = $repository;
        $this->logger = $logger;
    }

    /**
     * Event is available since 8.4.0RC5.
     *
     * https://github.com/concrete5/concrete5/pull/6715/commits/49be245e196a3ba87862062417dd56d3a6f20775
     *
     * @param \Concrete\Core\File\Event\ThumbnailDelete $event
     */
    public function handle($event)
    {
        try {
            $this->repository->removeByPath($event->getPath());
        } catch (Exception $e) {
            $this->logger->debug($e->getMessage());
        }
    }
}
