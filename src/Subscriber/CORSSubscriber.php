<?php

declare(strict_types=1);

namespace App\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CORSSubscriber implements EventSubscriberInterface
{
    /** @var string $corsDomain */
    private $corsDomain;

    public function __construct($corsDomain)
    {
        $this->corsDomain = $corsDomain;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['handlePreFlightRequest', 1024],
            ],
            KernelEvents::RESPONSE => [
                ['handleResponse', 1024],
            ],
        ];
    }

    public function handlePreFlightRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!$event->isMasterRequest()) {
            return;
        }

        if (!$request->headers->has('Origin')) {
            return;
        }

        if ($request->getMethod() !== Request::METHOD_OPTIONS) {
            return;
        }

        $event->setResponse($this->addHeaders($request, new Response()));
        $event->stopPropagation();
    }

    public function handleResponse(FilterResponseEvent $event)
    {
        $response = $this->addHeaders($event->getRequest(), $event->getResponse());
        $event->setResponse($response);
    }

    public function addHeaders(Request $request, Response $response)
    {
        $origin = $request->headers->get('Origin');

        if (null === $origin || !preg_match('{' . $this->corsDomain . '}i', $origin)) {
            return $response;
        }

        $response->headers->add([
            'Access-Control-Allow-Methods' => 'GET,POST,PUT,PATCH,DELETE',
            'Access-Control-Allow-Origin' => $origin,
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Headers' => $request->headers->get('Access-Control-Request-Headers', '*'),
        ]);

        return $response;
    }
}
