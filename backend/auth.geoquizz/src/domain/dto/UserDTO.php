<?php

namespace geoquizz\auth\domain\dto;

class UserDTO extends DTO
{
    public string $email;
    public string $password;
    public string $active;
    public ?string $activation_token;
    public ?string $activation_token_expiration_date;
    public ?string $refresh_token;
    public ?string $refresh_token_expiration_date;
    public ?string $reset_passwd_token;
    public ?string $reset_passwd_token_expiration_date;
    public ?string $username;

    public function __construct(string $email)
    {
        $this->email = $email;
    }
}
