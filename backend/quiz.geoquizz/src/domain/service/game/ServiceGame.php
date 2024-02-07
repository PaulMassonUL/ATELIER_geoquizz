<?php

namespace geoquizz\quiz\domain\service\game;

use geoquizz\quiz\domain\manager\JwtManager;
use geoquizz\quiz\domain\dto\GameDTO;
use geoquizz\quiz\domain\entities\Game;
use geoquizz\quiz\domain\service\serie\ServiceSerie;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class ServiceGame implements iGame
{
    private LoggerInterface $logger;

    private ServiceSerie $serviceSerie;

    public function __construct(LoggerInterface $logger, ServiceSerie $serviceSerie)
    {
        $this->logger = $logger;
        $this->serviceSerie = $serviceSerie;
    }


    /**
     * @throws \Exception
     */
    public function creerGame(GameDTO $g): GameDTO
    {
        $jwt = new JwtManager("secret");
        $jwt->setIssuer($_SERVER['HTTP_HOST']);

        $uuid = Uuid::uuid4();
        $game = Game::create([
            'id' => $uuid->toString(),
            'token' => $jwt->create([
                'id' => $uuid->toString(),
                'id_serie' => $g->id_serie,
            ]),
            'id_serie' => $g->id_serie,
            'sequence' => $this->serviceSerie->sequenceByIdSerie($g->id_serie),
            'level' => $g->level,
            'isPublic' => $g->isPublic,
            'id_user' => $g->id_user
        ]);

        $this->logger->info("Game $uuid créée");
        return $game->toDTO();

    }

    public function getGames(): array
    {
        $games = Game::all();
        $gamesDTO = [];
        foreach ($games as $game) {
            $gamesDTO[] = $game->toDTO();
        }
        return $gamesDTO;
    }

}