<?php

namespace A3020\ImageOptimizer\Migrations;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Database\Connection\Connection;
use Exception;

class Version2018100202 implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var Connection
     */
    private $db;

    public function __construct(Connection $connection)
    {
        $this->db = $connection;
    }

    // Remove an old image optimizer table.
    public function up()
    {
        try {
            $this->db->executeQuery('DROP TABLE IF EXISTS ImageOptimizerProcessedCacheFiles');
        } catch (Exception $e) {}
    }
}
