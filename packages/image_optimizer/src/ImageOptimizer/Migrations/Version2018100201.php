<?php

namespace A3020\ImageOptimizer\Migrations;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Database\Connection\Connection;
use Exception;

class Version2018100201 implements ApplicationAwareInterface
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

    // Because each file storage location has a different path, let's store the whole
    // relative path, instead of only the path relatively from the file storage location.
    public function up()
    {
        try {
            $cacheFiles = $this->connection->fetchAll("
               SELECT id, path FROM ImageOptimizerProcessedFiles WHERE path LIKE '/cache/%'
            ");

            foreach ($cacheFiles as $cacheFile) {
                $newPath = REL_DIR_FILES_UPLOADED_STANDARD . $cacheFile['path'];

                $this->connection->update('ImageOptimizerProcessedFiles', [
                    'path' => $newPath,
                ], [
                    'id' => $cacheFile['id'],
                ]);
            }
        } catch (Exception $e) {}
    }
}
