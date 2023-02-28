<?php

namespace A3020\ImageOptimizer\Ajax;

use A3020\ImageOptimizer\Entity\ProcessedFile;
use A3020\ImageOptimizer\Repository\ProcessedFilesRepository;
use Concrete\Core\Http\ResponseFactory;

class Files extends BaseController
{
    /** @var \Concrete\Core\Utility\Service\Number */
    protected $numberHelper;

    public function view()
    {
        $this->numberHelper = $this->app->make('helper/number');

        return $this->app->make(ResponseFactory::class)->json([
            'data' => $this->getFiles(),
        ]);
    }

    /**
     * @return array
     */
    private function getFiles()
    {
        /** @var \Concrete\Core\Cache\Level\ExpensiveCache $expensiveCache */
        $expensiveCache = $this->app->make('cache/expensive');

        $cacheList = $expensiveCache->getItem('ImageOptimizer/OptimizedImagesList');
        if (!$cacheList->isMiss()) {
            return $cacheList->get();
        }

        $cacheList->lock();

        $records = [];

        /** @var ProcessedFilesRepository $repository */
        $repository = $this->app->make(ProcessedFilesRepository::class);
        foreach ($repository->findAll() as $processedFile) {
            $record = [];

            $sizeKb = $this->size($processedFile->getFileSizeReduction());

            $path = $processedFile->getComputedPath();
            if (!$path) {
                $this->deleteOldRecord($processedFile);
                continue;
            }

            $record['path'] = $path;
            $record['is_original'] = $processedFile->isOriginal();
            $record['id'] = $processedFile->getId();
            $record['size_original'] = $this->size($processedFile->getFileSizeOriginal());
            $record['size_original_human'] = $sizeKb > 1024 ? $this->numberHelper->formatSize($processedFile->getFileSizeOriginal()) : '';
            $record['size_optimized'] = $this->size($processedFile->getFileSizeNew());
            $record['size_optimized_human'] = $sizeKb > 1024 ? $this->numberHelper->formatSize($processedFile->getFileSizeNew()) : '';
            $record['size_reduction'] = $this->size($processedFile->getFileSizeReduction());
            $record['size_reduction_human'] = $sizeKb > 1024 ? $this->numberHelper->formatSize($processedFile->getFileSizeReduction()) : '';
            $record['skip_reason'] = $processedFile->getSkipReason();
            $record['date'] = $processedFile->getProcessedAt() ? $processedFile->getProcessedAt()->format('Y-m-d H:i:s') : '-';

            $records[] = $record;
        }

        $expensiveCache->save($cacheList->set($records));

        return $records;
    }

    /**
     * @param int $size in bytes
     *
     * @return float
     */
    private function size($size)
    {
        return max(0, round($size / 1024, 2));
    }

    /**
     * Remove reference to non-existing file.
     *
     * The associated file doesn't seem to exist anymore,
     * let's also delete the reference in the Image Optimizer table.
     *
     * @param ProcessedFile $file
     */
    private function deleteOldRecord(ProcessedFile $file)
    {
        /** @var ProcessedFilesRepository $repository */
        $repository = $this->app->make(ProcessedFilesRepository::class);

        $repository->remove($file);
    }
}
