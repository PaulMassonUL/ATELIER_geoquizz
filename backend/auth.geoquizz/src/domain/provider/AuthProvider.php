<?php

namespace geoquizz\auth\domain\provider;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use geoquizz\auth\domain\entities\User;

class AuthProvider implements iAuthProvider
{

    private User $authenticatedUser;

    /**
     * @throws AuthProviderInvalidCredentialsException
     */
    public function checkCredentials(string $email, string $pass): void
    {
        try {
            $user = User::findOrFail($email);
            if (!password_verify($pass, $user->password)) throw new AuthProviderInvalidCredentialsException("Invalid credentials");
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
        if (!is_null($user)) throw new AuthProviderInvalidCredentialsException("User already exists");

        $user = new User();
        $user->email = $email;
        $user->password = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 12]);
        $user->username = $username;
        $user->save();

        $this->authenticatedUser = $user;
    }

    public function activate(string $token): void
    {
        try {
            $user = User::where('activation_token', $token)
                ->where('activation_token_expiration_date', '>=', date('Y-m-d H:i:s'))
                ->firstOrFail();

            $user->active = true;
            $user->save();
        } catch (ModelNotFoundException) {
            throw new AuthProviderInvalidTokenException("Invalid activation token");
        }
    }

    /**
     * @throws AuthProviderInvalidCredentialsException
     * @throws AuthProviderInvalidTokenException
     */
    public function resetPassword(string $token, string $old_pass, string $new_pass): void
    {
        try {
            $user = User::where('reset_passwd_token', $token)
                ->where('reset_passwd_token_expiration_date', '>=', date('Y-m-d H:i:s'))
                ->firstOrFail();

            if (!password_verify($old_pass, $user->password)) throw new AuthProviderInvalidCredentialsException("Invalid password");

            $user->password = password_hash($new_pass, PASSWORD_DEFAULT, ['cost' => 12]);
            $user->save();
        } catch (ModelNotFoundException) {
            throw new AuthProviderInvalidTokenException("Invalid reset password token");
        }
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