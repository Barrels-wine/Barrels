<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    /** @var JWTConfiguration */
    private $configuration;

    /** @var RouterInterface */
    private $router;

    public function __construct(JWTConfiguration $configuration, RouterInterface $router)
    {
        $this->configuration = $configuration;
        $this->router = $router;
    }

    public function getCredentials(Request $request)
    {
        if (!$request->headers->has('Authorization')) {
            return false;
        }

        $headerParts = explode(' ', $request->headers->get('Authorization'));

        if (!(count($headerParts) === 2 && $headerParts[0] === 'Bearer')) {
            return false;
        }

        return $headerParts[1];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (!$credentials) {
            throw new CustomUserMessageAuthenticationException('Missing Token');
        }

        try {
            $token = $this->configuration->decode($credentials);
        } catch (\Exception $e) {
            throw new CustomUserMessageAuthenticationException('Invalid Token');
        }

        if ($token === null) {
            throw new CustomUserMessageAuthenticationException('Invalid Token');
        }

        $user = new User();
        $user->setId($token->getClaim('id'));
        $user->setEmail($token->getClaim('email'));
        $user->setUsername($token->getClaim('username'));

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $route = $this->router->match($request->getPathInfo())['_route'];
        if ($request->getMethod() === 'POST' && $route === 'login') {
            return;
        }

        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
    }

    public function supportsRememberMe()
    {
        return false;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => strtr($authException->getMessageKey(), $authException->getMessageData()),
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supports(Request $request)
    {
        return true;
    }
}
