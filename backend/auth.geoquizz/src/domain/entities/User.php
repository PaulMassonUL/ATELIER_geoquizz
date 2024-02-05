<?php

namespace geoquizz\auth\domain\entities;

use geoquizz\auth\domain\dto\UserDTO;

class User extends \Illuminate\Database\Eloquent\Model
{
    public $connection = 'auth';
    protected $table = 'users';
    protected $primaryKey = 'email';
    public $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'email',
        'password',
        'active',
        'activation_token',
        'activation_token_expiration_date',
        'refresh_token',
        'refresh_token_expiration_date',
        'reset_passwd_token',
        'reset_passwd_token_expiration_date',
        'username'
    ];

    public function toDTO(): UserDTO
    {
        $userDTO = new UserDTO($this->email);
        $userDTO->active = $this->active;
        $userDTO->activation_token = $this->activation_token;
        $userDTO->activation_token_expiration_date = $this->activation_token_expiration_date;
        $userDTO->refresh_token = $this->refresh_token;
        $userDTO->refresh_token_expiration_date = $this->refresh_token_expiration_date;
        $userDTO->reset_passwd_token = $this->reset_passwd_token;
        $userDTO->reset_passwd_token_expiration_date = $this->reset_passwd_token_expiration_date;
        $userDTO->username = $this->username;
        return $userDTO;
    }
}
