<?php

namespace geoquizz\quiz\domain\service\game;

use Exception;
use geoquizz\quiz\domain\entities\Played;
use geoquizz\quiz\domain\manager\JwtManager;
use geoquizz\quiz\domain\dto\GameDTO;
use geoquizz\quiz\domain\entities\Game;
use geoquizz\quiz\domain\service\serie\ServiceSerie;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class ServiceGame implements iGame
{
    private LoggerInterface $logger;

    private ServiceSerie $serviceSerie;

    private string $auth_uri;

    private string $serie_uri;



    public function __construct(LoggerInterface $logger, ServiceSerie $serviceSerie, string $auth_uri, string $serie_uri)
    {
        $this->logger = $logger;
        $this->serviceSerie = $serviceSerie;
        $this->auth_uri = $auth_uri;
        $this->serie_uri = $serie_uri;
    }


    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function creerGame(GameDTO $g): GameDTO
    {
        $jwt = new JwtManager("secret");
        $jwt->setIssuer($_SERVER['HTTP_HOST']);

        $uuid = Uuid::uuid4()->toString();

        $game = Game::create([
            'id' => $uuid,
            'token' => $jwt->create([
                'id' => $uuid,
                'id_serie' => $g->id_serie,
            ]),
            'id_serie' => $g->id_serie,
            'sequence' => $this->serviceSerie->sequenceByIdSerie($g->id_serie),
            'state' => Game::ETAT_CREE,
            'level' => $g->level,
            'isPublic' => $g->isPublic,
            'id_user' => $g->id_user
        ]);
        $gameDTO = $game->toDTO();
        try {
            $clientAuth = new Client([
                'base_uri' => $this->auth_uri,
                'timeout' => 60.0,
                'http_errors' => false
            ]);
            $headers = [
                'Origin' => $_SERVER['HTTP_HOST'],
            ];
            $response = $clientAuth->request('GET', '/api/users/username?id_user=' . $g->id_user, [
                'headers' => $headers
            ]);
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);
            $gameDTO->username = $data['username'];
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération du username" . $e);
        }

        $this->playedGame($game, $g->id_user);

        $this->logger->info("Game $uuid créée");
        return $gameDTO;
    }

    //Ajoute un objet played dans la base de données (table played) avec les informations de la partie jouée
    public function playedGame(Game $game, string $id_user): void
    {
        $played = new Played();
        $played->id_game = $game->id;
        $played->id_user = $id_user;
        $played->score = 0;
        $played->date = date('Y-m-d H:i:s');

        $played->save();
    }


    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function getGamesPublic(): array
    {
        try{
            $clientAuth = new Client([
                'base_uri' => $this->auth_uri,
                'timeout' => 60.0,
                'http_errors' => false
            ]);
            $headers = [
                'Origin' => $_SERVER['HTTP_HOST'],
            ];

            $games = Game::where('isPublic', 1)->get();
            $gamesDTO = [];
            for ($i = 0; $i < count($games); $i++) {
                $game = $games[$i];
                $response = $clientAuth->request('GET', '/api/users/username?id_user=' . $game->id_user, [
                    'headers' => $headers
                ]);
                $body = $response->getBody()->getContents();
                $data = json_decode($body, true);

                $gameDataDTO = $game->toDTO();
                $gameDataDTO->username = $data['username'];
                $gamesDTO[] = $gameDataDTO;
            }

            return $gamesDTO;
        } catch (Exception $e) {
            throw new Exception("Erreur lors de la récupération des games publics" . $e);
        }
    }

    //retourner une game avec son id si elle est public

    /**
     * @throws Exception
     */
    public function startGameById($id_game, $id_user = null): GameDTO
    {
        $game = Game::where('id', $id_game)->first();
        if (!$game) {
            throw new Exception("Game not found", 404);
        }

        if ($game->isPublic == 1) {
            $this->playedGame($game, $game->id_user);
            return $game->toDTO();
        } else {
            // Vérifie si l'utilisateur fourni correspond à l'utilisateur associé au jeu
            if ($id_user !== null && $game->id_user == $id_user) {
                $this->playedGame($game, $game->id_user);
                return $game->toDTO();
            } else {
                throw new Exception("Access denied", 403); // Accès non autorisé
            }
        }
    }



}