<?php

return [

    'game.logger' => function (\Psr\Container\ContainerInterface $c) {
        $log = new \Monolog\Logger($c->get('log.game.name'));
        $log->pushHandler(new \Monolog\Handler\StreamHandler($c->get('log.game.file'), $c->get('log.game.level')));
        return $log;
    },

    'game.service' => function (\Psr\Container\ContainerInterface $c) {
        return new \geoquizz\quiz\domain\service\game\ServiceGame($c->get('game.logger'), $c->get('serie.service'));
    },

    'serie.service' => function (\Psr\Container\ContainerInterface $c) {
        return new \geoquizz\quiz\domain\service\serie\ServiceSerie($c->get('serie.api.base_uri'));
    },

];