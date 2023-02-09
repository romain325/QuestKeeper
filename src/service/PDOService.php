<?php

class PDOService {
    private static string $host = '127.0.0.1';
    private static string $db   = 'QuestKeeper';
    private static string $user = 'postgres';
    private static string $pass = 'postgres';
    private static string $port = "5432";
    private static ?PDO $pdo = null;

    public static function getPDO(): PDO
    {
        if(PDOService::$pdo == null) {
            $options = [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $dsn = "pgsql:host=" . PDOService::$host . ";port=" . PDOService::$port . ";dbname=" . PDOService::$db;
            PDOService::$pdo = new \PDO($dsn, PDOService::$user, PDOService::$pass, $options);
        }
        return PDOService::$pdo;
    }

    public static function getUUID() : string {
        $pdo = PDOService::getPDO();
        return $pdo->query("SELECT uuid_generate_v4() id;")->fetch()["id"];
    }
}
