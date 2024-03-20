<?php

namespace App\Pdo;

use Domain\Auth\Entity\User;
use Domain\Auth\Port\UserRepositoryInterface;
use PDO;
use PDOException;

class PdoUserRepository implements UserRepositoryInterface
{
    private PDO $pdo;
    public function __construct()
    {
        $this->pdo = PdoRepository::getPDO();
    }

    private function getDomainRolesFromDb($dbRoles)
    {
        $dbRoles = json_decode($dbRoles);
        $domainRoles = [];
        foreach($dbRoles as $dbRole) {
            if($dbRole === "ROLE_AUTHOR") {
                $domainRoles[] = "author";
            }
            if($dbRole === "ROLE_PUBLISHER") {
                $domainRoles[] = "publisher";
            }
        }
        return $domainRoles;
    }

    public function getDomainRolesForDb($domainRoles)
    {
        $dbRoles = [];
        foreach($domainRoles as $domainRole) {
            if($domainRole === "author") {
                $dbRoles[] = "ROLE_AUTHOR";
            }
            if($domainRole === "publisher") {
                $dbRoles[] = "ROLE_PUBLISHER";
            }
        }
        return $dbRoles;
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
            $this->getDomainRolesFromDb($row['roles'])
        );
    }

    public function save(User $user): bool
    {
        try {
            $stmt = $this->pdo->prepare('INSERT INTO users (uuid, email, password, roles, created_at) VALUES (:uuid, :email, :password, :roles, NOW())');
            $stmt->execute([
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'roles' => json_encode($this->getDomainRolesForDb($user->getRoles())),
                'uuid' => $user->getUuid()
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}
