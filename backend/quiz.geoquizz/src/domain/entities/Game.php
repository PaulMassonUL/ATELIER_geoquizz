<?php

namespace geoquizz\quiz\domain\entities\game;

use geoquizz\quiz\domain\dto\game\GameDTO;

class Game extends \Illuminate\Database\Eloquent\Model
{

    protected $connection = 'quiz';
    protected $table = 'game';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $fillable = ['token', 'id_serie', 'sequence'];

    public function toDTO() : GameDTO {
        return new GameDTO(
            $this->id,
            $this->token,
            $this->id_serie,
            $this->sequence,
        );
    }
}