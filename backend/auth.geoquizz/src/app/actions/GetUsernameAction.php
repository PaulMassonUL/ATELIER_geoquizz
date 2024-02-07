<?php

namespace geoquizz\auth\app\actions;

use Exception;
use geoquizz\auth\domain\service\AuthServiceInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class GetUsernameAction extends Action
{
    private AuthServiceInterface $serviceAuth;

    public function __construct(AuthServiceInterface $serviceAuth)
    {
        $this->serviceAuth = $serviceAuth;
    }

    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        try {
            $data = $rq->getParsedBody();
            if (!isset($data['id_user'])) {
                $rs->getBody()->write(json_encode(['error' => 'id_user manquant']));
                return $rs->withStatus(400);
            }

            $username = $this->serviceAuth->getUsername($data['id_user']);

            $donnees = [
                'username' => $username
            ];

            $rs->getBody()->write(json_encode($donnees));
            return $rs->withStatus(201)->withHeader('Content-Type', 'application/json;charset=utf-8');

        } catch (Exception $e) {
            $rs->getBody()->write(json_encode(['error' => 'Erreur interne' . $e->getMessage()]));
            return $rs->withStatus(500);
        }
    }
}
