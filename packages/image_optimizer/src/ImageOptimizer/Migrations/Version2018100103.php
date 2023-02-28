<?php

namespace A3020\ImageOptimizer\Migrations;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Database\Connection\Connection;
use Exception;

class Version2018100103 implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    // The originalFileId, fileVersionId, and thumbnailTypeHandle are missing for thumbnails.
    // Try to find the associated files and update the records.
    public function up()
    {
        try {
            $thumbs = $this->connection->fetchAll("
               SELECT id, path FROM ImageOptimizerProcessedFiles 
               WHERE path LIKE '/thumbnails%'
            ");

            foreach ($thumbs as $thumb) {
                $this->update($thumb);
            }
        } catch (Exception $e) {}
    }

    /**
     * @param array $thumb
     *
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     */
    private function update($thumb)
    {
        $fileInfo = $this->connection->fetchAll("
           SELECT fileID, fileVersionID, thumbnailTypeHandle
           FROM FileImageThumbnailPaths 
           WHERE path = ?
        ", [
            $thumb['path'],
        ]);

        if (count($fileInfo)) {
            $fileInfo = current($fileInfo);

            $this->connection->update('ImageOptimizerProcessedFiles', [
                'originalFileId' => (int) $fileInfo['fileID'],
                'fileVersionId' => (int) $fileInfo['fileVersionID'],
                'thumbnailTypeHandle' => $fileInfo['thumbnailTypeHandle'],
                'path' => null,
            ], [
                'id' => $thumb['id'],
            ]);
        } else {
            $this->connection->delete('ImageOptimizerProcessedFiles', [
                'id' => $thumb['id'],
            ]);
        }
    }
}
