<?php

namespace geoquizz\auth\app\actions;

use geoquizz\auth\domain\dto\CredentialsDTO;
use geoquizz\auth\domain\service\AuthServiceCredentialsException;
use geoquizz\auth\domain\service\AuthServiceInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpUnauthorizedException;

class SigninAction extends Action
{
    private AuthServiceInterface $serviceAuth;

    public function __construct(AuthServiceInterface $serviceAuth)
    {
        $this->serviceAuth = $serviceAuth;
    }

    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        if (!$rq->hasHeader('Authorization')) {
            throw new HttpUnauthorizedException($rq, "missing Authorization Header");
        }

        try {
            $credentials = $rq->getHeader('Authorization')[0];
            $credentials = str_replace('Basic ', '', $credentials);
            $credentials = base64_decode($credentials);
            $credentials = explode(':', $credentials);

            if (count($credentials) !== 2) {
                return $rs->withStatus(400);
            }

            $tokenDTO = $this->serviceAuth->signin(new CredentialsDTO($credentials[0], $credentials[1]));

            $rs->getBody()->write($tokenDTO->toJson());

            return $rs->withStatus(201)->withHeader('Content-Type', 'application/json;charset=utf-8');
        } catch (AuthServiceCredentialsException $e) {
            $rs->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $rs->withStatus(401)->withHeader('Content-Type', 'application/json;charset=utf-8');
        }
    }
}
