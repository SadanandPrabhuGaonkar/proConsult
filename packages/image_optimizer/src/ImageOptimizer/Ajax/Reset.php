<?php

namespace A3020\ImageOptimizer\Ajax;

use A3020\ImageOptimizer\Resetter;
use Concrete\Core\Http\Response;
use Concrete\Core\Http\ResponseFactory;

class Reset extends BaseController
{
    public function view()
    {
        $id = $this->post('id');
        $isOriginal = $this->post('is_original');

        if (empty($id) || $isOriginal === null) {
            return $this->app->make(ResponseFactory::class)->json([
                'error' => t('ID / Type missing'),
            ], Response::HTTP_BAD_REQUEST);
        }

        /** @var Resetter $resetter */
        $resetter = $this->app->make(Resetter::class);

        return $this->app->make(ResponseFactory::class)->json([
            'success' => $resetter->reset($id),
        ]);
    }
}
