<?php

namespace geoquizz\quiz\domain\middleware;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;

class Jwt
{
    private string $auth_uri;

    public function __construct(string $auth_uri)
    {
        $this->auth_uri = $auth_uri;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $next)
    {

        if (!$request->hasHeader('Authorization'))
            throw new HttpUnauthorizedException($request, 'No authorization token provided');

        $client = new Client([
            'base_uri' => $this->auth_uri,
            'timeout' => 30.0
        ]);

        $headers = [
            'Origin' => $_SERVER['HTTP_HOST'],
            'Authorization' => $request->getHeader('Authorization'),
        ];

        try {
            $response = $client->request('GET', '/api/users/validate', [
                'headers' => $headers
            ]);
            //enregistrer le user dans la requête
            // la reponse retourne un UserDTO
            $request = $request->withAttribute('user', json_decode($response->getBody()->getContents(), true));



            return $next->handle($request);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $response_json = json_decode($response->getBody()->getContents(), true);

            if ($response_json['error'] === 'Invalid') {
                // Le serveur répond avec une erreur Invalid et une url de redirection vers l'authentification

                if (!$response->hasHeader('Location'))
                    throw new HttpUnauthorizedException($request, 'No redirection url provided');

                // Redirection
                var_dump($request->withHeader('Location', $response->getHeader('Location')), 'Invalid');
                return $next->handle($request->withHeader('Location', $response->getHeader('Location')));
            } else if ($response_json['error'] === 'Expired') {
                // Lorsque l'access token n'est plus valide, le client utilise le refresh token pour obtenir un nouvel access token

                if (!$request->hasHeader('Location'))
                    throw new HttpUnauthorizedException($request, 'No redirection url provided');

                try {
                    $response = $client->request('POST', '/api/users/refresh', [
                        'headers' => $headers
                    ]);

                    $request->getBody()->write($response->getBody()->getContents());
                    return $next->handle($request);
                } catch (RequestException $e) {
                    $response = $e->getResponse();
                    $response_json = json_decode($response->getBody()->getContents(), true);

                    if ($response_json['error'] === 'Invalid') {
                        // Le serveur répond avec une erreur Invalid et une url de redirection vers l'authentification

                        if (!$response->hasHeader('Location'))
                            throw new HttpUnauthorizedException($request, 'No redirection url provided');

                        // Redirection
                        var_dump($request->withHeader('Location', $response->getHeader('Location')), 'Invalid');
                        return $next->handle($request->withHeader('Location', $response->getHeader('Location')));
                    } else {
                        throw new HttpUnauthorizedException($request, 'No redirection url provided');
                    }
                }
            }
        }

        return $next->handle($request);
    }
}