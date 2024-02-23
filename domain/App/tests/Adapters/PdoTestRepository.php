<?php

namespace Domain\App\Tests\Adapters;

use PDO;

class PdoTestRepository
{
    private PDO $dbh;
    private static PdoTestRepository $instance;

    private function __construct()
    {
        $configData = parse_ini_file(__DIR__ . '/../../../../app/pdo.ini');
        try {
            $this->dbh = new PDO(
                "mysql:host={$configData['DB_HOST']};dbname={$configData['DB_NAME']};charset=utf8",
                $configData['DB_USERNAME'],
                $configData['DB_PASSWORD'],
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