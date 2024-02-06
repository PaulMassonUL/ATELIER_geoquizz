<?php

namespace geoquizz\quiz\console;
use Faker\Generator;
use geoquizz\quiz\domain\entities\game\Game;
use Illuminate\Database\Capsule\Manager;

class populateDatabaseCommand
{
    private Generator $faker;

    public function __construct(Manager $capsule)
    {
        $this->faker = \Faker\Factory::create('fr_FR');
    }

    protected function createSequence () {
        $sequence = [];
        for ($i=0; $i < 10; $i++) {
            $image = "{\"id:\" $i, \"url:\" " + $this->faker->url + ", \"coordonnées:\" " + $this->faker->localCoordinates + "}";
            $sequence [] = $image;
        }
        return $sequence;
    }

    protected function createGame () {
        $game = new Game();
        $game->token = $this->faker->linuxPlatformToken;
        $game->id_serie = $this->faker->biasedNumberBetween(1, 15);
        $game->sequence = $this->createSequence();
        $game->save();
    }

    public function execute()
    {
        $db = new \Illuminate\Database\Capsule\Manager();

    }

}