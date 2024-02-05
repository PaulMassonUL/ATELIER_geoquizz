<?php

return [

    'logger' => function(\Psr\Container\ContainerInterface $c) {
        $log = new \Monolog\Logger($c->get('auth.log.name'));
        $log->pushHandler(new \Monolog\Handler\StreamHandler($c->get('auth.log.file'), $c->get('auth.log.level')));
        return $log;
    },

    'JwtManager' => function(\Psr\Container\ContainerInterface $c) {
        $manager = new \geoquizz\auth\domain\manager\JwtManager($c->get('auth.token.secret'), $c->get('auth.token.expiration'));
        $manager->setIssuer($c->get('auth.token.issuer'));
        return $manager;
    },

    'AuthProvider' => function(\Psr\Container\ContainerInterface $c) {
        return new \geoquizz\auth\domain\provider\AuthProvider();
    },

    'AuthService' => function(\Psr\Container\ContainerInterface $c) {
        return new \geoquizz\auth\domain\service\AuthService($c->get('JwtManager'), $c->get('AuthProvider'), $c->get('logger'));
    },

];