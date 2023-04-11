<?php
declare(strict_types=1);

namespace Salle\LSCoins\Model;

interface UserRepository
{
    public function save(User $user): void;
}