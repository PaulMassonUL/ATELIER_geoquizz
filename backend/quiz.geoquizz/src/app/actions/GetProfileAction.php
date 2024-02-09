<?php

namespace geoquizz\quiz\app\actions;

use geoquizz\quiz\domain\service\played\iPlayed;
use geoquizz\quiz\domain\service\played\ServicePlayed;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
class GetProfileAction extends Action
{
    private ServicePlayed $servicePlayed;

    public function __construct(iPlayed $servicePlayed)
    {
        $this->servicePlayed = $servicePlayed;
    }

    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $user = $rq->getAttribute('user');

        $games_played = $this->servicePlayed->getGamesPlayedByUser($user['email']);

        $rs->getBody()->write(json_encode([
            'user' => $user,
            'games_played' => $games_played
        ]));
        return $rs->withHeader('Content-Type', 'application/json');
    }
}