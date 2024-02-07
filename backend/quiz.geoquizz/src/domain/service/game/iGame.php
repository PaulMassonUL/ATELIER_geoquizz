<?php

namespace geoquizz\quiz\domain\service\game;

use geoquizz\quiz\domain\dto\GameDTO;

interface iGame
{
    function creerGame(GameDTO $g) : GameDTO;

    function GetGamesPublic() : array;
}