<?php

namespace A3020\ImageOptimizer\Migrations;

use A3020\ImageOptimizer\Entity\ProcessedFile;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Database\Connection\Connection;
use Concrete\Core\Database\DatabaseStructureManager;
use Exception;

class Version2018100205 implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var Connection
     */
    private $db;

    /**
     * @var DatabaseStructureManager
     */
    private $manager;


    public function __construct(Connection $db, DatabaseStructureManager $databaseStructureManager)
    {
        $this->db = $db;
        $this->manager = $databaseStructureManager;
    }

    // Make sure the 'type' column is filled.
    public function up()
    {
        $this->manager->refreshEntities();

        try {
            $this->db->executeQuery('
                UPDATE ImageOptimizerProcessedFiles SET `type` = ?
                WHERE
                    originalFileId IS NOT NULL 
                    AND fileVersionID IS NOT NULL 
                    AND thumbnailTypeHandle IS NULL
                    AND `type` IS NULL', [
                ProcessedFile::TYPE_ORIGINAL,
            ]);

            $this->db->executeQuery('
                UPDATE ImageOptimizerProcessedFiles SET `type` = ?
                WHERE
                    thumbnailTypeHandle IS NOT NULL
                    AND `type` IS NULL', [
                ProcessedFile::TYPE_THUMBNAIL,
            ]);

            $this->db->executeQuery('
                UPDATE ImageOptimizerProcessedFiles SET `type` = ?
                WHERE
                    path IS NOT NULL
                    AND `type` IS NULL',[
                ProcessedFile::TYPE_CACHE_FILE,
            ]);
        } catch (Exception $e) {}
    }
}
