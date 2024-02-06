<?php

use geoquizz\quiz\app\actions\CreerGameAction;
use geoquizz\quiz\console\CreateDatabaseCommand;
use Psr\Container\ContainerInterface;

return [

    CreerGameAction::class => function (ContainerInterface $container) {
        return new CreerGameAction($container->get('game.service'));
    },

    \Slim\App::class => function (ContainerInterface $container)
    {
        $container->get('db');
        $app = \Slim\Factory\AppFactory::createFromContainer($container);
        $app->addRoutingMiddleware();
        $app->addBodyParsingMiddleware();
        return $app;
    },
    \geoquizz\quiz\console\CreateDatabaseCommand::class => function (ContainerInterface $container) {
        return new CreateDatabaseCommand($container->get(\Slim\App::class));
    }
];