<?php
declare(strict_types=1);

use geoquizz\quiz\app\actions\CreerGameAction;
use geoquizz\quiz\app\actions\GetGameAction;
use geoquizz\quiz\app\actions\GetGamesPublicAction;
use geoquizz\quiz\app\actions\PatchGamesScoreAction;
use geoquizz\quiz\domain\middleware\Jwt;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

return function(App $app):void {

    $JwtVerification = new Jwt($app->getContainer()->get('auth.api.base_uri'));

    $app->options('/{routes:.+}', function ($request, $response) {
            return $response;
        });

    $app->get('/games[/]', GetGamesPublicAction::class)
        ->setName('games');

    $app->post('/games/new[/]', CreerGameAction::class)
        ->setName('creer_game')->add($JwtVerification);

    $app->get('/games/{id}[/]', GetGameAction::class)
        ->setName('game');

    $app->patch('/games/{id}[/]', PatchGamesScoreAction::class)
        ->setName('game_update_score')->add($JwtVerification);
};