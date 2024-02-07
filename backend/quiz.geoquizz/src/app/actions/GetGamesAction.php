<?php

namespace geoquizz\quiz\app\actions;

use geoquizz\quiz\domain\service\game\iGame;
use geoquizz\quiz\domain\service\game\ServiceGame;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GetGamesAction extends Action
{
    private ServiceGame $serviceGame;

    public function __construct(iGame $serviceGame)
    {
        $this->serviceGame = $serviceGame;
    }
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $games = $this->serviceGame->getGames();
        $rs->getBody()->write(json_encode($games));
        return $rs->withHeader('Content-Type', 'application/json;charset=utf-8')->withStatus(200);
    }
}