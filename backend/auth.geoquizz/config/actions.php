<?php

use geoquizz\auth\app\actions\RefreshAction;
use geoquizz\auth\app\actions\SigninAction;
use geoquizz\auth\app\actions\SignupAction;
use geoquizz\auth\app\actions\ValidateAction;
use Psr\Container\ContainerInterface;

return [

    SigninAction::class => function (ContainerInterface $container) {
        return new SigninAction($container->get('AuthService'));
    },

    SignupAction::class => function (ContainerInterface $container) {
        return new SignupAction($container->get('AuthService'));
    },

    ValidateAction::class => function (ContainerInterface $container) {
        return new ValidateAction($container->get('AuthService'));
    },

    RefreshAction::class => function (ContainerInterface $container) {
        return new RefreshAction($container->get('AuthService'));
    },

];