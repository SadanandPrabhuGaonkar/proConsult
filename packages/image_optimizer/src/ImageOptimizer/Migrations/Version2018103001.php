<?php

namespace A3020\ImageOptimizer\Migrations;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Page\Page;
use Concrete\Core\Page\Single;

class Version2018103001 implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var \Concrete\Core\Package\PackageService
     */
    private $packageService;

    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    public function up()
    {
        $pkg = $this->packageService->getClass('image_optimizer');

        $path = '/dashboard/files/image_optimizer/settings/tinypng';

        /** @var \Concrete\Core\Page\Page $page */
        $page = Page::getByPath($path);
        if (!$page || $page->isError()) {
            $page = Single::add($path, $pkg);
        }

        $name = 'Tinypng';
        if ($page->getCollectionName() !== $name) {
            $page->update([
                'cName' => $name
            ]);
        }
    }
}
