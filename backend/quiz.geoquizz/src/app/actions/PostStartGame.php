<?php

namespace geoquizz\quiz\app\actions;

use geoquizz\quiz\domain\service\game\iGame;
use geoquizz\quiz\domain\service\game\ServiceGame;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpUnauthorizedException;

class PostStartGame extends Action
{
    private ServiceGame $serviceGame;

    private string $auth_uri;

    public function __construct(iGame $serviceGame, string $auth_uri)
    {
        $this->serviceGame = $serviceGame;
        $this->auth_uri = $auth_uri;
    }

    /**
     * @throws \Exception|\GuzzleHttp\Exception\GuzzleException
     */
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        if (!isset($args['id'])) {
            $rs->getBody()->write(json_encode(['error' => 'missing parameters in URL (id_game)']));
            return $rs->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        if ($rq->hasHeader('Authorization')) {
            $client = new Client([
                'base_uri' => $this->auth_uri,
                'timeout' => 30.0
            ]);

            $headers = [
                'Origin' => $_SERVER['HTTP_HOST'],
                'Authorization' => $rq->getHeader('Authorization'),
            ];

            try {
                $response = $client->request('GET', '/api/users/validate', [
                    'headers' => $headers
                ]);
            } catch (RequestException $e) {
                $response = $e->getResponse();
                $response_json = json_decode($response->getBody()->getContents(), true);
                throw new HttpUnauthorizedException($rq, $response_json['error']);
            }

            $id_user = json_decode($response->getBody()->getContents(), true)['email'];

            if (!isset($id_user)) {
                $games = $this->serviceGame->startGameById($args['id']);
            } else {
                $games = $this->serviceGame->startGameById($args['id'], $id_user);
            }
        } else {
            $games = $this->serviceGame->startGameById($args['id']);
        }

        $rs->getBody()->write(json_encode($games));
        return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);
    }
}