<?php

namespace pizzashop\shop\domain\entities\commande;

use pizzashop\shop\domain\dto\commande\ItemDTO;

class Item extends \Illuminate\Database\Eloquent\Model
{
    const TAILLE_NORMALE = 1;
    const TAILLE_GRANDE = 2;

    protected $connection = 'commande';
    protected $table = 'item';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $fillable = ['numero', 'libelle', 'taille', 'libelle_taille', 'tarif', 'quantite', 'commande_id'];

    public function commande() {
        return $this->belongsTo(Commande::class, 'commande_id', 'id');
    }

    public function toDTO() : ItemDTO {
        $i = new ItemDTO(
            $this->numero,
            $this->taille,
            $this->quantite
        );
        $i->id = $this->id;
        $i->libelle = $this->libelle;
        $i->libelle_taille = $this->libelle_taille;
        $i->tarif = $this->tarif;
        return $i;
    }
}