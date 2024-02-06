<?php
declare(strict_types=1);

use geoquizz\quiz\domain\middleware\Jwt;

return function(\Slim\App $app):void {

    $JwtVerification = new Jwt($app->getContainer()->get('auth.api.base_uri'));

    $app->get('/games[/]', \geoquizz\quiz\app\actions\GetGamesAction::class);
    //->setName('series')->add($JwtVerification);

    $app->post('/game[/]', \geoquizz\quiz\app\actions\CreerGameAction::class);
        //->setName('creer_game')->add($JwtVerification);



    
};