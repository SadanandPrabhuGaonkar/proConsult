<?php

namespace A3020\ImageOptimizer\RequestHandler;

use A3020\ImageOptimizer\TinyPng\CompressionCount;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Database\Connection\Connection;

class ViewSettings
{
    /**
     * @var Repository
     */
    public $config;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var CompressionCount
     */
    private $compressionCount;

    public function __construct(Repository $config, Connection $connection, CompressionCount $compressionCount)
    {
        $this->config = $config;
        $this->connection = $connection;
        $this->compressionCount = $compressionCount;
    }

    /**
     * @return int
     */
    public function getNumberOfProcessedFiles()
    {
        return (int) $this->connection->fetchColumn('
            SELECT COUNT(1) FROM ImageOptimizerProcessedFiles 
        ');
    }

    /**
     * @return int|null
     */
    public function getTinyPngNumberOfCompressions()
    {
        if ((bool) $this->config->get('image_optimizer::settings.tiny_png.enabled')
            && $this->config->get('image_optimizer::settings.tiny_png.api_key')
        ) {
            return $this->compressionCount->get();
        }

        return null;
    }
}
