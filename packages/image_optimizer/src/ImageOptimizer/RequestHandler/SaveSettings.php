<?php

namespace A3020\ImageOptimizer\RequestHandler;

use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Http\Request;

class SaveSettings
{
    /**
     * @var Repository
     */
    public $config;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Repository $config, Request $request)
    {
        $this->config = $config;
        $this->request = $request;
    }

    /**
     * Return the integer value, otherwise NULL
     *
     * @param $name
     *
     * @return int|null
     */
    public function getOrNull($name)
    {
        $value = (int) $this->request->request->get($name);
        if (empty($value)) {
            $value = null;
        }

        return $value;
    }
}
