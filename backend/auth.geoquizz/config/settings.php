<?php

return [

    'auth.log.name' => 'auth',
    'auth.log.file' => __DIR__ . '/../logs/auth.log',
    'auth.log.level' => \Psr\Log\LogLevel::ALERT,

    'auth.token.secret' => getenv('JWT_SECRET'),
    'auth.token.expiration' => 3600,
    'auth.token.issuer' => $_SERVER['HTTP_HOST'],

];