<?php

use DI\ContainerBuilder;
use pizzashop\shop\domain\middleware\Cors;

$builder = new ContainerBuilder();

$builder->addDefinitions(__DIR__ . '/settings.php');
$builder->addDefinitions(__DIR__ . '/services_dependencies.php');
$builder->addDefinitions(__DIR__ . '/actions_dependencies.php');

$c=$builder->build();

$app = \Slim\Factory\AppFactory::createFromContainer($c);

$app->add(new Cors());

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, false, false);

$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->forceContentType('application/json');


$capsule = new \Illuminate\Database\Capsule\Manager();

$capsule->addConnection(parse_ini_file("commande.db.ini"), 'commande');
$capsule->setAsGlobal();
$capsule->bootEloquent();

return $app;
