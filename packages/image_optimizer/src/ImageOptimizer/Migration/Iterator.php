<?php

namespace A3020\ImageOptimizer\Migration;

use Concrete\Core\Support\Facade\Package;
use FilesystemIterator;

class Iterator
{
    public function get()
    {
        /** @var \Concrete\Core\Package\Package $pkg */
        $pkg = Package::getClass('image_optimizer');

        return new FilesystemIterator(
            $pkg->getPackagePath() . '/src/ImageOptimizer/Migrations',
            FilesystemIterator::SKIP_DOTS
        );
    }

    /**
     * E.g. Version2018100203.php
     *
     * @return string
     */
    public function getLastMigration()
    {
        $iterator = $this->get();

        $last = '';
        foreach ($iterator as $fileInfo) {
            $last = $fileInfo->getBaseName();
        }

        return $last;
    }
}

