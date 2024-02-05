<?php

namespace geoquizz\auth\domain\provider;

interface iAuthProvider
{

    public function checkCredentials(string $email, string $pass): void;

    public function checkToken(string $token): void;

    public function register(string $email, string $pass, string $username): void;

    public function activate(string $token): void;

    public function resetPassword(string $token, string $old_pass, string $new_pass): void;

    public function getAuthenticatedUser(): array;

}