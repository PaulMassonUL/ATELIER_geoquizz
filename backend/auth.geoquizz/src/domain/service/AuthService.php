<?php

namespace geoquizz\auth\domain\service;

use geoquizz\auth\domain\dto\CredentialsDTO;
use geoquizz\auth\domain\dto\TokenDTO;
use geoquizz\auth\domain\dto\UserDTO;
use geoquizz\auth\domain\entities\User;
use geoquizz\auth\domain\manager\JwtManagerInterface;
use geoquizz\auth\domain\manager\JwtManagerExpiredTokenException;
use geoquizz\auth\domain\manager\JwtManagerInvalidTokenException;
use geoquizz\auth\domain\provider\AuthProviderInvalidCredentialsException;
use geoquizz\auth\domain\provider\AuthProviderInvalidTokenException;
use geoquizz\auth\domain\provider\AuthProviderInterface;
use Psr\Log\LoggerInterface;

class AuthService implements AuthServiceInterface
{
    private JwtManagerInterface $jwtManager;
    private AuthProviderInterface $authProvider;
    private LoggerInterface $logger;

    public function __construct(JwtManagerInterface $jwtManager, AuthProviderInterface $authProvider, LoggerInterface $logger)
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
            throw new AuthServiceCredentialsException("Invalid email or password.");
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

    //obtenir le username avec l'email du User
    public function getUsername(string $email): string
    {
        return User::where('email', $email)->first()->username;
    }
}
