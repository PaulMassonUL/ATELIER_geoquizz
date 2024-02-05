<?php

namespace geoquizz\auth\app\actions;

use geoquizz\auth\domain\dto\CredentialsDTO;
use geoquizz\auth\domain\service\AuthServiceCredentialsException;
use geoquizz\auth\domain\service\AuthServiceInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class SignupAction extends Action
{
    private AuthServiceInterface $serviceAuth;

    public function __construct(AuthServiceInterface $serviceAuth)
    {
        $this->serviceAuth = $serviceAuth;
    }

    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        // Récupérez les données JSON du corps de la requête
        $data = $rq->getParsedBody();

        if (!isset($data['email']) || !isset($data['password']) || !isset($data['username'])) {
            throw new HttpBadRequestException($rq, 'Données invalides');
        }

        $email = htmlspecialchars($data['email']);
        $password = $data['password'];
        $username = htmlspecialchars($data['username']);

        try {
            $credentialsDTO = new CredentialsDTO($email, $password);
            $credentialsDTO->username = $username;
            $this->serviceAuth->signup($credentialsDTO);

            return $rs->withStatus(201)->withHeader('Content-Type', 'application/json; charset=utf-8');
        } catch (AuthServiceCredentialsException $e) {
            $rs->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $rs->withStatus(400)->withHeader('Content-Type', 'application/json; charset=utf-8');
        }
    }
}
