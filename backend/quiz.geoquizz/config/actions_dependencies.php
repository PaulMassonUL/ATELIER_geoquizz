<?php

use geoquizz\quiz\app\actions\CreerGameAction;
use Psr\Container\ContainerInterface;

return [

    CreerGameAction::class => function (ContainerInterface $container) {
        return new CreerGameAction($container->get('game.service'));
    },

];