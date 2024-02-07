<?php

namespace geoquizz\quiz\console;

use Slim\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDatabaseCommand extends Command
{
    private $db;
    public function __construct($db)
    {
        $this->db = $db;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('db:create');
        $this->setDescription('Create database');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $output->writeln('Creating database...');
        $db = $this->db;
        $db->getConnection()->statement("SET FOREIGN_KEY_CHECKS=0");
        $db->getConnection()->statement("DROP TABLE IF EXISTS `game`");
        $db->getConnection()->statement("DROP TABLE IF EXISTS `played`");
        $db->getConnection()->statement("SET FOREIGN_KEY_CHECKS=1");
        $db->getConnection()->statement("
CREATE TABLE game
(
    `id` INT AUTO_INCREMENT NOT NULL,
    `token` varchar(255) NOT NULL,
    `id_serie` varchar(255) NOT NULL,
    `sequence` JSON NOT NULL,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    PRIMARY KEY (`id`)
);
");

        $db->getConnection()->statement("
CREATE TABLE `played`
(
    `id`        int(11) NOT NULL AUTO_INCREMENT,
    `id_game` int(11) NOT NULL,
    `id_user`   varchar(255) NOT NULL,
    `score`     int(11) NOT NULL,
    `state`      int(11) NOT NULL,
    `date`      datetime NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_game_id` FOREIGN KEY (`id_game`) REFERENCES `game` (`id`) ON DELETE CASCADE
);
");
        $output->writeln('Database created successfully!');
        return 0;
    }

}