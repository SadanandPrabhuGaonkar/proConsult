<?php

namespace A3020\ImageOptimizer\Ajax\Foundation;

use A3020\ImageOptimizer\Ajax\BaseController;

class DismissReview extends BaseController
{
    public function view()
    {
        $this->config->save('image_optimizer::settings.foundation.review.is_dismissed', true);
    }
}
