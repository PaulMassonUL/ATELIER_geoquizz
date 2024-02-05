<?php

namespace pizzashop\shop\app\actions;

use Exception;
use pizzashop\shop\domain\dto\commande\CommandeDTO;
use pizzashop\shop\domain\service\commande\iCommander;
use pizzashop\shop\domain\service\commande\ServiceCommande;
use pizzashop\shop\domain\service\commande\ServiceCommandeInvalidDataException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;

class CreerCommandeAction extends Action
{

    private ServiceCommande $serviceCommande;

    public function __construct(iCommander $serviceCommande)
    {
        $this->serviceCommande = $serviceCommande;
    }

    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        // Récupérez les données JSON du corps de la requête
        $data = $rq->getParsedBody();

        if (!isset($data['mail_client']) || !isset($data['type_livraison']) || !isset($data['items'])) {
            throw new HttpBadRequestException($rq, 'Données invalides');
        }

        try {
            // Créez un DTO à partir des données reçues
            $commandeDTO = new CommandeDTO($data['mail_client'], $data['type_livraison'], $data['items']);
            // Créez une commande à partir du DTO
            $commande = $this->serviceCommande->creerCommande($commandeDTO);

            // Créez une réponse JSON
            $donnees =
                [
                    'id' => $commande->id,
                    'date_commande' => $commande->date_commande,
                    'type_livraison' => $commande->type_livraison,
                    'etat' => $commande->etat,
                    'mail_client' => $commande->mail_client,
                    'montant' => $commande->montant_total,
                    'delai' => $commande->delai
                ];
            foreach ($commande->items as $item) {
                $donnees['items'][] = [
                    'numero' => $item->numero,
                    'taille' => $item->taille,
                    'quantite' => $item->quantite,
                    'libelle' => $item->libelle,
                    'libelle_taille' => $item->libelle_taille,
                    'tarif' => $item->tarif,
                ];
            }

            $rs->getBody()->write(json_encode($donnees));
            return $rs->withHeader('Content-Type', 'application/json')->withHeader('Location', '/commande/' . $commande->id)->withStatus(201);

        } catch (ServiceCommandeInvalidDataException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        } catch (Exception $e) {
            $rs->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(500);
        }
    }

}