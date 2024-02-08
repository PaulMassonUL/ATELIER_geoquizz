<?php

namespace geoquizz\quiz\app\actions;

use geoquizz\quiz\domain\service\game\iGame;
use geoquizz\quiz\domain\service\game\ServiceGame;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PostStartGame extends Action
{
    private ServiceGame $serviceGame;

    public function __construct(iGame $serviceGame)
    {
        $this->serviceGame = $serviceGame;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        // Récupérez les données JSON du corps de la requête
        $user = $rq->getAttribute('user');

        if (!isset($args['id']))  {
            $rs->getBody()->write(json_encode(['error' => 'missing parameters in URL (id_game)']));
            return $rs->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        if(!isset($user['email'])) {
            $games = $this->serviceGame->startGameById($args['id']);
        }else {
            $games = $this->serviceGame->startGameById($args['id'], $user['email']);
        }

        $rs->getBody()->write(json_encode($games));
        return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}