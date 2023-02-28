<?php

namespace Concrete\Package\ImageOptimizer;

use A3020\ImageOptimizer\Installer\Installer;
use A3020\ImageOptimizer\Installer\Updater;
use A3020\ImageOptimizer\Provider\ServiceProvider;
use Concrete\Core\Package\Package;
use Concrete\Core\Support\Facade\Package as PackageFacade;

final class Controller extends Package
{
    protected $pkgHandle = 'image_optimizer';
    protected $appVersionRequired = '8.3.1';
    protected $pkgVersion = '3.2.11';
    protected $pkgAutoloaderRegistries = [
        'src/ImageOptimizer' => '\A3020\ImageOptimizer',
    ];

    public function getPackageName()
    {
        return t('Image Optimizer');
    }

    public function getPackageDescription()
    {
        return t('Optimizes PNGs, JPGs, SVGs, and GIFs.');
    }

    public function on_start()
    {
        /** @var ServiceProvider $provider */
        $provider = $this->app->make(ServiceProvider::class);
        $provider->register();
    }

    public function install()
    {
        $pkg = parent::install();

        /** @var Installer $installer */
        $installer = $this->app->make(Installer::class);
        $installer->install($pkg);
    }

    public function upgrade()
    {
        /** @var \Concrete\Core\Package\Package $pkg */
        $pkg = PackageFacade::getClass($this->pkgHandle);

        /** @var Updater $installer */
        $installer = $this->app->make(Updater::class);
        $installer->update($pkg);
    }

    public function uninstall()
    {
        parent::uninstall();

        $db = $this->app->make('database')->connection();
        $db->executeQuery("DROP TABLE IF EXISTS ImageOptimizerProcessedFiles");
    }
}
