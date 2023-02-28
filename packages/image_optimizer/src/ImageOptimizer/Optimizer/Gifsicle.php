<?php

namespace A3020\ImageOptimizer\Optimizer;

class Gifsicle extends BaseOptimizer
{
    public $binaryName = 'gifsicle';

    public function canHandle($image)
    {
        return $image->mime() === 'image/gif';
    }
}
