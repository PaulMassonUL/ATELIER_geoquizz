<?php

use Psr\Container\ContainerInterface;

return [

    'game.logger' => function (ContainerInterface $c) {
        $log = new \Monolog\Logger($c->get('log.game.name'));
        $log->pushHandler(new \Monolog\Handler\StreamHandler($c->get('log.game.file'), $c->get('log.game.level')));
        return $log;
    },

    'game.service' => function (ContainerInterface $c) {
        return new \geoquizz\quiz\domain\service\game\ServiceGame($c->get('game.logger'), $c->get('serie.service'));
    },

    'serie.service' => function (\Psr\Container\ContainerInterface $c) {
        return new \geoquizz\quiz\domain\service\serie\ServiceSerie($c->get('serie.api.base_uri'));
    },

    'db' => function (ContainerInterface $container) {
        $capsule = new \Illuminate\Database\Capsule\Manager();

        $capsule->addConnection(parse_ini_file("quiz.db.ini"), 'quiz');
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

];