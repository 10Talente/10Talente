<?php

require_once 'config/DatabaseConfig.php';

class Database
{
    private $db;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        try {
            $dbFile = DatabaseConfig::DB_FILE;
    
            // Überprüfen, ob die Datenbankdatei existiert
            if (!file_exists($dbFile)) {
                // Datenbankdatei erstellen
                touch($dbFile);
                chmod($dbFile, 0777);
    
                // SQL-Datei mit Datenbankstruktur laden
                $sqlFile = DatabaseConfig::SQL_FILE;
                if (file_exists($sqlFile)) {
                    $sql = file_get_contents($sqlFile);
                    $this->db = new PDO('sqlite:' . $dbFile);
                    $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $this->db->exec($sql);
                } else {
                    die('SQL-Datei mit Datenbankstruktur nicht gefunden: ' . $sqlFile);
                }
            } else {
                // Verbindung zur vorhandenen Datenbankdatei herstellen
                $this->db = new PDO('sqlite:' . $dbFile);
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        } catch (PDOException $e) {
            die('Verbindung zur Datenbank fehlgeschlagen: ' . $e->getMessage());
        }
    }
    
    public function executeQuery($query, $params = [])
    {
        try {
            $statement = $this->db->prepare($query);
            $statement->execute($params);
            return $statement;
        } catch (PDOException $e) {
            die('Abfragefehler: ' . $e->getMessage());
        }
    }

    public function getUserByUsernameAndPassword($username, $password)
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':username', $username);
        $statement->execute();
    
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    
        if ($result && count($result) == 1) {
            $storedPasswordHash = $result[0]['password'];
    
            if (password_verify($password, $storedPasswordHash)) {
                return $result[0];
            }
        }
    
        return false;
    }

    public function getUserByUsername($username)
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':username', $username);
        $statement->execute();
    
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    
        if (count($results) == 1) {
            return $result[0];
        } elseif (count($result) === 0) {
            return false; // Benutzer nicht gefunden
        } else {
            throw new Exception("Mehrere Benutzer mit demselben Benutzernamen gefunden.");
        }
    }

    public function getUserById($userId)
    {
        $query = "SELECT * FROM users WHERE id = :user_id";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':user_id', $userId);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    
        if (count($result) === 1) {
            return $result[0];
        } elseif (count($result) === 0) {
            return false; // Benutzer nicht gefunden
        } else {
            throw new Exception("Mehrere Benutzer mit derselben ID gefunden.");
        }
    }
    
    public function createUser2($username, $password, $email)
    {
        // Überprüfen, ob der Benutzername bereits existiert
        $existingUser = $this->getUserByUsername($username);
    
        if ($existingUser) {
            // Benutzername bereits vergeben
            // Hier kannst du entsprechende Fehlermeldung anzeigen
            echo "Benutzername bereits vergeben.";
            return false;
        }
    
        // Benutzer in die Datenbank einfügen
        $query = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':username', $username);
        $statement->bindParam(':password', $password);
        $statement->bindParam(':email', $email);
        $statement->execute();
    
        return $this->db->lastInsertId();
    }

    public function createUser($username, $password, $email)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Füge den gehashten Wert in die Datenbank ein
        $query = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':username', $username);
        $statement->bindParam(':password', $hashedPassword);
        $statement->bindParam(':email', $email);
        $statement->execute();
    }
}
