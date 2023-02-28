<?php

namespace A3020\ImageOptimizer\Migrations;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Config\Repository\Repository;

class Version2018100204 implements ApplicationAwareInterface
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

    // Make the max number of optimizations per month specific to TinyPNG.
    public function up()
    {
       $this->config->save('image_optimizer::settings.tiny_png.max_optimizations_per_month',
           $this->config->get('image_optimizer::settings.max_optimizations_per_month')
       );

       $settings = $this->config->get('image_optimizer::settings');

       // Remove old config key/value.
       if (isset($settings['max_optimizations_per_month'])) {
           unset($settings['max_optimizations_per_month']);
           $this->config->save('image_optimizer::settings', $settings);
       }
    }
}
