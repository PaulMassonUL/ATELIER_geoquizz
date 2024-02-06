<?php

namespace geoquizz\quiz\domain\service\serie;

interface iSerie
{
    function sequenceByIdSerie(string $id) : string;
}