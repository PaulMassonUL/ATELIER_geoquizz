<?php

use geoquizz\quiz\domain\service\game\ServiceGame;
use geoquizz\quiz\domain\service\serie\ServiceSerie;
use Illuminate\Database\Capsule\Manager;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

return [

    'game.logger' => function (ContainerInterface $c) {
        $log = new Logger($c->get('log.game.name'));
        $log->pushHandler(new StreamHandler($c->get('log.game.file'), $c->get('log.game.level')));
        return $log;
    },

    'game.service' => function (ContainerInterface $c) {
        return new ServiceGame($c->get('game.logger'), $c->get('serie.service'), $c->get('auth.api.base_uri'), $c->get('serie.api.base_uri'));
    },

    'serie.service' => function (ContainerInterface $c) {
        return new ServiceSerie($c->get('serie.api.base_uri'));
    },

    'db' => function () {
        $capsule = new Manager();
        $capsule->addConnection(parse_ini_file("quiz.db.ini"));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        return $capsule;
    }

];