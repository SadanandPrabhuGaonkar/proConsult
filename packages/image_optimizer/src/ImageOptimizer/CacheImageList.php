<?php

namespace A3020\ImageOptimizer;

use A3020\ImageOptimizer\Finder\Finder;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Database\Connection\Connection;

class CacheImageList
{
    /**
     * @var Connection
     */
    private $db;

    /**
     * @var Repository
     */
    private $config;

    /**
     * @var Finder
     */
    private $finder;

    public function __construct(Connection $db, Repository $config, Finder $finder)
    {
        $this->db = $db;
        $this->config = $config;
        $this->finder = $finder;
    }

    /**
     * Return a list of cache image paths that haven't been optimized
     *
     * @return array
     */
    public function get()
    {
        $cacheFiles = [];
        foreach ($this->finder->getLocations() as $storagePath) {
            $cachePath = $storagePath . '/cache/';
            foreach ($this->finder->cacheImagesIn($cachePath) as $file) {
                $path = $cachePath . $file->getRelativePathname();

                $cacheFiles[] = str_replace('\\', '/', $path);
            }
        }

        $processed = $this->db->fetchAll('
            SELECT path 
            FROM ImageOptimizerProcessedFiles
            WHERE path IS NOT NULL
        ');

        $cacheFilesProcessed = array_column($processed, 'path');

        return array_diff($cacheFiles, $cacheFilesProcessed);
    }
}
