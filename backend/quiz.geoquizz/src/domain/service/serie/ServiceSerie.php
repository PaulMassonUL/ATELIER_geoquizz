<?php

namespace geoquizz\quiz\domain\service\serie;

use Exception;
use geoquizz\quiz\domain\entities\Game;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;

class ServiceSerie implements iSerie
{
    private string $series_uri;

    public function __construct(string $series_uri)
    {
        $this->series_uri = $series_uri;
    }

    /**
     * @throws Exception
     */
    public function sequenceByIdSerie(string $id, int $level): string
    {
        $client = new Client([
            'base_uri' => $this->series_uri,
            'timeout' => 60.0,
        ]);
        try {
            $response = $client->request('GET', '/items/Serie/' . $id . '?fields=*.Image_id.*');
            $body = $response->getBody();
            $data = json_decode($body, true);
            $images = array_column($data['data']['image'], 'Image_id');
            // Mélanger les images aléatoirement
            shuffle($images);
            if($level == Game::LEVEL_DIFFICILE) {
                $selectedImages = array_slice($images, 0, 10);
            } elseif ($level == Game::LEVEL_NORMAL) {
                $selectedImages = array_slice($images, 0, 8);
            } else {
                $selectedImages = array_slice($images, 0, 6);
            }
            $sequence = [];
            foreach ($selectedImages as $image) {
                $sequence[] = [
                    'id' => $image['id'],
                    'location' => $image['location'],
                    'city' => $image['city'],
                    'hint' => $image['hint'],
                    'url' => $image['url']
                ];
            }
            return json_encode($sequence); // Retourne la séquence encodée en JSON
        } catch (RequestException|GuzzleException $e) {
            throw new Exception("Erreur lors de la récupération de la séquence de la série " . $id . $e);
        }
    }

}