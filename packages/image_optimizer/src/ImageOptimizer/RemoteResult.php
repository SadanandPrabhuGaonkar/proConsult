<?php

namespace A3020\ImageOptimizer;

class RemoteResult
{
    public $code;
    public $error;
    public $output;

    public function isSuccessful()
    {
        return $this->code === 200;
    }

    public function getErrorOutput()
    {
        return $this->error;
    }

    public function getOutput()
    {
        return $this->output;
    }
}
