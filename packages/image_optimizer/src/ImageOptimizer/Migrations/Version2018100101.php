<?php

namespace A3020\ImageOptimizer\Migrations;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Config\Repository\Repository;

class Version2018100101 implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var Repository
     */
    private $config;

    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * Convert the 'old' config values to the packaged namespace config structure.
     */
    public function up()
    {
        $this->config->save('image_optimizer::settings.batch_size',
            $this->config->get('image_optimizer.batch_size')
        );

        $this->config->save('image_optimizer::settings.enable_log',
            $this->config->get('image_optimizer.enable_log', false)
        );

        $this->config->save('image_optimizer::settings.include_filemanager_images',
            $this->config->get('image_optimizer.include_filemanager_images', true)
        );

        $this->config->save('image_optimizer::settings.include_thumbnail_images',
            $this->config->get('image_optimizer.include_thumbnail_images', true)
        );

        $this->config->save('image_optimizer::settings.include_cached_images',
            $this->config->get('image_optimizer.include_cached_images', true)
        );

        $this->config->save('image_optimizer::settings.max_image_size',
            $this->config->get('image_optimizer.max_image_size')
        );

        $this->config->save('image_optimizer::settings.max_optimizations_per_month',
            $this->config->get('image_optimizer.max_optimizations_per_month')
        );

        $this->config->save('image_optimizer::settings.foundation.review.is_dismissed',
            $this->config->get('image_optimizer.foundation.review.is_dismissed')
        );

        $this->config->save('image_optimizer::settings.tiny_png.enabled',
            $this->config->get('image_optimizer.tiny_png.enabled')
        );

        $this->config->save('image_optimizer::settings.tiny_png.api_key',
            $this->config->get('image_optimizer.tiny_png.api_key')
        );

        // Remove all the old config values to prevent confusion.
        $this->config->save('image_optimizer', [
            'info' => 'This file should be removed!',
        ]);
    }
}
