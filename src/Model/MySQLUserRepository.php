<?php
declare(strict_types=1);

namespace Salle\LSCoins\Model;

use PDO;
use Salle\LSCoins\Model\User;
use Salle\LSCoins\Model\UserRepository;

final class MysqlUserRepository implements UserRepository
{
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private PDOSingleton $database;

    public function __construct(PDOSingleton $database)
    {
        $this->database = $database;
    }

    public function save(User $user): void
    {
        $query = <<<'QUERY'
        INSERT INTO users(email, password, coins, createdAt, updatedAt)
        VALUES(:email, :password, :coins, :created_at, :updated_at)
        QUERY;
        $statement = $this->database->connection()->prepare($query);

        $email = $user->email();
        $password = $user->password();
        $coins = $user->coins();
        $createdAt = $user->createdAt()->format(self::DATE_FORMAT);
        $updatedAt = $user->updatedAt()->format(self::DATE_FORMAT);

        $statement->bindParam('email', $email, PDO::PARAM_STR);
        $statement->bindParam('password', $password, PDO::PARAM_STR);
        $statement->bindParam('coins', $coins, PDO::PARAM_INT);
        $statement->bindParam('created_at', $createdAt, PDO::PARAM_STR);
        $statement->bindParam('updated_at', $updatedAt, PDO::PARAM_STR);

        $statement->execute();
    }
}