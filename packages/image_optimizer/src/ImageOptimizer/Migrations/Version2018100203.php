<?php

namespace A3020\ImageOptimizer\Migrations;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Page\Page;
use Exception;

class Version2018100203 implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    // Remove an old image optimizer page.
    // This page is now moved to /dashboard/files/image_optimizer.
    public function up()
    {
        try {
            /** @var Page $page */
            $page = Page::getByPath('/dashboard/system/files/image_optimizer');
            if ($page && !$page->isError()) {
                $page->delete();
            }
        } catch (Exception $e) {}
    }
}
