<?php

namespace A3020\ImageOptimizer\RequestHandler\Tinypng;

use A3020\ImageOptimizer\TinyPng\ConnectionChecker;
use Concrete\Core\Http\Request;
use Concrete\Core\Validation\CSRF\Token;
use Exception;

class ValidateSettings
{
    /**
     * @var Token
     */
    private $token;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var ConnectionChecker
     */
    private $connectionChecker;

    public function __construct(Token $token, ConnectionChecker $connectionChecker, Request $request)
    {
        $this->request = $request;
        $this->token = $token;
        $this->connectionChecker = $connectionChecker;
    }

    /**
     * @return string|null
     */
    public function validate()
    {
        if (!$this->token->validate('a3020.image_optimizer.settings')) {
            return $this->token->getErrorMessage();
        }

        if ($this->request->request->has('tinyPngEnabled')) {
            try {
                $this->connectionChecker->check(
                    $this->request->request->get('tinyPngApiKey')
                );
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
    }
}
