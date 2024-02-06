<?php

namespace geoquizz\auth\domain\provider;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use geoquizz\auth\domain\entities\User;

class AuthProvider implements AuthProviderInterface
{
    private User $authenticatedUser;

    /**
     * @throws AuthProviderInvalidCredentialsException
     */
    public function checkCredentials(string $email, string $pass): void
    {
        try {
            $user = User::findOrFail($email);
            if (!password_verify($pass, $user->password)) {
                throw new AuthProviderInvalidCredentialsException("Invalid credentials");
            }
            $this->authenticatedUser = $user;
            $this->generateRefreshToken($user);
        } catch (ModelNotFoundException) {
            throw new AuthProviderInvalidCredentialsException("Invalid credentials");
        }
    }

    public function checkToken(string $token): void
    {
        try {
            $user = User::where('refresh_token', $token)->where('refresh_token_expiration_date', '>=', date('Y-m-d H:i:s'))->firstOrFail();
        } catch (\Exception) {
            throw new AuthProviderInvalidTokenException("Invalid refresh token");
        }
        $this->generateRefreshToken($user);
        $this->authenticatedUser = $user;
    }

    public function generateRefreshToken(User $user): void
    {
        $user->refresh_token = bin2hex(random_bytes(32));
        $user->refresh_token_expiration_date = date('Y-m-d H:i:s', time() + 3600);
        $user->save();
    }

    /**
     * @throws AuthProviderInvalidCredentialsException
     */
    public function register(string $email, string $pass, string $username): void
    {
        $user = User::find($email);
        if (!is_null($user)) {
            throw new AuthProviderInvalidCredentialsException("This email is already used");
        }

        $user = new User();
        $user->email = $email;
        $user->password = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 12]);
        $user->username = $username;
        $user->save();

        $this->authenticatedUser = $user;
    }

    public function getAuthenticatedUser(): array
    {
        return [
            "email" => $this->authenticatedUser->email,
            "username" => $this->authenticatedUser->username,
            "refresh_token" => $this->authenticatedUser->refresh_token
        ];
    }
}
