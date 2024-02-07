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
        $app = \Slim\Factory\AppFactory::createFromContainer($container);
        $app->addRoutingMiddleware();
        $app->addBodyParsingMiddleware();
        return $app;
    },
    \geoquizz\quiz\console\CreateDatabaseCommand::class => function (ContainerInterface $container) {
        return new CreateDatabaseCommand($container->get('db'));
    },
    \geoquizz\quiz\console\PopulateDatabaseCommand::class => function (ContainerInterface $container) {
        return new \geoquizz\quiz\console\PopulateDatabaseCommand($container->get('db'));
    }
];