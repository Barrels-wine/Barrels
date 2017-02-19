<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var JWTConfiguration
     */
    private $configuration;

    public function __construct(JWTConfiguration $configuration)
    {
        $this->configuration = $configuration;
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
            throw new AuthenticationException('Missing Token');
        }

        try {
            $token = $this->configuration->decode($credentials);
        } catch (\Exception $e) {
            throw new AuthenticationException('Invalid Token');
        }

        if ($token === false) {
            throw new AuthenticationException('Invalid Token');
        }

        $user = new User(
            $token->getClaim('id'),
            $token->getClaim('email'),
            $token->getClaim('username')
        );

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
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
        throw $authException;
    }

    public function supports(Request $request)
    {
        return true;
    }

}
