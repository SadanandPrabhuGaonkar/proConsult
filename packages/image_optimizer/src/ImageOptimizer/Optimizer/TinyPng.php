<?php

namespace A3020\ImageOptimizer\Optimizer;

use A3020\ImageOptimizer\RemoteOptimizer;
use A3020\ImageOptimizer\RemoteResult;
use Exception;

class TinyPng extends BaseOptimizer implements RemoteOptimizer
{
    public function canHandle($image)
    {
        return $image->mime() === 'image/png' || $image->mime() === 'image/jpeg';
    }

    public function getCommand()
    {
        return t('TinyPNG API for %s', $this->imagePath);
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $result = new RemoteResult();

        try {
            \Tinify\setKey($this->options['api_key']);

            $source = \Tinify\fromFile($this->imagePath);
            $source->preserve('copyright');
            $apiResult = $source->result();
            $apiResult->toFile($this->imagePath);

            $result->code = 200;
            $result->output = t('New file size: '.$apiResult->size());
        } catch (Exception $e) {
            $result->code = $e->getCode();
            $result->error = $e->getMessage();
        }

        return $result;
    }
}
