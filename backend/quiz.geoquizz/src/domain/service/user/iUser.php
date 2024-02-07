<?php

namespace geoquizz\quiz\domain\service\user;

interface iUser
{
    function getProfile($token);
}