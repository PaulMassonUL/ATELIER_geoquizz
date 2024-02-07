<?php

use DI\ContainerBuilder;
use geoquizz\quiz\domain\middleware\Cors;

$builder = new ContainerBuilder();

$builder->addDefinitions(__DIR__ . '/settings.php');
$builder->addDefinitions(__DIR__ . '/services_dependencies.php');
$builder->addDefinitions(__DIR__ . '/actions_dependencies.php');

$c=$builder->build();

$app = $c->get(\Slim\App::class);

$app->add(new Cors());

$c->get('db');

$errorMiddleware = $app->addErrorMiddleware(true, false, false);
$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->forceContentType('application/json');



return $app;
