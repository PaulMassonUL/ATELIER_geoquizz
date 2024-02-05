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
        'refresh_token',
        'refresh_token_expiration_date',
        'username'
    ];

    public function toDTO(): UserDTO
    {
        $userDTO = new UserDTO($this->email);
        $userDTO->refresh_token = $this->refresh_token;
        $userDTO->refresh_token_expiration_date = $this->refresh_token_expiration_date;
        $userDTO->username = $this->username;
        return $userDTO;
    }
}
