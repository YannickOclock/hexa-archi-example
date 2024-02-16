<?php
    namespace Domain\Auth\Tests\Adapters;

    use Domain\Auth\Entity\User;
    use Domain\Auth\Port\UserRepositoryInterface;

    class PdoUserRepository implements UserRepositoryInterface
    {
        private \PDO $pdo;

        public function __construct()
        {
            $this->pdo = new \PDO('mysql:host=localhost;dbname=archihexa', 'archihexa', 'archihexa', [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]);
        }

        public function findByEmail(string $email): User|null
        {
            $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
            $stmt->execute(['email' => $email]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($row === false) {
                return null;
            }
            return new User($row['email'], $row['password']);
        }
    }