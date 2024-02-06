<?php

return [

    'game.logger' => function (\Psr\Container\ContainerInterface $c) {
        $log = new \Monolog\Logger($c->get('log.game.name'));
        $log->pushHandler(new \Monolog\Handler\StreamHandler($c->get('log.game.file'), $c->get('log.game.level')));
        return $log;
    },

    'game.service' => function (\Psr\Container\ContainerInterface $c) {
        return new \geoquizz\quiz\domain\service\game\ServiceGame($c->get('game.logger'), $c->get('game.api.base_uri'));
    },

];