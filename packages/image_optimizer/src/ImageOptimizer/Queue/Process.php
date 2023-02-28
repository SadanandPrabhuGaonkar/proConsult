<?php

namespace A3020\ImageOptimizer\Queue;

use A3020\ImageOptimizer\Entity\ProcessedFile;
use A3020\ImageOptimizer\Exception\HandlerNotFound;
use A3020\ImageOptimizer\Handler\HandlerInterface;
use A3020\ImageOptimizer\MonthlyLimit;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Config\Repository\Repository;
use Exception;
use Psr\Log\LoggerInterface;
use ZendQueue\Message as ZendQueueMessage;

class Process implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var \A3020\ImageOptimizer\MonthlyLimit
     */
    private $monthlyLimit;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var Repository
     */
    private $config;

    public function __construct(MonthlyLimit $monthlyLimit, LoggerInterface $logger, Repository $config)
    {
        $this->monthlyLimit = $monthlyLimit;
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * @param ZendQueueMessage $msg
     *
     * @return ProcessedFile|null
     */
    public function process(ZendQueueMessage $msg)
    {
        if ($this->monthlyLimit->reached()) {
            return null;
        }

        try {
            $body = json_decode($msg->body, true);

            $handler = $this->makeHandler($body);

            if ((bool)$this->config->get('image_optimizer::settings.enable_log')) {
                $handler->useLogger($this->logger);
            }

            $file = $handler->process($body);

            $this->clearCache();

            return $file;
        } catch (HandlerNotFound $e) {
            // Silently fail. This is most likely because earlier version of IO used 'fID'
            // in the queue table. Newer versions use 'fileId'. By returning null,
            // this queue message will just disappear, and next time an image is processed,
            // it'll use the correct file id reference.
        } catch (Exception $e) {
            $this->logger->debug($e->getMessage() . $e->getFile() . $e->getLine() . $e->getTraceAsString());
        }

        return null;
    }

    /**
     * @param array $body
     *
     * @return HandlerInterface
     *
     * @throws HandlerNotFound
     */
    private function makeHandler($body)
    {
        // Thumbnail
        if (isset($body['fileId']) && isset($body['fileVersionId']) && isset($body['thumbnailTypeHandle'])) {
            return $this->app->make(\A3020\ImageOptimizer\Handler\Thumbnail::class);
        }

        // Original / normal file
        if (isset($body['fileId'])) {
           return $this->app->make(\A3020\ImageOptimizer\Handler\Original::class);
        }

        // Cache file
        if (isset($body['path'])) {
            return $this->app->make(\A3020\ImageOptimizer\Handler\CacheFile::class);
        }

        throw new HandlerNotFound();
    }

    /**
     * Clear the list of optimized images.
     *
     * This cache is used on the Optimized Images page
     * quickly return an AJAX response.
     */
    private function clearCache()
    {
        /** @var \Concrete\Core\Cache\Level\ExpensiveCache $expensiveCache */
        $expensiveCache = $this->app->make('cache/expensive');

        $cacheList = $expensiveCache->getItem('ImageOptimizer/OptimizedImagesList');
        $cacheList->clear();
    }
}
