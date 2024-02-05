<?php

namespace geoquizz\auth\domain\service;

use geoquizz\auth\domain\dto\CredentialsDTO;
use geoquizz\auth\domain\dto\TokenDTO;
use geoquizz\auth\domain\dto\UserDTO;
use geoquizz\auth\domain\manager\iJwtManager;
use geoquizz\auth\domain\manager\JwtManagerExpiredTokenException;
use geoquizz\auth\domain\manager\JwtManagerInvalidTokenException;
use geoquizz\auth\domain\provider\AuthProviderInvalidCredentialsException;
use geoquizz\auth\domain\provider\AuthProviderInvalidTokenException;
use geoquizz\auth\domain\provider\iAuthProvider;
use Psr\Log\LoggerInterface;

class AuthService implements iAuth
{

    private iJwtManager $jwtManager;
    private iAuthProvider $authProvider;
    private LoggerInterface $logger;

    public function __construct(iJwtManager $jwtManager, iAuthProvider $authProvider, LoggerInterface $logger)
    {
        $this->jwtManager = $jwtManager;
        $this->authProvider = $authProvider;
        $this->logger = $logger;
    }

    /**
     * @throws AuthServiceCredentialsException
     */
    public function signup(CredentialsDTO $c): UserDTO
    {
        try {
            $this->authProvider->register($c->email, $c->password, $c->username);
        } catch (AuthProviderInvalidCredentialsException $e) {
            throw new AuthServiceCredentialsException($e->getMessage());
        }
        $user = $this->authProvider->getAuthenticatedUser();

        return new UserDTO($user['email']);
    }

    /**
     * @throws AuthServiceCredentialsException
     */
    public function signin(CredentialsDTO $c): TokenDTO
    {
        try {
            $this->authProvider->checkCredentials($c->email, $c->password);
        } catch (AuthProviderInvalidCredentialsException) {
            throw new AuthServiceCredentialsException("Invalid credentials: " . $c->email . " " . $c->password);
        }
        $user = $this->authProvider->getAuthenticatedUser();

        return new TokenDTO($this->jwtManager->create($user), $user['refresh_token']);
    }

    /**
     * @throws AuthServiceInvalidTokenException
     * @throws AuthServiceExpiredTokenException
     */
    public function validate(TokenDTO $t): UserDTO
    {
        try {
            $payload = $this->jwtManager->validate($t->access_token);
        } catch (JwtManagerExpiredTokenException) {
            throw new AuthServiceExpiredTokenException("Expired token");
        } catch (JwtManagerInvalidTokenException) {
            $this->logger->warning('failed jwt validation');
            throw new AuthServiceInvalidTokenException("Invalid token");
        }
        return new UserDTO($payload['email']);
    }

    /**
     * @throws AuthServiceInvalidTokenException
     */
    public function refresh(TokenDTO $t): TokenDTO
    {
        try {
            $this->authProvider->checkToken($t->refresh_token);
        } catch (AuthProviderInvalidTokenException $e) {
            $this->logger->warning('Failed jwt refresh because of invalid refresh token');
            throw new AuthServiceInvalidTokenException($e->getMessage());
        }
        $user = $this->authProvider->getAuthenticatedUser();

        return new TokenDTO($this->jwtManager->create($user), $user['refresh_token']);
    }

    /**
     * @throws AuthServiceInvalidTokenException
     */
    public function activate_signup(TokenDTO $t): void
    {
        try {
            $this->authProvider->activate($t->access_token);
        } catch (AuthProviderInvalidTokenException) {
            throw new AuthServiceInvalidTokenException("Invalid token");
        }
    }

    /**
     * @throws AuthServiceInvalidTokenException
     */
    public function reset_password(TokenDTO $t, CredentialsDTO $c, string $newPassword): void
    {
        try {
            $this->authProvider->resetPassword($t->access_token, $c->password, $newPassword);
        } catch (AuthProviderInvalidTokenException) {
            throw new AuthServiceInvalidTokenException("Invalid token");
        }
    }
}