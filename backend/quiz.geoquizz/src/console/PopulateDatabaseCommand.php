<?php

namespace geoquizz\quiz\console;
use Faker\Generator;
use geoquizz\quiz\domain\entities\Game;
use Illuminate\Database\Capsule\Manager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateDatabaseCommand extends Command
{
    private Generator $faker;
    private Manager $db;

    public function __construct(Manager $db)
    {
        $this->db = $db;
        $this->faker = \Faker\Factory::create('fr_FR');
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('db:populate');
        $this->setDescription('Populate database');
    }

    protected function createSequence () {
        //creation de la sequence
        $sequence = [];
        for ($i=0; $i < 10; $i++) {
            $image = json_encode([
                "id" => $i,
                "url" => $this->faker->url(),
                "coordonnées" => $this->faker->localCoordinates()
            ]);
            $sequence [] = $image;
        }
        return json_encode($sequence);
    }

    protected function createGame () {
        //creation de la game
        $game = new Game();
        $game->id = $this->faker->uuid;
        $game->token = $this->faker->linuxPlatformToken;
        $game->state = 1;
        $game->id_serie = $this->faker->biasedNumberBetween(1, 15);
        $game->sequence = $this->createSequence();
        $game->isPublic = $this->faker->boolean;
        $game->level = $this->faker->biasedNumberBetween(1, 10);
        $game->id_user = $this->faker->email;
        $game->save();
    }

    public function execute(InputInterface $input, OutputInterface $output) : int
    {
        //execution de la commande pour peupler la base de données
        $output->writeln('Populate database...');
        $db = $this->db;
        $db->getConnection()->statement("SET FOREIGN_KEY_CHECKS=0");
        $db->getConnection()->statement("TRUNCATE `game`");
        $db->getConnection()->statement("TRUNCATE `played`");
        $db->getConnection()->statement("SET FOREIGN_KEY_CHECKS=1");

        //créer une dizaine de game
        for($i = 0; $i < 10; $i++) {
            $this->createGame();
        }
        $output->writeln('Database created successfully!');
        return 0;
    }

}