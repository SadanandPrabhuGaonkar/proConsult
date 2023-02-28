<?php

namespace A3020\ImageOptimizer\Optimizer;

class Optipng extends BaseOptimizer
{
    public $binaryName = 'optipng';

    public function canHandle($image)
    {
        return $image->mime() === 'image/png';
    }
}
