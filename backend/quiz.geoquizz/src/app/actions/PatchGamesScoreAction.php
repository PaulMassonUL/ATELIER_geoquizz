<?php

namespace geoquizz\quiz\app\actions;

use geoquizz\quiz\domain\service\game\iGame;
use geoquizz\quiz\domain\service\game\ServiceGame;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PatchGamesScoreAction extends Action
{
    private ServiceGame $serviceGame;

    public function __construct(iGame $serviceGame)
    {
        $this->serviceGame = $serviceGame;
    }
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        if (!isset($args['id'])) {
            $rs->getBody()->write(json_encode(['error' => 'missing parameters in URL (id_game)']));
            return $rs->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        if (!isset($rq->getParsedBody()['id_played']) || !isset($rq->getParsedBody()['score'])) {
            $rs->getBody()->write(json_encode(['error' => 'missing parameters in body (id_played, score)']));
            return $rs->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $data = $rq->getParsedBody();
        $id_played = $data['id_played'];
        $score = $data['score'];

        $this->serviceGame->updateScore($id_played, $score);
        $rs->getBody()->write(json_encode(['message' => 'Score updated']));
        return $rs->withStatus(200)->withHeader('Content-Type', 'application/json');
    }
}