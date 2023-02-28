<?php

namespace A3020\ImageOptimizer;

use A3020\ImageOptimizer\Entity\ProcessedFile;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Database\Connection\Connection;

class FileList
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var Repository
     */
    private $config;

    public function __construct(Connection $connection, Repository $config)
    {
        $this->connection = $connection;
        $this->config = $config;
    }

    /**
     * Return all file ids that haven't been processed
     *
     * - We only want images (fvType = 1)
     * - If there is a new version of a file, the file needs to be processed again
     * - If the fileVersionId IS NULL, it hasn't been processed at all
     *
     * @return array
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function get()
    {
        return $this->connection->executeQuery(
        'SELECT fv.fID as fileId, fv.fvID, pf.fileVersionId FROM (
                SELECT MAX(fvID) as fvID, fID FROM FileVersions fv
                WHERE fv.fvType = ? AND fv.fvSize < ?
                GROUP BY fID
              ) AS fv
              LEFT JOIN ImageOptimizerProcessedFiles pf ON pf.originalFileId = fv.fID AND pf.`type`= ?
              WHERE pf.fileVersionId IS NULL
              OR (
                SELECT MAX(fileVersionId) FROM ImageOptimizerProcessedFiles WHERE originalFileID = fv.fID
              ) < fv.fvID
        ', [
            \Concrete\Core\File\Type\Type::T_IMAGE,
            $this->getMaxSize(),
            ProcessedFile::TYPE_ORIGINAL,
        ])->fetchAll();
    }

    /**
     * Return the max file size of images
     *
     * If it hasn't been defined, we'll use a high value
     *
     * @return int max size in bytes
     */
    private function getMaxSize()
    {
        // This is in KB
        $maxSize = (int) $this->config->get('image_optimizer::settings.max_image_size');
        $maxSize = $maxSize ? $maxSize : 999999;

        return $maxSize * 1024;
    }
}
