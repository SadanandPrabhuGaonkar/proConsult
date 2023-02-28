<?php

namespace A3020\ImageOptimizer;

use Concrete\Core\Package\PackageService;

class ComposerLoader
{
    /**
     * @var PackageService
     */
    private $packageService;

    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    public function load()
    {
        $pkg = $this->packageService->getByHandle('image_optimizer');

        require_once $pkg->getPackagePath() . '/vendor/autoload.php';
    }
}
