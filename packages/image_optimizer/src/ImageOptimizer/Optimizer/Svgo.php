<?php

namespace A3020\ImageOptimizer\Optimizer;

class Svgo extends BaseOptimizer
{
    public $binaryName = 'svgo';

    public function canHandle($image)
    {
        if ($image->extension() !== 'svg') {
            return false;
        }

        return $image->mime() === 'text/html';
    }

    public function getCommand()
    {
        $optionString = implode(' ', $this->options);

        return "{$this->binaryName} {$optionString}"
           .' --input='.escapeshellarg($this->imagePath)
           .' --output='.escapeshellarg($this->imagePath);
    }
}
