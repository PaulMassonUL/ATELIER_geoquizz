<?php

namespace geoquizz\quiz\app\actions;

use Exception;
use geoquizz\quiz\domain\dto\GameDTO;
use geoquizz\quiz\domain\service\game\iGame;
use geoquizz\quiz\domain\service\game\ServiceGame;
use geoquizz\quiz\domain\service\game\ServiceGameInvalidDataException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreerGameAction extends Action
{
    private ServiceGame $serviceGame;

    public function __construct(iGame $serviceGame)
    {
        $this->serviceGame = $serviceGame;
    }

    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        // Récupérez les données JSON du corps de la requête
        $data = $rq->getParsedBody();

        if (!isset($data['id_serie'])) {
            $rs->getBody()->write(json_encode(['error' => 'id_serie manquant']));
            return $rs->withStatus(400);
        }

        try {
            $gameDTO = new GameDTO($data['id_serie']);
            $game = $this->serviceGame->creerGame($gameDTO);

            $donnees = [
                'id' => $game->id,
                'token' => $game->token,
                'id_serie' => $game->id_serie,
                'sequence' => $game->sequence,
                'created_at' => $game->created_at,
                'updated_at' => $game->updated_at
            ];

            $rs->getBody()->write(json_encode($donnees));
            return $rs->withHeader('Content-Type', 'application/json;charset=utf-8')->withHeader('Location', '/game/' . $game->id)->withStatus(201);

        } catch (ServiceGameInvalidDataException $e) {
            $rs->getBody()->write(json_encode(['error' => 'id_serie invalide']));
            return $rs->withStatus(400);
        }

    }
}