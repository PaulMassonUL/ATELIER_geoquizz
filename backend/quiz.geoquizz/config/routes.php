<?php
declare(strict_types=1);

use geoquizz\quiz\domain\middleware\Jwt;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function(\Slim\App $app):void {

    $JwtVerification = new Jwt($app->getContainer()->get('auth.api.base_uri'));

    $app->options('/{routes:.+}', function ($request, $response) {
            return $response;
        });


    $app->get('/games[/]', \geoquizz\quiz\app\actions\GetGamesPublicAction::class);
    //->setName('series')->add($JwtVerification);

    $app->post('/game[/]', \geoquizz\quiz\app\actions\CreerGameAction::class);
        //->setName('creer_game')->add($JwtVerification);

    $app->get("/", function (Request $rq, Response $rs, array $args) {
        echo "bonjour";
        return $rs->withStatus(200);
    });
    
};