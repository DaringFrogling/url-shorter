<?php

namespace App\EventListener;

use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class ApiContentTypeEventListener
{
    /**
     * Validates request content type.
     *
     * @param RequestEvent $event
     *
     * @throws UnsupportedMediaTypeHttpException
     */
    public function __invoke(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if ($request->getContentType() !== 'json') {
            throw new UnsupportedMediaTypeHttpException();
        }
        
        if ($this->isJsonRequest($request)) {
            $this->transformJsonBody($request);
        }
    }

    private function isJsonRequest(Request $request): bool
    {
        return $request->getContent()
            && (
                $request->isMethod(Request::METHOD_PUT)
                || $request->isMethod(Request::METHOD_POST)
                || $request->isMethod(Request::METHOD_PATCH)
            );
    }


    private function transformJsonBody(Request $request): void
    {
        try {
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

            if (is_array($data)) {
                $request->request->replace($data);
            }
        } catch (\JsonException $e) {
            throw new RuntimeException('Unable to decode request json');
        }
    }
}