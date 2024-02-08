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

        $user = $rq->getAttribute('user');

        if (!isset($data['id_serie']) || !isset($user['email']) || !isset($data['level']) || !isset($data['isPublic']))  {
            $rs->getBody()->write(json_encode(['error' => 'missing parameters (id_serie, id_user, level, isPublic)']));
            return $rs->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            $gameDTO = new GameDTO($data['id_serie'], $user['email'], $data['level'], $data['isPublic']);
            $resultDTO = $this->serviceGame->creerGame($gameDTO);

            $rs->getBody()->write(json_encode($resultDTO->id));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(201);

        } catch (Exception $e) {
            $rs->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $rs->withStatus(500)->withHeader('Content-Type', 'application/json');
        }

    }
}