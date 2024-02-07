<?php

namespace geoquizz\quiz\domain\service\user;

use geoquizz\quiz\domain\service\game\ServiceGame;
use GuzzleHttp\Client;

class ServiceUser implements IUser
{

    private ServiceGame $serviceGame;

    public function __construct(ServiceGame $serviceGame)
    {
        $this->serviceGame = $serviceGame;
    }

    function getProfile($token): array
    {
        $user = new Client([
            'base_uri' => 'http://localhost:2780/api/',
            'timeout' => 2.0,
        ]);
        $response = [];

        return $response;
    }
}