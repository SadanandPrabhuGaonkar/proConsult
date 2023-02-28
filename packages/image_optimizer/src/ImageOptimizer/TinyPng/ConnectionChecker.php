<?php

namespace A3020\ImageOptimizer\TinyPng;

use A3020\ImageOptimizer\ComposerLoader;

class ConnectionChecker
{
    /**
     * @var ComposerLoader
     */
    private $composerLoader;

    public function __construct(ComposerLoader $composerLoader)
    {
        $this->composerLoader = $composerLoader;
    }

    /**
     * @param string $key
     *
     * @throws \Tinify\AccountException
     * @throws \Tinify\ClientException
     * @throws \Tinify\ConnectionException
     * @throws \Tinify\ServerException
     */
    public function check($key)
    {
        $this->composerLoader->load();
        \Tinify\setKey($key);
        \Tinify\Tinify::getClient();
        \Tinify\validate();
    }
}
