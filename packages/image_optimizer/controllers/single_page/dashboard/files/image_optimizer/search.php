<?php

namespace Concrete\Package\ImageOptimizer\Controller\SinglePage\Dashboard\Files\ImageOptimizer;

use A3020\ImageOptimizer\Repository\ProcessedFilesRepository;
use A3020\ImageOptimizer\Resetter;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Routing\Redirect;

final class Search extends DashboardPageController
{
    /** @var Repository $config */
    protected $config;

    public function on_before_render()
    {
        parent::on_before_render();

        $al = AssetList::getInstance();

        $al->register('javascript', 'image_optimizer/datatables', 'js/datatables.min.js', [], 'image_optimizer');
        $this->requireAsset('javascript', 'image_optimizer/datatables');

        $al->register('css', 'image_optimizer/datatables', 'css/datatables.css', [], 'image_optimizer');
        $al->register('css', 'image_optimizer/style', 'css/style.css', [], 'image_optimizer');
        $this->requireAsset('css', 'image_optimizer/datatables');
        $this->requireAsset('css', 'image_optimizer/style');
    }

    public function view()
    {
        /** @see \A3020\ImageOptimizer\Ajax\Files */

        /** @var ProcessedFilesRepository $repository */
        $repository = $this->app->make(ProcessedFilesRepository::class);

        $this->set('numberHelper', $this->app->make('helper/number'));
        $this->set('totalFiles', $repository->totalFiles());
        $this->set('totalGained', $repository->totalFileSize());
    }

    public function resetAll()
    {
        if (!$this->token->validate('a3020.image_optimizer.reset_all')) {
            $this->flash('error', $this->token->getErrorMessage());

            return Redirect::to('/dashboard/files/image_optimizer/search');
        }

        /** @var Resetter $resetter */
        $resetter = $this->app->make(Resetter::class);
        $resetter->resetAll();

        $this->flash('success', t('All images have been reset.'));

        return Redirect::to('/dashboard/files/image_optimizer/search');
    }
}
