<?php

namespace pizzashop\shop\domain\service\commande;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use pizzashop\shop\domain\dto\commande\CommandeDTO;
use pizzashop\shop\domain\entities\commande\Commande;
use pizzashop\shop\domain\entities\commande\Item;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class ServiceCommande implements iCommander
{
    private string $catalog_uri;
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger, string $catalog_uri)
    {
        $this->logger = $logger;
        $this->catalog_uri = $catalog_uri;
    }

    /**
     * @throws ServiceCommandeNotFoundException
     */
    public function accederCommande(string $id): CommandeDTO
    {
        try {
            $commande = Commande::findOrFail($id)->with('items')->first();
        } catch (Exception) {
            throw new ServiceCommandeNotFoundException("Commande $id non trouvée");
        }
        return $commande->toDTO();
    }

    /**
     * @throws ServiceCommandeNotFoundException
     * @throws ServiceCommandeInvalidTransitionException
     */
    public function validerCommande(string $id): CommandeDTO
    {
        try {
            $commande = Commande::findOrFail($id);
        } catch (Exception) {
            throw new ServiceCommandeNotFoundException("Commande $id non trouvée");
        }
        if ($commande->etat >= Commande::ETAT_VALIDE) {
            $etat = match ($commande->etat) {
                Commande::ETAT_VALIDE => Commande::ETAT_VALIDE_LIBELLE,
                Commande::ETAT_PAYEE => Commande::ETAT_PAYEE_LIBELLE,
                default => '',
            };
            throw new ServiceCommandeInvalidTransitionException($etat);
        }
        $commande->update(['etat' => Commande::ETAT_VALIDE]);
        $this->logger->info("Commande $id validée");
        return $commande->toDTO();
    }

    /**
     * @throws ServiceCommandeInvalidDataException|GuzzleException
     */
    public function creerCommande(CommandeDTO $c): CommandeDTO
    {
        $this->validerDonneesDeCommande($c);

        $uuid = Uuid::uuid4();
        $commande = Commande::create([
            'id' => $uuid->toString(),
            'date_commande' => date('Y-m-d H:i:s'),
            'type_livraison' => $c->type_livraison,
            'mail_client' => $c->mail_client,
            'delai' => 0,
            'etat' => Commande::ETAT_CREE
        ]);

        $client = new Client([
            'base_uri' => $this->catalog_uri,
            'timeout' => 30.0
        ]);

        $headers = [
            'Origin' => $_SERVER['HTTP_HOST'],
            'Content-Type' => 'application/json;charset=utf-8'
        ];

        $numerostaillesItems = array_map(function ($itemDTO) {
            return ['numero' => $itemDTO->numero, 'taille' => $itemDTO->taille];
        }, $c->items);
        $numerostaillesItems = array_unique($numerostaillesItems, SORT_REGULAR);

        try {
            $response = $client->request('GET', '/produits-commande', [
                'headers' => $headers,
                'body' => json_encode($numerostaillesItems)
            ]);

            $infosItems = json_decode($response->getBody()->getContents(), true);

            // Créez les items de la commande en utilisant les informations obtenues
            foreach ($c->items as $itemDTO) {
                $key = $itemDTO->numero . '-' . $itemDTO->taille;
                $infoItem = $infosItems["produits"][$key];

                $item = new Item();
                $item->numero = $itemDTO->numero;
                $item->taille = $itemDTO->taille;
                $item->quantite = $itemDTO->quantite;
                $item->libelle = $infoItem['libelle_produit'];
                $item->libelle_taille = $infoItem['taille']['libelle'];
                $item->tarif = $infoItem['taille']['tarif'];

                $commande->items()->save($item);
            }
        } catch (RequestException $e) {
            throw new ServiceCommandeInvalidDataException("Impossible de récupérer les informations des produits : " . $e->getMessage());
        }

        $commande->calculerMontantTotal();

        $this->logger->info("Commande $commande->id créée");
        return $commande->toDTO();
    }


    /**
     * @throws ServiceCommandeInvalidDataException
     */
    public function validerDonneesDeCommande(CommandeDTO $c): void
    {
        try {
            v::attribute('mail_client', v::email())
                ->attribute('type_livraison', v::in([Commande::TYPE_LIVRAISON_SUR_PLACE, Commande::TYPE_LIVRAISON_DOMICILE, Commande::TYPE_LIVRAISON_A_EMPORTER]))
                ->attribute('items', v::arrayVal()->notEmpty()
                    ->each(v::attribute('numero', v::intVal()->positive())
                        ->attribute('taille', v::in([1, 2]))
                        ->attribute('quantite', v::intVal()->positive())
                    ))
                ->assert($c);

        } catch (NestedValidationException $e) {
            throw new ServiceCommandeInvalidDataException("Données de commande invalides : " . $e->getFullMessage());
        }
    }

}