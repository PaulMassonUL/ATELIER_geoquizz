<?php

namespace pizzashop\shop\domain\middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;

class Cors
{
    public function __invoke(ServerRequestInterface $rq, RequestHandlerInterface $next): ResponseInterface
    {

        $response = $next->handle($rq);
        return $response->withHeader('Access-Control-Allow-Origin', $rq->getHeaderLine('Origin'))
            ->withHeader('Access-Control-Allow-Headers', $rq->getHeaderLine('Access-Control-Request-Headers'))
            ->withHeader('Access-Control-Allow-Methods', $rq->getHeaderLine('Access-Control-Request-Method'))
            ->withHeader('Access-Control-Max-Age', 3600)
            ->withHeader('Access-Control-Allow-Credentials', 'true');
    }
}