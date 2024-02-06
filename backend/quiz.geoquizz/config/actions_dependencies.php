<?php

use geoquizz\quiz\app\actions\CreerGameAction;
use geoquizz\quiz\app\actions\GetGamesAction;
use Psr\Container\ContainerInterface;

return [

    CreerGameAction::class => function (ContainerInterface $container) {
        return new CreerGameAction($container->get('game.service'));
    },

    GetGamesAction::class => function (ContainerInterface $container) {
        return new GetGamesAction($container->get('game.service'));
    },

];