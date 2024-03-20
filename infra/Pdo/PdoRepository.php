<?php

namespace App\Pdo;

use PDO;

class PdoRepository
{
    private PDO $dbh;
    private static PdoRepository $instance;

    private function __construct()
    {
        try {
            $this->dbh = new PDO(
                "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8",
                $_ENV['DB_USER'],
                $_ENV['DB_PASSWORD'],
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
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
            self::$instance = new PdoRepository();
        }
        return self::$instance->dbh;
    }
}
