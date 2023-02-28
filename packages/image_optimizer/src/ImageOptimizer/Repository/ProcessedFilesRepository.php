<?php

namespace A3020\ImageOptimizer\Repository;

use A3020\ImageOptimizer\Entity\ProcessedFile;
use Doctrine\ORM\EntityManager;

final class ProcessedFilesRepository
{
    /** @var EntityManager */
    private $entityManager;

    /** @var \Doctrine\ORM\EntityRepository */
    protected $repository;

    public function __construct(EntityManager $em)
    {
        $this->entityManager = $em;
        $this->repository = $this->entityManager->getRepository(ProcessedFile::class);
    }

    /**
     * @return ProcessedFile[]
     */
    public function findAll()
    {
        return $this->repository->findBy(
            [],
            [
                'fileSizeReduction' => 'desc',
            ],
            2000
        );
    }

    /**
     * @param int $fileId
     * @param int $versionId
     *
     * @return ProcessedFile
     */
    public function findOrCreateOriginal($fileId, $versionId)
    {
        /** @var ProcessedFile $record */
        $record = $this->repository->findOneBy([
            'originalFileId' => $fileId,
            'fileVersionId' => $versionId,
            'type' => ProcessedFile::TYPE_ORIGINAL,
        ]);

        if (!$record) {
            $record = new ProcessedFile();
            $record->setType(ProcessedFile::TYPE_ORIGINAL);
            $record->setOriginalFileId($fileId);
            $record->setFileVersionId($versionId);

            $this->flush($record);
        }

        return $record;
    }

    /**
     * @param int $fileId
     * @param int $fileVersionId
     * @param string $thumbnailTypeHandle
     *
     * @return ProcessedFile
     */
    public function findOrCreateThumbnail($fileId, $fileVersionId, $thumbnailTypeHandle)
    {
        /** @var ProcessedFile $record */
        $record = $this->repository->findOneBy([
            'originalFileId' => $fileId,
            'fileVersionId' => $fileVersionId,
            'thumbnailTypeHandle' => $thumbnailTypeHandle,
            'type' => ProcessedFile::TYPE_THUMBNAIL,
        ]);

        if (!$record) {
            $record = new ProcessedFile();
            $record->setType(ProcessedFile::TYPE_THUMBNAIL);
            $record->setOriginalFileId($fileId);
            $record->setFileVersionId($fileVersionId);
            $record->setThumbnailTypeHandle($thumbnailTypeHandle);

            $this->flush($record);
        }

        return $record;
    }

    /**
     * @param string $path
     *
     * @return ProcessedFile
     */
    public function findOrCreateCacheFile($path)
    {
        /** @var ProcessedFile $record */
        $record = $this->repository->findOneBy([
            'path' => $path,
            'type' => ProcessedFile::TYPE_CACHE_FILE,
        ]);

        if (!$record) {
            $record = new ProcessedFile();
            $record->setType(ProcessedFile::TYPE_CACHE_FILE);
            $record->setPath($path);

            $this->flush($record);
        }

        return $record;
    }

    /**
     * @return float
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function totalFileSize()
    {
        return (float) $this->repository
            ->createQueryBuilder('pf')
            ->select('SUM(pf.fileSizeReduction) as fileSize')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function remove(ProcessedFile $file)
    {
        $this->entityManager->remove($file);
        $this->entityManager->flush();
    }

    public function removeOne($id)
    {
        /** @var ProcessedFile $record */
        $record = $this->repository->find($id);
        if ($record) {
            $this->entityManager->remove($record);
            $this->entityManager->flush();

            return true;
        }

        return false;
    }

    public function removeByPath($path)
    {
        /** @var ProcessedFile $record */
        $record = $this->repository->findOneBy([
            'path' => $path,
        ]);
        if ($record) {
            $this->entityManager->remove($record);
            $this->entityManager->flush();

            return true;
        }

        return false;
    }

    public function removeAll()
    {
        $this->entityManager
            ->getConnection()
            ->executeQuery('TRUNCATE TABLE ImageOptimizerProcessedFiles');
    }

    public function flush($record)
    {
        $this->entityManager->persist($record);
        $this->entityManager->flush();
    }

    /**
     * @return int
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function totalFiles()
    {
        return (int) $this->entityManager
            ->getConnection()
            ->fetchColumn('SELECT COUNT(1) FROM ImageOptimizerProcessedFiles');
    }
}
