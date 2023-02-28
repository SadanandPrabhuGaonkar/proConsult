<?php

namespace A3020\ImageOptimizer;

interface RemoteOptimizer
{
    /**
     * @return RemoteResult
     */
    public function execute();
}
