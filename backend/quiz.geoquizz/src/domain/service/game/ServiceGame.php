<?php

namespace geoquizz\quiz\domain\service\game;

use Exception;
use geoquizz\quiz\domain\dto\GameDTO;
use geoquizz\quiz\domain\entities\game\Game;
use geoquizz\quiz\domain\service\game\iGame;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

class ServiceGame implements iGame
{
    private string $catalog_uri;
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger, string $catalog_uri)
    {
        $this->logger = $logger;
        $this->catalog_uri = $catalog_uri;
    }

    public function creerGame(GameDTO $g): GameDTO
    {
        $uuid = Uuid::uuid4();
        $game = Game::create([
            'id' => $uuid->toString(),
            'token' => 12345,
            'id_serie' => $g->id_serie,
        ]);

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




}