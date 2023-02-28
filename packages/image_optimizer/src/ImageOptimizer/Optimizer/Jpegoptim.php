<?php

namespace A3020\ImageOptimizer\Optimizer;

class Jpegoptim extends BaseOptimizer
{
    public $binaryName = 'jpegoptim';

    public function canHandle($image)
    {
        return $image->mime() === 'image/jpeg';
    }
}
