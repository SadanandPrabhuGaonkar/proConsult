<?php

namespace A3020\ImageOptimizer;

use A3020\ImageOptimizer\Entity\ProcessedFile;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Database\Connection\Connection;

class ThumbnailFileList
{
    /**
     * @var Connection
     */
    private $db;

    /**
     * @var Repository
     */
    private $config;

    public function __construct(Connection $db, Repository $config)
    {
        $this->db = $db;
        $this->config = $config;
    }

    /**
     * Return a list of thumbnails that haven't been optimized.
     *
     * @return array [
     *   'fileId' => int
     *   'fileVersionId' => int
     *   'thumbnailTypeHandle' => string
     * ]
     *
     * PS. I tried converting this to a join query, but got stuck terribly.
     */
    public function get()
    {
        $available = $this->db->fetchAll('
            SELECT fileID as fileId, fileVersionID as fileVersionId, thumbnailTypeHandle 
            FROM FileImageThumbnailPaths
            WHERE isBuilt = 1
        ');

        $processed = $this->db->fetchAll('
            SELECT originalFileId as fileId, fileVersionId, thumbnailTypeHandle 
            FROM ImageOptimizerProcessedFiles 
            WHERE `type` = ?
        ', [ProcessedFile::TYPE_THUMBNAIL]);

        return array_udiff($available, $processed, function($row1, $row2) {
            return strcmp(serialize($row1), serialize($row2));
        });
    }
}
