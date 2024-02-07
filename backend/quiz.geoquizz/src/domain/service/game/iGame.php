<?php

namespace geoquizz\quiz\domain\service\game;

use geoquizz\quiz\domain\dto\GameDTO;

interface iGame
{
    function creerGame(GameDTO $g) : array;

    function GetGamesPublic() : array;

    function getGameById($id) : GameDTO;
}