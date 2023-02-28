<?php

namespace A3020\ImageOptimizer\Provider;

use A3020\ImageOptimizer\ComposerLoader;
use A3020\ImageOptimizer\Optimizer\Gifsicle;
use A3020\ImageOptimizer\Optimizer\Jpegoptim;
use A3020\ImageOptimizer\Optimizer\Optipng;
use A3020\ImageOptimizer\Optimizer\Pngquant;
use A3020\ImageOptimizer\Optimizer\Svgo;
use A3020\ImageOptimizer\Optimizer\TinyPng;
use A3020\ImageOptimizer\OptimizerChain;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Config\Repository\Repository;

class JobServiceProvider implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var Repository
     */
    private $config;

    /**
     * @var ComposerLoader
     */
    private $composerLoader;

    public function __construct(Repository $config, ComposerLoader $composerLoader)
    {
        $this->config = $config;
        $this->composerLoader = $composerLoader;
    }

    public function register()
    {
        $this->composerLoader->load();

        $this->app->bind(OptimizerChain::class, function($app) {
            $chain = (new OptimizerChain());

            if ((bool) $this->config->get('image_optimizer::settings.enable_log')) {
                $chain->useLogger($this->app->make('log'));
            }

            // First check if TinyPNG is enabled and configured. If so, let's only use that service.
            if ((bool) $this->config->get('image_optimizer::settings.tiny_png.enabled')
                && !empty($this->config->get('image_optimizer::settings.tiny_png.api_key'))
            ) {
                $chain->addOptimizer(new TinyPng([
                    'api_key' => $this->config->get('image_optimizer::settings.tiny_png.api_key'),
                ]));
            } else {
                // Only use server side optimizers if TinyPNG is disabled or not configured.
                // The `proc_open` and `proc_close` functions are needed to run optimizers locally.
                if (function_exists('proc_open') && function_exists('proc_close')) {
                    $chain
                        ->addOptimizer(new Jpegoptim([
                            '--strip-all',
                            '--all-progressive',
                        ]))
                        ->addOptimizer(new Pngquant([
                            '--force',
                        ]))
                        ->addOptimizer(new Optipng([
                            '-i0',
                            '-o2',
                            '-quiet',
                        ]))
                        ->addOptimizer(new Svgo([
                            '--disable=cleanupIDs',
                        ]))
                        ->addOptimizer(new Gifsicle([
                            '-b',
                            '-O3',
                        ]));
                }
            }

            return $chain;
        });
    }
}
