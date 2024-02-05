<?php

namespace pizzashop\shop\domain\dto\commande;

class CommandeDTO extends \pizzashop\shop\domain\dto\DTO
{

    public string $id;
    public string $date_commande;
    public int $type_livraison;

    public string $mail_client;
    public array $items;

    public float $montant_total;

    public int $etat;

    public int $delai;

    public function __construct(string $mail_client, int $type_livraison, array $items)
    {
        $this->type_livraison = $type_livraison;
        $this->mail_client = $mail_client;
        $this->items = [];
        foreach ($items as $item) {
            $this->items[] = new ItemDTO($item['numero'], $item['taille'], $item['quantite']);
        }
    }


}