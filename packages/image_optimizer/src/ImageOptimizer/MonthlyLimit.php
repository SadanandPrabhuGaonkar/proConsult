<?php

namespace A3020\ImageOptimizer;

use A3020\ImageOptimizer\Statistics\Month;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Config\Repository\Repository;
use Exception;

class MonthlyLimit implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var Repository
     */
    private $config;

    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * Return true if we've reached a monthly limit of image optimizations.
     *
     * @return bool
     */
    public function reached()
    {
        $max = (int) $this->config->get('image_optimizer::settings.tiny_png.max_optimizations_per_month');

        if (empty($max)) {
            return false;
        }

        if ((bool) $this->config->get('image_optimizer::settings.tiny_png.enabled')
            && $this->config->get('image_optimizer::settings.tiny_png.api_key')
        ) {
            $tinyPngCount = $this->getTinyPngNumberOfCompressions();

            return $tinyPngCount >= $max;
        }

        return false;
    }

    /**
     * @return int|null
     */
    public function getTinyPngNumberOfCompressions()
    {
        try {
            \Tinify\setKey($this->config->get('image_optimizer::settings.tiny_png.api_key'));
            \Tinify\validate();

            return (int) \Tinify\compressionCount();
        } catch (Exception $e) {}

        return null;
    }
}
