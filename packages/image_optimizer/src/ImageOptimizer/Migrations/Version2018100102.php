<?php

namespace A3020\ImageOptimizer\Migrations;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Database\DatabaseStructureManager;

class Version2018100102 implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var DatabaseStructureManager
     */
    private $manager;

    public function __construct(DatabaseStructureManager $databaseStructureManager)
    {
        $this->manager = $databaseStructureManager;
    }

    public function up()
    {
        $this->manager->refreshEntities();
    }
}
