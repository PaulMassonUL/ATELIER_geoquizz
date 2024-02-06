<?php

namespace geoquizz\quiz\domain\manager;

interface JwtManagerInterface
{
    public function setIssuer(string $issuer): void;
    public function create(array $payload): string;
    public function validate(string $t): array;
}
