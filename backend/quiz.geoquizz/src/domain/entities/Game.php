<?php

namespace geoquizz\quiz\domain\entities;

use geoquizz\quiz\domain\dto\GameDTO;

class Game extends \Illuminate\Database\Eloquent\Model
{
    const LEVEL_FACILE = 1;
    const LEVEL_NORMAL = 2;
    const LEVEL_DIFFICILE = 3;

    protected $connection = 'quiz';
    protected $table = 'game';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $fillable = ['id','token', 'id_serie', 'sequence','level','isPublic','id_user'];


    public function toDTO() : GameDTO {
        $g = new GameDTO($this->id_serie, $this->id_user, $this->level, $this->isPublic);
        $g->id = $this->id;
        $g->token = $this->token;
        $g->id_serie = $this->id_serie;
        $g->sequence = $this->sequence;
        $g->level = $this->level;
        $g->isPublic = $this->isPublic;
        $g->id_user = $this->id_user;
        $g->created_at = $this->created_at;
        $g->updated_at = $this->updated_at;
        return $g;
    }
}