<?php

declare(strict_types=1);

namespace App;



use App\Exception\StorageException;
use App\Exception\ConfigurationException;
use App\Exception\NotFoundException;
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
            $query = "INSERT INTO notes(title,description,datatime) VALUES($title,$description, '$datetime')";
            $result = $this->conn->exec($query);
        } catch (Throwable $e) {
            throw new StorageException('Nie udalo sie utworzyc notatki', 400, $e);
        }
    }


    public function getNote(int $id): array
    {
        try {
            $query = "SELECT * FROM notes WHERE id=$id";
            $result = $this->conn->query($query);
            $note = $result->fetch(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            throw new StorageException('Nie udalo sie pobrac notatki.', 400, $e);
        }

        if (!$note) {
            throw new NotFoundException("Notatka o id: $id nie istnieje.");
        }
        return $note;
    }


    public function editNotes(int $id, array $data): void {
        try {
            // $id = $this->conn->quote($data['id']);
            $title = $this->conn->quote($data['title']);
            $description = $this->conn->quote($data['description']);

            $query = "UPDATE notes SET title = $title, description = $description WHERE id = $id";
            $this->conn->exec($query);

        } catch (Throwable $e) {
            throw new StorageException('Nie udalo sie edytowac danych o notatkach', 400, $e);
        }
    }



    public function getNotes(): array
    {
        try {
            $notes = [];
            $query = "SELECT id,title,datatime FROM notes";
            $result = $this->conn->query($query, PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $notes[] = $row;
            }
            return $notes;
        } catch (Throwable $e) {
            throw new StorageException('Nie udalo sie pobrac danych o notatkach', 400, $e);
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