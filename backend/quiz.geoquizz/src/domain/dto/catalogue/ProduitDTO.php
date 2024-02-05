<?php

namespace pizzashop\shop\domain\dto\catalogue;

class ProduitDTO extends \pizzashop\shop\domain\dto\DTO
{

    public int $numero;
    public string $libelle_produit;
    public string $libelle_taille;
    public string $tarif;

    public function __construct(int $numero, string $libelle_produit, string $libelle_taille, float $tarif)
    {
        $this->numero = $numero;
        $this->libelle_produit = $libelle_produit;
        $this->libelle_taille = $libelle_taille;
        $this->tarif = $tarif;
    }


}