<?php

namespace A3020\ImageOptimizer\Queue;

use A3020\ImageOptimizer\Repository\ProcessedFilesRepository;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Support\Facade\Url;
use Exception;
use ZendQueue\Queue as ZendQueue;

class Finish implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var Repository
     */
    private $config;

    /**
     * @var ProcessedFilesRepository
     */
    private $repository;

    /**
     * @var Precheck
     */
    private $precheck;

    public function __construct(Repository $config, ProcessedFilesRepository $repository, Precheck $precheck)
    {
        $this->config = $config;
        $this->repository = $repository;
        $this->precheck = $precheck;
    }

    /**
     * @param ZendQueue $q
     *
     * @return string
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function finish(ZendQueue $q)
    {
        $nh = $this->app->make('helper/number');
        $totalSavedDiskSpace = $this->repository->totalFileSize();

        if ($totalSavedDiskSpace === 0) {
            throw new Exception(t("Do you have any of the optimizers installed or configured? The Image Optimizer couldn't gain any file size. Read more: %s",
                '<a href="https://www.concrete5.org/marketplace/addons/image-optimizer/faq/" target="_blank">https://www.concrete5.org/marketplace/addons/image-optimizer/faq/</a>'
            ));
        }

        return t('All images have been optimized. %sImage Optimizer%s has saved you %s of disk space.',
            '<a href="'.Url::to('/dashboard/files/image_optimizer').'">',
            '</a>',
            $nh->formatSize($totalSavedDiskSpace)
        );
    }

    /**
     * @throws \A3020\ImageOptimizer\Exception\MonthlyLimitReached
     * @throws \Tinify\AccountException
     * @throws \Tinify\ClientException
     * @throws \Tinify\ConnectionException
     * @throws \Tinify\ServerException
     */
    public function precheck()
    {
        $this->precheck->check();
    }
}
