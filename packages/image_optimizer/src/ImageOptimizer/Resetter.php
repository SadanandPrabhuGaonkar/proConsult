<?php

namespace A3020\ImageOptimizer;

use A3020\ImageOptimizer\Repository\ProcessedFilesRepository;

class Resetter
{
    /**
     * @var ProcessedFilesRepository
     */
    private $originalsRepository;

    public function __construct(ProcessedFilesRepository $originalsRepository)
    {
        $this->originalsRepository = $originalsRepository;
    }

    public function resetAll()
    {
        $this->originalsRepository->removeAll();
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function reset($id)
    {
        return $this->originalsRepository->removeOne($id);
    }
}
