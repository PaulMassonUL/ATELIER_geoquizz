<?php

namespace geoquizz\quiz\domain\service\game;

use geoquizz\quiz\domain\dto\GameDTO;
use geoquizz\quiz\domain\entities\Game;

interface iGame
{
    function creerGame(GameDTO $g) : GameDTO;

    function GetGamesPublic() : array;

    function startGameById($id_game, $id_user) : GameDTO;
}