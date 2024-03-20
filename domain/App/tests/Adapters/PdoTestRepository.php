<?php

namespace Domain\App\Tests\Adapters;

use PDO;

class PdoTestRepository
{
    private PDO $dbh;
    private static PdoTestRepository $instance;

    private function __construct()
    {
        try {
            $this->dbh = new PDO(
                "mysql:host={$_ENV['DB_TEST_HOST']};dbname={$_ENV['DB_TEST_NAME']};charset=utf8",
                $_ENV['DB_TEST_USER'],
                $_ENV['DB_TEST_PASSWORD'],
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)
            );
        } catch (\Exception $exception) {
            echo 'Erreur de connexion...<br>';
            echo $exception->getMessage() . '<br>';
            echo '<pre>';
            echo $exception->getTraceAsString();
            echo '</pre>';
            exit;
        }
    }
    public static function getPDO(): PDO
    {
        if (empty(self::$instance)) {
            self::$instance = new PdoTestRepository();
        }
        return self::$instance->dbh;
    }
}
