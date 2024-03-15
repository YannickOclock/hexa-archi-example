<?php

namespace App\Pdo;

use Domain\Auth\Entity\User;
use Domain\Auth\Port\UserRepositoryInterface;
use PDO;

class PdoUserRepository implements UserRepositoryInterface
{
    private PDO $pdo;
    public function __construct()
    {
        $this->pdo = PdoRepository::getPDO();
    }

    public function findByEmail(string $email): User|null
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return null;
        }
        return new User(
            $row['email'], 
            $row['password'], 
            json_decode($row['roles']));
    }
}