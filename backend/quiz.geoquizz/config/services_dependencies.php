<?php

return [

  'commande.logger' => function(\Psr\Container\ContainerInterface $c) {
      $log = new \Monolog\Logger($c->get('log.commande.name'));
      $log->pushHandler(new \Monolog\Handler\StreamHandler($c->get('log.commande.file'), $c->get('log.commande.level')));
      return $log;
  },

  'commande.service' => function(\Psr\Container\ContainerInterface $c) {
      return new \pizzashop\shop\domain\service\commande\ServiceCommande($c->get('commande.logger'), $c->get('catalog.api.base_uri'));
  },

];