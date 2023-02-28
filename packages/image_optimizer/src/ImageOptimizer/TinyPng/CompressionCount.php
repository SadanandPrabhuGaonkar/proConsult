<?php

namespace A3020\ImageOptimizer\TinyPng;

use A3020\ImageOptimizer\ComposerLoader;
use Concrete\Core\Config\Repository\Repository;
use Exception;

class CompressionCount
{
    /**
     * @var ComposerLoader
     */
    private $composerLoader;

    /**
     * @var Repository
     */
    private $config;

    public function __construct(ComposerLoader $composerLoader, Repository $config)
    {
        $this->composerLoader = $composerLoader;
        $this->config = $config;
    }

    /**
     * @return int|null
     */
    public function get()
    {
        try {
            $this->composerLoader->load();

            \Tinify\setKey($this->config->get('image_optimizer::settings.tiny_png.api_key'));
            \Tinify\validate();

            return \Tinify\compressionCount();
        } catch (Exception $e) { }

        return null;
    }
}
