<?php

namespace geoquizz\quiz\domain\service\played;

interface iPlayed
{
    function getGamesPlayedByUser($id_user): array;

}