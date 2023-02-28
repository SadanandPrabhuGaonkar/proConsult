<?php

namespace A3020\ImageOptimizer\Listener;

use A3020\ImageOptimizer\Entity\ProcessedFile;
use Doctrine\ORM\EntityManager;
use Exception;
use Psr\Log\LoggerInterface;

class FileDelete
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repository;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(EntityManager $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(ProcessedFile::class);
        $this->logger = $logger;
    }

    /**
     * Removes image optimizer references when a file is removed.
     *
     * @param \Concrete\Core\File\Event\DeleteFile $event
     */
    public function handle($event)
    {
        try {
            // Get records for optimized thumbs / file versions of this file.
            $processedFiles = $this->repository->findBy([
                'originalFileId' => $event->getFileObject()->getFileID(),
            ]);

            // Delete those records.
            foreach ($processedFiles as $processedFile) {
                $this->entityManager->remove($processedFile);
            }

            $this->entityManager->flush();
        } catch (Exception $e) {
            $this->logger->debug($e->getMessage());
        }
    }
}
