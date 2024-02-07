<?php
declare(strict_types=1);

use geoquizz\quiz\app\actions\CreerGameAction;
use geoquizz\quiz\app\actions\GetGameByIdAction;
use geoquizz\quiz\app\actions\GetGamesPublicAction;
use geoquizz\quiz\domain\middleware\Jwt;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function(App $app):void {

    $JwtVerification = new Jwt($app->getContainer()->get('auth.api.base_uri'));

    $app->options('/{routes:.+}', function ($request, $response) {
            return $response;
        });

    $app->get('/games[/]', GetGamesPublicAction::class);
    //->setName('series')->add($JwtVerification);

    $app->post('/game[/]', CreerGameAction::class);
        //->setName('creer_game')->add($JwtVerification);

    $app->get('/games/{id}[/]', GetGameByIdAction::class);
        //->setName('game')->add($JwtVerification);

    $app->get("/", function (Request $rq, Response $rs, array $args) {
        echo "bonjour";
        return $rs->withStatus(200);
    });
    
};