<?php

namespace Concrete\Package\ImageOptimizer\Controller\SinglePage\Dashboard\Files\ImageOptimizer\Settings;

use A3020\ImageOptimizer\RequestHandler\SaveSettings;
use A3020\ImageOptimizer\RequestHandler\Tinypng\ValidateSettings;
use A3020\ImageOptimizer\RequestHandler\ViewSettings;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Routing\Redirect;

final class Tinypng extends DashboardPageController
{
    public function view()
    {
        /** @var ViewSettings $handler */
        $handler = $this->app->make(ViewSettings::class);

        $this->set('tinyPngEnabled', (bool) $handler->config->get('image_optimizer::settings.tiny_png.enabled'));
        $this->set('tinyPngApiKey', $handler->config->get('image_optimizer::settings.tiny_png.api_key'));
        $this->set('tinyPngMaxOptimizationsPerMonth', $handler->config->get('image_optimizer::settings.tiny_png.max_optimizations_per_month'));
        $this->set('tinyPngNumberOfCompressions', $handler->getTinyPngNumberOfCompressions());
    }

    public function save()
    {
        /** @var ValidateSettings $validator */
        $validator = $this->app->make(ValidateSettings::class);
        $error = $validator->validate();

        if ($error) {
            $this->error = $error;

            return $this->view();
        }

        /** @var SaveSettings $handler */
        $handler = $this->app->make(SaveSettings::class);
        $handler->config->save('image_optimizer::settings.tiny_png.enabled', (bool) $this->post('tinyPngEnabled'));
        $handler->config->save('image_optimizer::settings.tiny_png.api_key', $this->post('tinyPngApiKey'));
        $handler->config->save('image_optimizer::settings.tiny_png.max_optimizations_per_month', $handler->getOrNull('tinyPngMaxOptimizationsPerMonth'));

        $this->flash('success', t('Your settings have been saved.'));

        return Redirect::to('/dashboard/files/image_optimizer/settings/tinypng');
    }
}
