<?php

namespace geoquizz\quiz\domain\dto;


use Ramsey\Uuid\Uuid;

class GameDTO extends \geoquizz\quiz\domain\dto\DTO
{

    public string $id;
    public string $token;
    public string $id_serie;
    public string $sequence;
    public bool $isPublic = false;
    public int $level;
    public string $id_user;
    public string $created_at;
    public string $updated_at;





    public function __construct(string $id_serie, string $id_user, int $level, bool $isPublic)
    {
        $this->id_serie = $id_serie;
        $this->id_user = $id_user;
        $this->level = $level;
        $this->isPublic = $isPublic;
    }



}