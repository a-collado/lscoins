<?php
declare(strict_types=1);

namespace Salle\LSCoins\Model;

use DateTime;

final class User
{
    private int $id;
    private string $email;
    private string $password;
    private int $coins;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(
        string $email,
        string $password,
        int $coins,
    ) {
        $this->email = $email;
        $this->password = $password;
        $this->coins = $coins;
        $this->createdAt = new DateTime();
        $this->createdAt->format("Y-m-d H:i:s");
        $this->updatedAt = $this->createdAt;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setCoins(int $coins): int
    {
        $this->coins = $coins;
        return $this->coins;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function coins(): int
    {
        return $this->coins;
    }

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }

    public function updatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}