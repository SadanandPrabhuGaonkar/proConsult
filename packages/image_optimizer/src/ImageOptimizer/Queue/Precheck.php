<?php

namespace A3020\ImageOptimizer\Queue;

use A3020\ImageOptimizer\Exception\MonthlyLimitReached;
use A3020\ImageOptimizer\Exception\ServerOptimizersMissing;
use A3020\ImageOptimizer\MonthlyLimit;
use A3020\ImageOptimizer\OptimizerChain;
use A3020\ImageOptimizer\TinyPng\ConnectionChecker;
use Concrete\Core\Config\Repository\Repository;

class Precheck
{
    /**
     * @var MonthlyLimit
     */
    private $monthlyLimit;

    /**
     * @var Repository
     */
    private $config;

    /**
     * @var ConnectionChecker
     */
    private $tinyPngConnectionChecker;

    /**
     * @var OptimizerChain
     */
    private $optimizerChain;

    public function __construct(
        MonthlyLimit $monthlyLimit,
        Repository $config,
        ConnectionChecker $tinyPngConnectionChecker,
        OptimizerChain $optimizerChain
    )
    {
        $this->monthlyLimit = $monthlyLimit;
        $this->config = $config;
        $this->tinyPngConnectionChecker = $tinyPngConnectionChecker;
        $this->optimizerChain = $optimizerChain;
    }

    /**
     * @throws MonthlyLimitReached
     * @throws \Tinify\AccountException
     * @throws \Tinify\ClientException
     * @throws \Tinify\ConnectionException
     * @throws \Tinify\ServerException
     * @throws ServerOptimizersMissing
     */
    public function check()
    {
        if ($this->monthlyLimit->reached()) {
            throw new MonthlyLimitReached('Monthly limit reached.');
        }

        // Check if TinyPNG is enabled and set up correctly.
        if ((bool) $this->config->get('image_optimizer::settings.tiny_png.enabled')) {
            $this->tinyPngConnectionChecker->check(
                $this->config->get('image_optimizer::settings.tiny_png.api_key')
            );
        }

        // Make sure at least one optimizer is installed.
        if (!(bool) $this->config->get('image_optimizer::settings.tiny_png.enabled')) {
            $this->checkServerOptimizers();
        }
    }

    /**
     * @throws ServerOptimizersMissing
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     */
    private function checkServerOptimizers()
    {
        $enabled = [];
        foreach ($this->optimizerChain->getOptimizers() as $optimizer) {
            if (!$optimizer->binaryName()) {
                continue;
            }

            $process = new \Symfony\Component\Process\Process($optimizer->binaryName());
            $process->run();

            if ($process->isSuccessful()) {
                $enabled[] = $optimizer->binaryName();
            }
        }

        if (count($enabled) === 0) {
            throw new ServerOptimizersMissing(
                t('No optimizers are installed or configured.') . ' ' .
                t('Please configure TinyPNG or check the installation instructions.')
            );
        }
    }
}
