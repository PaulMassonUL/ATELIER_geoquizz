<?php

namespace geoquizz\quiz\domain\manager;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtManager implements JwtManagerInterface
{
    private string $secret;
    private string $alg;

    private string $issuer;

    public function __construct(string $secret)
    {
        $this->secret = $secret;
        $this->alg = "HS256";
    }

    public function setIssuer(string $issuer): void
    {
        $this->issuer = $issuer;
    }

    public function create(array $payload): string
    {
        return JWT::encode([
            "iss" => $this->issuer,
            "iat" => time(),
            "upr" => $payload
        ], $this->secret, $this->alg);
    }

    /**
     * @throws JwtManagerInvalidTokenException
     */
    public function validate(string $t): array
    {
        try {
            $jwt = JWT::decode($t, new Key($this->secret, $this->alg));
        } catch (\Exception) {
            throw new JwtManagerInvalidTokenException("Invalid token");
        }
        return (array)$jwt->upr;
    }
}
