<?php

namespace A3020\ImageOptimizer\Optimizer;

class Pngquant extends BaseOptimizer
{
    public $binaryName = 'pngquant';

    public function canHandle($image)
    {
        return $image->mime() === 'image/png';
    }

    public function getCommand()
    {
        $optionString = implode(' ', $this->options);

        return "{$this->binaryName} {$optionString}"
            .' '.escapeshellarg($this->imagePath)
            .' --output='.escapeshellarg($this->imagePath);
    }
}
