<?php

namespace Concrete\Package\ImageOptimizer\Controller\SinglePage\Dashboard\Files;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Routing\Redirect;

final class ImageOptimizer extends DashboardPageController
{
    public function view()
    {
        return Redirect::to('/dashboard/files/image_optimizer/search');
    }
}
