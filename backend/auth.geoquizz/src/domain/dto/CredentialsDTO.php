<?php

namespace geoquizz\auth\domain\dto;

class CredentialsDTO extends DTO
{
    public string $email;
    public string $password;
    public string $username;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
}
