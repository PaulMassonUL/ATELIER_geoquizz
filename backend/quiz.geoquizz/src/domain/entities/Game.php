<?php

namespace geoquizz\quiz\domain\entities;

use geoquizz\quiz\domain\dto\GameDTO;

class Game extends \Illuminate\Database\Eloquent\Model
{
    const LEVEL_FACILE = 1;
    const LEVEL_NORMAL = 2;
    const LEVEL_DIFFICILE = 3;

    const ETAT_CREE = 1;
    const ETAT_EN_COURS = 2;
    const ETAT_TERMINEE = 3;


    protected $table = 'game';
    protected $primaryKey = 'id';
    public $keyType = 'string';
    public $incrementing = false;
    public $timestamps = true;
    public $fillable = ['id','token', 'id_serie', 'sequence','level','isPublic', 'state', 'id_user'];

    public function toDTO() : GameDTO {
        $g = new GameDTO($this->id_serie, $this->id_user, $this->level, $this->isPublic);
        $g->id = $this->id;
        $g->token = $this->token;
        $g->id_serie = $this->id_serie;
        $g->sequence = $this->sequence;
        $g->level = $this->level;
        $g->isPublic = $this->isPublic;
        $g->state = $this->state;
        $g->id_user = $this->id_user;
        $g->created_at = $this->created_at;
        $g->updated_at = $this->updated_at;
        return $g;
    }
}