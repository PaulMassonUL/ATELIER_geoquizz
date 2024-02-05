<?php

namespace pizzashop\shop\domain\service\commande;

use pizzashop\shop\domain\dto\commande\CommandeDTO;

interface iCommander
{
    function creerCommande(CommandeDTO $c) : CommandeDTO;

    function validerCommande(string $id) : CommandeDTO;

    function accederCommande(string $id) : CommandeDTO;
}