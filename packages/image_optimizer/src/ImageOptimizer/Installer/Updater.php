<?php

namespace A3020\ImageOptimizer\Installer;

use A3020\ImageOptimizer\Migration\Iterator;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;

class Updater implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var Iterator
     */
    private $iterator;

    public function __construct(Iterator $iterator)
    {
        $this->iterator = $iterator;
    }

    /**
     * @param \Concrete\Core\Package\Package $pkg
     */
    public function update($pkg)
    {
        $lastMigration = $this->app['config']->get('image_optimizer::settings.last_migration');

        foreach ($this->iterator->get() as $fileInfo) {
            // Run each migration once.
            if ($lastMigration > $fileInfo->getBasename()) {
                continue;
            }

            // Create an object of a single migration.
            $migration = $this->app->make(
                '\A3020\ImageOptimizer\Migrations\\' .
                $fileInfo->getBasename('.' . $fileInfo->getExtension())
            );
            $migration->up();

            // Mark the migration as executed.
            $this->app['config']->save('image_optimizer::settings.last_migration', $fileInfo->getBasename());
        }
    }
}
