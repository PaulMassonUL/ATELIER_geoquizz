<?php

namespace pizzashop\shop\domain\dto\commande;

class ItemDTO extends \pizzashop\shop\domain\dto\DTO
{

    public string $id;
    public string $libelle;
    public string $libelle_taille;
    public float $tarif;
    public int $numero;
    public int $taille;
    public int $quantite;

    public function __construct(int $numero, int $taille, int $quantite)
    {
        $this->numero = $numero;
        $this->taille = $taille;
        $this->quantite = $quantite;
    }


}