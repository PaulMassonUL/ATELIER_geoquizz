<?php

namespace geoquizz\quiz\domain\dto;


use Ramsey\Uuid\Uuid;

class GameDTO extends \geoquizz\quiz\domain\dto\DTO
{

    public string $id;
    public string $token;
    public string $id_serie;
    public string $sequence;
    public string $created_at;
    public string $updated_at;



    public function __construct(string $id_serie)
    {
        $this->id_serie = $id_serie;
    }



}