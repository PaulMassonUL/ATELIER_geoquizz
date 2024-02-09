<?php

namespace geoquizz\quiz\domain\service\played;

use Exception;
use geoquizz\quiz\domain\entities\Game;
use geoquizz\quiz\domain\entities\Played;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ServicePlayed implements iPlayed
{

    private string $series_uri;

    public function __construct(string $series_uri)
    {
        $this->series_uri = $series_uri;
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function getGamesPlayedByUser($id_user): array
    {
        $client = new Client([
            'base_uri' => $this->series_uri,
            'timeout' => 60.0,
        ]);
        $played = Played::where('id_user', $id_user)->get();
        $liste = [];
        foreach ($played as $p) {
            $game = Game::where('id', $p->id_game)->first();
            $level = $game->level;
            $id_serie = $game->id_serie;
            try {
                $response = $client->request('GET', '/items/Serie/' . $id_serie . '?fields=*.Image_id.*');
                $body = $response->getBody();
                $data = json_decode($body, true);
                $id_serie_name = $data['data']['name'];
            } catch (Exception $e) {
                throw new Exception("Erreur lors de la récupération de la série " . $id_serie . $e);
            }
            $liste[] = [
                'id' => $p->id,
                'id_game' => $p->id_game,
                'name_serie' => $id_serie_name,
                'level' => $level,
                'id_user' => $p->id_user,
                'score' => $p->score,
                'date' => $p->date
            ];
        }
        return $liste;
    }


}