<?php

namespace pizzashop\shop\app\actions;

use pizzashop\shop\domain\service\commande\iCommander;
use pizzashop\shop\domain\service\commande\ServiceCommande;
use pizzashop\shop\domain\service\commande\ServiceCommandeNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Routing\RouteContext;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class AccederCommandeAction extends Action
{

    private ServiceCommande $serviceCommande;

    public function __construct(iCommander $serviceCommande)
    {
        $this->serviceCommande = $serviceCommande;
    }

    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        if(is_null($args['id_commande'])) throw new HttpBadRequestException($rq, 'id_commande manquant');
        try {

            $commande = $this->serviceCommande->accederCommande($args['id_commande']);

            $data = [
                'type' => 'resource',
                'commande' => [
                    'id' => $commande->id,
                    'date_commande' => $commande->date_commande,
                    'type_livraison' => $commande->type_livraison,
                    'etat' => $commande->etat,
                    'mail_client' => $commande->mail_client,
                    'montant_total' => $commande->montant_total,
                    'delai' => $commande->delai
                ]
            ];
            var_dump($commande->items);
            foreach ($commande->items as $item) {
                $data['commande']['items'] = [
                    'numero' => $item->numero,
                    'taille' => $item->taille,
                    'quantite' => $item->quantite,
                    'libelle' => $item->libelle,
                    'libelle_taille' => $item->libelle_taille,
                    'tarif' => $item->tarif,
                ];
            }
            $routeParser = RouteContext::fromRequest($rq)->getRouteParser();
            $data['links'] = [
                'self' => [
                    'href' => $routeParser->urlFor('commande', ['id_commande' => $commande->id]),
                ],



            ];

            $rs->getBody()->write(json_encode($data));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);

        } catch (CommandNotFoundException) {
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(404);
        } catch (ServiceCommandeNotFoundException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        }

    }

}