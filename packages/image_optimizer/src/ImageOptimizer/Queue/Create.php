<?php

namespace A3020\ImageOptimizer\Queue;

use A3020\ImageOptimizer\CacheImageList;
use A3020\ImageOptimizer\Exception\MonthlyLimitReached;
use A3020\ImageOptimizer\FileList;
use A3020\ImageOptimizer\ThumbnailFileList;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Config\Repository\Repository;
use ZendQueue\Queue as ZendQueue;

class Create implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var Repository
     */
    private $config;

    /**
     * @var Precheck
     */
    private $precheck;

    public function __construct(Repository $config, Precheck $precheck)
    {
        $this->config = $config;
        $this->precheck = $precheck;
    }

    /**
     * @param ZendQueue $queue
     *
     * @return ZendQueue
     *
     * @throws MonthlyLimitReached
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Tinify\AccountException
     * @throws \Tinify\ClientException
     * @throws \Tinify\ConnectionException
     * @throws \Tinify\ServerException
     */
    public function create(ZendQueue $queue)
    {
        $this->precheck->check();

        if ($this->config->get('image_optimizer::settings.include_filemanager_images')) {
            /** @var FileList $list */
            $list = $this->app->make(FileList::class);
            foreach ($list->get() as $row) {
                $queue->send(json_encode($row));
            }
        }

        if ($this->config->get('image_optimizer::settings.include_thumbnail_images', true)) {
            /** @var ThumbnailFileList $list */
            $list = $this->app->make(ThumbnailFileList::class);
            foreach ($list->get() as $row) {
                $queue->send(json_encode($row));
            }
        }

        if ($this->config->get('image_optimizer::settings.include_cached_images')) {
            /** @var CacheImageList $list */
            $list = $this->app->make(CacheImageList::class);
            foreach ($list->get() as $path) {
                $queue->send(json_encode([
                    'path' => $path,
                ]));
            }
        }

        return $queue;
    }
}

