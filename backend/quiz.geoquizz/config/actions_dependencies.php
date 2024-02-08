<?php

use geoquizz\quiz\app\actions\CreerGameAction;
use geoquizz\quiz\app\actions\GetGameAction;
use geoquizz\quiz\console\CreateDatabaseCommand;
use geoquizz\quiz\app\actions\GetGamesPublicAction;
use geoquizz\quiz\console\PopulateDatabaseCommand;
use Psr\Container\ContainerInterface;
use Slim\App;
use Slim\Factory\AppFactory;

return [

    CreerGameAction::class => function (ContainerInterface $container) {
        return new CreerGameAction($container->get('game.service'));
    },

    App::class => function (ContainerInterface $container)
    {
        $app = AppFactory::createFromContainer($container);
        $app->addRoutingMiddleware();
        $app->addBodyParsingMiddleware();
        return $app;
    },
    CreateDatabaseCommand::class => function (ContainerInterface $container) {
        return new CreateDatabaseCommand($container->get('db'));
    },
    PopulateDatabaseCommand::class => function (ContainerInterface $container) {
        return new PopulateDatabaseCommand($container->get('db'));
    },

    GetGamesPublicAction::class => function (ContainerInterface $container) {
        return new GetGamesPublicAction($container->get('game.service'));
    },

    GetGameAction::class => function (ContainerInterface $container) {
        return new GetGameAction($container->get('game.service'), $container->get('auth.api.base_uri'));
    }

];