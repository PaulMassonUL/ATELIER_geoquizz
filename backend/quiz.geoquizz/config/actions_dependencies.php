<?php

use \pizzashop\shop\app\actions\AccederCommandeAction;
use pizzashop\shop\app\actions\CreerCommandeAction;
use \pizzashop\shop\app\actions\ValiderCommandeAction;
use Psr\Container\ContainerInterface;

return [

    ValiderCommandeAction::class => function (ContainerInterface $container) {
        return new ValiderCommandeAction($container->get('commande.service'));
    },

    AccederCommandeAction::class => function (ContainerInterface $container) {
        return new AccederCommandeAction($container->get('commande.service'));
    },

    CreerCommandeAction::class => function (ContainerInterface $container) {
        return new CreerCommandeAction($container->get('commande.service'));
    },

];