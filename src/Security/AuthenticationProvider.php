<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Token;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class AuthenticationProvider
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * @var JWTConfiguration
     */
    private $configuration;

    public function __construct(EntityManager $entityManager, EncoderFactoryInterface $encoderFactory, JWTConfiguration $configuration)
    {
        $this->entityManager = $entityManager;
        $this->encoderFactory = $encoderFactory;
        $this->configuration = $configuration;
    }

    public function authenticateAndCreateJWT(array $loginData): Token
    {
        $user = $this->findEnabledUserByUsername($loginData['username']);

        if (!$user) {
            throw new BadCredentialsException();
        }

        $encoder = $this->encoderFactory->getEncoder(User::class);
        if ($encoder->isPasswordValid($user->getPassword(), $loginData['password'], null)) {
            $signer = $this->configuration->getSigner();
            $passPhrase = $this->configuration->getPassPhrase();

            $builder = (new Builder())
                ->setIssuedAt(time())
                ->setExpiration(time() + 3600 * 24)
                ->set('email', $user->getEmail())
                ->set('username', $user->getUsername())
                ->set('id', $user->getId())
                ->sign($signer, $passPhrase)
            ;

            return $builder->getToken();
        }
        throw new BadCredentialsException();
    }

    public function findEnabledUserByUsername(string $username): ?User
    {
        $repo = $this->entityManager->getRepository(User::class);

        return $repo->findOneBy(['username' => $username, 'active' => true]);
    }
}
