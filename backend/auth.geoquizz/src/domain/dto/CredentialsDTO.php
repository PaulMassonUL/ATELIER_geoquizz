<?php

namespace geoquizz\auth\domain\dto;

class CredentialsDTO extends DTO
{
    public string $email, $password, $username;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password= $password;
    }
}