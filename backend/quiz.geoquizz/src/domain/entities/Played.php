<?php

namespace geoquizz\quiz\domain\entities;

use Illuminate\Database\Eloquent\Model;

class Played extends Model
{
    protected $table = 'played';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['id_game', 'id_user', 'score', 'date'];

    public function game(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Game');
    }
}
