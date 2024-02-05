<?php

namespace geoquizz\auth\app\actions;

use geoquizz\auth\domain\dto\TokenDTO;
use geoquizz\auth\domain\service\AuthServiceExpiredTokenException;
use geoquizz\auth\domain\service\AuthServiceInvalidTokenException;
use geoquizz\auth\domain\service\AuthServiceInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Routing\RouteContext;

class ValidateAction extends Action
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

        $routeParser = RouteContext::fromRequest($rq)->getRouteParser();

        try {
            $token = $rq->getHeader('Authorization')[0];
            $token = str_replace('Bearer ', '', $token);

            $userDTO = $this->serviceAuth->validate(new TokenDTO($token));

            $rs->getBody()->write($userDTO->toJson());

            return $rs->withStatus(201)->withHeader('Content-Type', 'application/json;charset=utf-8');
        } catch (AuthServiceExpiredTokenException $e) {
            $rs->getBody()->write(json_encode(['error' => 'Expired', 'message' => $e->getMessage()]));
            return $rs->withStatus(401)->withHeader('Location', $routeParser->urlFor('refresh'))->withHeader('Content-Type', 'application/json;charset=utf-8');
        } catch (AuthServiceInvalidTokenException $e) {
            $rs->getBody()->write(json_encode(['error' => 'Invalid', 'message' => $e->getMessage()]));
            return $rs->withStatus(401)->withHeader('Location', $routeParser->urlFor('signin'))->withHeader('Content-Type', 'application/json;charset=utf-8');
        }
    }
}
