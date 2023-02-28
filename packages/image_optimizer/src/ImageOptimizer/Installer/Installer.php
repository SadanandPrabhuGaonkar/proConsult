<?php

namespace A3020\ImageOptimizer\Installer;

use A3020\ImageOptimizer\Migration\Iterator;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Database\DatabaseStructureManager;
use Concrete\Core\Entity\Attribute\Key\Settings\BooleanSettings;
use Concrete\Core\Page\Page;
use Concrete\Core\Page\Single;
use Concrete\Core\Attribute\Key\Category;
use Concrete\Core\Attribute\Key\FileKey;
use Concrete\Core\Attribute\Type;
use Concrete\Core\Job\Job;
use Doctrine\ORM\EntityManager;

class Installer implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Repository
     */
    private $config;

    /**
     * @var Iterator
     */
    private $iterator;

    public function __construct(Repository $config, EntityManager $entityManager, Iterator $iterator)
    {
        $this->config = $config;
        $this->entityManager = $entityManager;
        $this->iterator = $iterator;
    }

    public function install($pkg)
    {
        $this->configure();
        $this->installPages($pkg);
        $this->installFileAttribute($pkg);
        $this->installJob($pkg);
        $this->refreshEntities();
    }

    private function installPages($pkg)
    {
        $pages = [
            '/dashboard/files/image_optimizer' => 'Image Optimizer',
            '/dashboard/files/image_optimizer/search' => 'Optimized Images',
            '/dashboard/files/image_optimizer/settings' => 'Settings',
            '/dashboard/files/image_optimizer/settings/tinypng' => 'Tinypng',
        ];

        foreach ($pages as $path => $name) {
            /** @var Page $page */
            $page = Page::getByPath($path);
            if (!$page || $page->isError()) {
                $page = Single::add($path, $pkg);
            }

            if ($page->getCollectionName() !== $name) {
                $page->update([
                    'cName' => $name
                ]);
            }
        }
    }

    private function installFileAttribute($pkg)
    {
        $handle = 'exclude_from_image_optimizer';
        $ak = FileKey::getByHandle($handle);
        if ($ak) {
            return;
        }

        $type = Type::getByHandle('boolean');
        $entity = Category::getByHandle('file');
        $category = $entity->getAttributeKeyCategory();

        $key = [
            'akHandle' => $handle,
            'akName' => t('Exclude from Image Optimizer'),
        ];

        $settings = new BooleanSettings();
        $settings->setIsCheckedByDefault(true);

        /** @var $category \Concrete\Core\Attribute\Category\FileCategory */
        $category->add($type, $key, $settings, $pkg);
    }

    private function installJob($pkg)
    {
        $job = Job::getByHandle('image_optimizer');
        if (!$job) {
            Job::installByPackage('image_optimizer', $pkg);
        }
    }

    private function configure()
    {
        if ($this->config->get('image_optimizer::settings.batch_size') !== null) {
            // The add-on has been installed before
            // we will not overwrite existing config settings
            return;
        }

        $this->config->save('image_optimizer::settings.enable_log', false);
        $this->config->save('image_optimizer::settings.include_filemanager_images', true);
        $this->config->save('image_optimizer::settings.include_thumbnail_images', true);
        $this->config->save('image_optimizer::settings.include_cached_images', true);
        $this->config->save('image_optimizer::settings.batch_size', 5);

        // New installations don't have to re-run migrations.
        $this->config->save('image_optimizer::settings.last_migration', $this->iterator->getLastMigration());
    }

    private function refreshEntities()
    {
        $manager = new DatabaseStructureManager($this->entityManager);
        $manager->refreshEntities();
    }
}
