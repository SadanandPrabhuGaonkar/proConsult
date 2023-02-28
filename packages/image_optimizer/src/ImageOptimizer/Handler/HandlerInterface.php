<?php

namespace A3020\ImageOptimizer\Handler;

use Psr\Log\LoggerInterface;

interface HandlerInterface
{
    /**
     * @param array $body Array that derives from the Queue.
     *
     * @see \A3020\ImageOptimizer\Queue\Create
     */
    public function process($body);

    public function useLogger(LoggerInterface $log);
}