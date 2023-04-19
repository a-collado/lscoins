<?php
declare(strict_types=1);

namespace Salle\LSCoins\Model;

interface UserRepository
{
    public function save(User $user): void;
    public function checkEmail(string $email): bool;
    public function login(string $email, string $password): bool;
}