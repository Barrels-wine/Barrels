<?php

declare(strict_types=1);

namespace App\Subscriber;

use App\Exception\ValidationException;
use Negotiation\Negotiator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    private $debugMode;

    public function __construct($debug)
    {
        $this->debugMode = $debug;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['createJSONResponse', 1024],
            ],
        ];
    }

    public function createJSONResponse(GetResponseForExceptionEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $negotiator = new Negotiator();
        $acceptHeader = $event->getRequest()->headers->get('Accept', 'text/html');
        $priorities = ['text/html', 'application/json'];
        $mediaType = $negotiator->getBest($acceptHeader, $priorities);

        // Let Symfony handle the exception if the Accept-Header is not defined or different than 'application/json'
        if ($mediaType === null || $mediaType->getType() !== 'application/json') {
            return;
        }

        $exception = $event->getException();
        $content = [
            'message' => 'An internal error happens',
            'validation' => false,
        ];

        if ($exception instanceof AuthenticationCredentialsNotFoundException) {
            $exception = new UnauthorizedHttpException('None', '', $exception);
        }

        if ($exception instanceof AccessDeniedException) {
            $exception = new AccessDeniedHttpException('', $exception);
        }

        if ($exception instanceof HttpExceptionInterface) {
            $content['message'] = Response::$statusTexts[$exception->getStatusCode()];

            if (!empty($exception->getMessage())) {
                $content['message'] = $exception->getMessage();
            }
        }

        if ($exception instanceof \ErrorException) {
            $content['message'] = 'Internal Server Error.';
        }

        if ($this->debugMode) {
            $content = [
                'class' => get_class($exception),
                'message' => $exception->getMessage(),
                'validation' => false,
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
                'code' => $exception->getCode(),
                'trace' => $exception->getTrace(),
                'previous' => [],
            ];

            $previousException = $exception->getPrevious();

            while ($previousException !== null) {
                $content['previous'][] = [
                    'class' => get_class($previousException),
                    'message' => $previousException->getMessage(),
                    'line' => $previousException->getLine(),
                    'file' => $previousException->getFile(),
                    'code' => $previousException->getCode(),
                    'trace' => $previousException->getTrace(),
                ];

                $previousException = $previousException->getPrevious();
            }
        }

        if ($exception instanceof ValidationException) {
            $content['validation'] = true;
            $content['violations'] = $exception->getViolations();
        }

        $response = new JsonResponse($content, Response::HTTP_INTERNAL_SERVER_ERROR);

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->add($exception->getHeaders());
        }

        $event->setResponse($response);
    }
}
