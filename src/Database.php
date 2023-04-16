<?php

declare(strict_types=1);

namespace App;

// namespace App\Exception\StorageException;
// namespace App\Exception\ConfigurationException;

use App\Exception\StorageException;
use App\Exception\ConfigurationException;
use PDO;
use PDOException;
use Throwable;

class Database
{
    private PDO $conn;
    public function __construct(array $config)
    {
        try {
            $this->volidateConfig($config);
            $this->createConnection($config);


        } catch (PDOException $e) {
            throw new StorageException('connection error');
        }
    }

    public function createNote(array $data): void
    {
        try {
            $title = $this->conn->quote($data['title']);
            $description = $this->conn->quote($data['description']);
            $datetime = date('Y-m-d H:i:s');
            $query = "INSERT INTO notes(title,description,datetime) VALUES($title,$description, '$datetime')";
            $result = $this->conn->exec($query);
        } catch (Throwable $e) {
            throw new StorageException('Nie udalo sie utworzyc notatki', 400, $e);
        }
    }

    private function volidateConfig(array $config): void
    {
        if (empty($config['database']) || empty($config['user']) || empty($config['host'])) {
            throw new ConfigurationException('Problem z konfiguracja bazy dannych - skontaktuj sie z administratorem.');
        }
    }

    private function createConnection(array $config): void
    {
        $dsn = "mysql:dbname={$config['database']};host={$config['host']}";
        $this->conn = new PDO(
            $dsn,
            $config['user'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }

}