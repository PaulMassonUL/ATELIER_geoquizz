<?php

namespace pizzashop\shop\domain\entities\commande;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use pizzashop\shop\domain\dto\commande\CommandeDTO;

class Commande extends Model
{
    const ETAT_CREE = 1;
    const ETAT_CREE_LIBELLE = 'cree';
    const ETAT_VALIDE = 2;
    const ETAT_VALIDE_LIBELLE = 'validee';
    const ETAT_PAYEE = 3;
    const ETAT_PAYEE_LIBELLE = 'payee';

    const TYPE_LIVRAISON_SUR_PLACE = 1;
    const TYPE_LIVRAISON_DOMICILE = 2;
    const TYPE_LIVRAISON_A_EMPORTER = 3;

    protected $connection = 'commande';
    protected $table = 'commande';
    protected $primaryKey = 'id';
    public $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = ['id', 'date_commande', 'type_livraison', 'mail_client', 'etat', 'delai'];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'commande_id', 'id');
    }

    public function calculerMontantTotal(): void
    {
        $this->montant_total = 0;
        foreach ($this->items as $item) {
            $this->montant_total += $item->tarif * $item->quantite;
        }
        $this->save();
    }

    public function toDTO(): CommandeDTO
    {
        $c = new CommandeDTO(
            $this->mail_client,
            $this->type_livraison,
            []
        );
        $c->id = $this->id;
        $c->date_commande = $this->date_commande;
        $c->montant_total = $this->montant_total;
        $c->etat = $this->etat;
        $c->delai = $this->delai;

        foreach ($this->items as $item) {
            $c->items[] = $item->toDTO();
        }

        return $c;
    }

}