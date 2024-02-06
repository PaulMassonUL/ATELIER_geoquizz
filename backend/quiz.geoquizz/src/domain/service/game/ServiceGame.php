<?php

namespace geoquizz\quiz\domain\service\game;

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
        $uuid = Uuid::uuid4();
        $game = Game::create([
            'id' => $uuid->toString(),
            'token' => 12345,
            'id_serie' => $g->id_serie,
            'sequence' => $this->serviceSerie->sequenceByIdSerie($g->id_serie),
        ]);

        $this->logger->info("Game $game->id créée");
        return $game->toDTO();

    }

}