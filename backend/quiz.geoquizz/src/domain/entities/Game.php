<?php

namespace geoquizz\quiz\domain\entities;

use geoquizz\quiz\domain\dto\GameDTO;

class Game extends \Illuminate\Database\Eloquent\Model
{

    protected $connection = 'quiz';
    protected $table = 'game';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $fillable = ['id','token', 'id_serie', 'sequence'];


    public function toDTO() : GameDTO {
        $g = new GameDTO($this->id_serie);
        $g->id = $this->id;
        $g->token = $this->token;
        $g->id_serie = $this->id_serie;
        $g->sequence = $this->sequence;
        $g->created_at = $this->created_at;
        $g->updated_at = $this->updated_at;
        return $g;
    }
}