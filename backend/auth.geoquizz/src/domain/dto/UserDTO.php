<?php

namespace geoquizz\auth\domain\dto;

class UserDTO extends DTO
{
    public string $email;
    public ?string $refresh_token;
    public ?string $refresh_token_expiration_date;
    public ?string $username;

    public function __construct(string $email)
    {
        $this->email = $email;
    }
}
