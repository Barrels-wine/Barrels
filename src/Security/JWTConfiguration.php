<?php

declare(strict_types=1);

namespace App\Security;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class JWTConfiguration
{
    /**
     * @var string
     */
    private $passPhrase;

    public function __construct(string $passPhrase = null)
    {
        $this->passPhrase = $passPhrase;
    }

    public function getSigner(): Signer
    {
        return new Sha256();
    }

    public function getPassPhrase() : string
    {
        return $this->passPhrase;
    }

    /**
     * Read a JWT string and get the full validated Token from it.
     * Return false if the token is invalid or expired.
     *
     * @param string $jwt
     *
     * @return bool|\Lcobucci\JWT\Token
     */
    public function decode(string $jwt)
    {
        $token = (new Parser())->parse($jwt);

        if ($token->verify($this->getSigner(), $this->passPhrase)) {
            // Check expiration
            if ($token->hasClaim('exp')) {
                if ($token->getClaim('exp') < time()) {
                    return false;
                }
            }

            return $token;
        } else {
            return false;
        }
    }
}
