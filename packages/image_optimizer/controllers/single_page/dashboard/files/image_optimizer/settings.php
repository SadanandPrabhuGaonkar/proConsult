<?php

namespace Concrete\Package\ImageOptimizer\Controller\SinglePage\Dashboard\Files\ImageOptimizer;

use A3020\ImageOptimizer\RequestHandler\SaveSettings;
use A3020\ImageOptimizer\RequestHandler\ViewSettings;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Routing\Redirect;

final class Settings extends DashboardPageController
{
    public function view()
    {
        /** @var ViewSettings $handler */
        $handler = $this->app->make(ViewSettings::class);

        $this->set('thumbnailImageDirectory', DIR_FILES_UPLOADED_STANDARD.REL_DIR_FILES_THUMBNAILS);
        $this->set('cacheDirectory', $handler->config->get('concrete.cache.directory'));
        $this->set('enableLog', (bool) $handler->config->get('image_optimizer::settings.enable_log'));
        $this->set('includeFilemanagerImages', (bool) $handler->config->get('image_optimizer::settings.include_filemanager_images'));
        $this->set('includeThumbnailImages', (bool) $handler->config->get('image_optimizer::settings.include_thumbnail_images', true));
        $this->set('includeCachedImages', (bool) $handler->config->get('image_optimizer::settings.include_cached_images'));
        $this->set('batchSize', max((int) $handler->config->get('image_optimizer::settings.batch_size'), 1));
        $this->set('maxImageSize', $handler->config->get('image_optimizer::settings.max_image_size'));
    }

    public function save()
    {
        if (!$this->token->validate('a3020.image_optimizer.settings')) {
            $this->error = $this->token->getErrorMessage();

            return $this->view();
        }

        /** @var SaveSettings $handler */
        $handler = $this->app->make(SaveSettings::class);
        $handler->config->save('image_optimizer::settings.enable_log', (bool) $this->post('enableLog'));
        $handler->config->save('image_optimizer::settings.include_filemanager_images', (bool) $this->post('includeFilemanagerImages'));
        $handler->config->save('image_optimizer::settings.include_thumbnail_images', (bool) $this->post('includeThumbnailImages'));
        $handler->config->save('image_optimizer::settings.include_cached_images', (bool) $this->post('includeCachedImages'));
        $handler->config->save('image_optimizer::settings.batch_size', (int) $this->post('batchSize'));
        $handler->config->save('image_optimizer::settings.max_image_size', $handler->getOrNull('maxImageSize'));

        $this->flash('success', t('Your settings have been saved.'));

        return Redirect::to('/dashboard/files/image_optimizer/settings');
    }
}
