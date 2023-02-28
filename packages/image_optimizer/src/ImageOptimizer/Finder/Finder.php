<?php

namespace A3020\ImageOptimizer\Finder;

use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\File\StorageLocation\Configuration\LocalConfiguration;
use Concrete\Core\File\StorageLocation\StorageLocationFactory;

class Finder
{
    /**
     * @var Repository
     */
    private $config;

    /**
     * @var StorageLocationFactory
     */
    private $storageLocationFactory;

    /**
     * @param Repository $config
     * @param StorageLocationFactory $storageLocationFactory
     */
    public function __construct(Repository $config, StorageLocationFactory $storageLocationFactory)
    {
        $this->config = $config;
        $this->storageLocationFactory = $storageLocationFactory;
    }

    /**
     * Returns a list of relative paths in which cache images are stored.
     *
     * @return array
     */
    public function getLocations()
    {
        $locations = [];
        foreach ($this->storageLocationFactory->fetchList() as $storage) {
            /** @var LocalConfiguration $configurationObject */
            $configurationObject = $storage->getConfigurationObject();

            // External file storages are not supported, because we need
            // absolute paths for our local server based optimizers.
            if (!$configurationObject instanceof LocalConfiguration) {
                continue;
            }

            $locations[] = $configurationObject->getWebRootRelativePath();
        }

        return $locations;
    }

    /**
     * @param string $path Relative path from web root.
     *
     * @return \EmptyIterator|\Symfony\Component\Finder\Finder|\Symfony\Component\Finder\SplFileInfo[]
     */
    public function cacheImagesIn($path)
    {
        $finder = new \Symfony\Component\Finder\Finder();

        // Otherwise it won't find anything e.g. in Composer based install
        // where images are stored in /public + /application/files
        $absolutePath = DIR_BASE . $path;

        return $finder->files()->name('/\.(?:jpe?g|png|gif)$/')->in([$absolutePath]);
    }
}
