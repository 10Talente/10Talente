<?php

require_once 'core/TemplateEngine.php';

class User
{
    private $database;
    private $templateEngine;
    private $userdata;

    public function __construct($db)
    {
        $this->database = $db;
        $this->templateEngine = new TemplateEngine('templates');
 
        $this->check_login();
    }

    private function check_login()
    {
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $result = $this->database->GetUserById($userId);

            if ($result) {
                $this->userdata = $result;
                return true;
            } 
        } elseif (!isset($_SESSION['user_id']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
    
            // Benutzer aus der Datenbank abrufen
            $result = $this->database->getUserByUsernameAndPassword($username, $password);

            if ($result) {
                // Benutzer erfolgreich eingeloggt
                // Benutzer-ID in der Session speichern
                $_SESSION['user_id'] = $result['id'];
    
                // Hier kannst du weitere Aktionen durchführen, z. B. Weiterleitung auf eine andere Seite
                header('Location: index.php');
                exit();
            } else {
                // Benutzername oder Passwort ist falsch
                // Hier kannst du entsprechende Fehlermeldung anzeigen
                $_SESSION['hint'] = "Ungültiger Benutzername oder Passwort";
            }
        }
        $this->userdata = null;
        $_GET['route'] = 'user/login';
        return false;
    }
/*        if (isset($_SESSION['user_id'])) {
            // Benutzer ist angemeldet, überprüfe den last_login-Timestamp
            $userId = $_SESSION['user_id'];
            $maxIdleTime = 3600; // Maximale Zeit in Sekunden, in der der Benutzer inaktiv sein kann (hier: 1 Stunde)

            $query = "SELECT last_login FROM users WHERE id = :user_id";
            $statement = $this->db->prepare($query);
            $statement->bindParam(':user_id', $userId);
            $statement->execute();

            $result = $statement->fetch(PDO::FETCH_ASSOC);

            if ($result && !empty($result['last_login'])) {
                $lastLoginTimestamp = strtotime($result['last_login']);
                $currentTime = time();

                if ($currentTime - $lastLoginTimestamp > $maxIdleTime) {
                    // Benutzer war zu lange inaktiv, führe die Abmeldung durch
                    session_unset();
                    session_destroy();
                    $_GET['route'] = 'user/login';
                } else {
                    // Aktualisiere den last_login-Timestamp
                    $currentTimestamp = date('Y-m-d H:i:s');

                    $query = "UPDATE users SET last_login = :last_login WHERE id = :user_id";
                    $statement = $this->db->prepare($query);
                    $statement->bindParam(':last_login', $currentTimestamp);
                    $statement->bindParam(':user_id', $userId);
                    $statement->execute();
                }
            }
        } elif (!isset($_SESSION['user_id']) and $_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
    
            // Benutzer aus der Datenbank abrufen
            $user = $this->database->getUserByUsernameAndPassword($username, $password);
    
            if ($user) {
                // Benutzer erfolgreich eingeloggt
                // Benutzer-ID in der Session speichern
                $_SESSION['user_id'] = $user['id'];
    
                // Hier kannst du weitere Aktionen durchführen, z. B. Weiterleitung auf eine andere Seite
                header('Location: index.php');
                exit();
            } else {
                // Benutzername oder Passwort ist falsch
                // Hier kannst du entsprechende Fehlermeldung anzeigen
                echo "Ungültiger Benutzername oder Passwort.";
            }
        } else {
            // Benutzer ist nicht angemeldet, führe die entsprechende Logik aus
            $_GET['route'] = 'user/login';
        }
    }
*/
    public function info()
    {
        
        if ($this->userdata == null) $info = 'no user';
        else $info = $this->userdata;
        print_r($info);
        return $info;
    }

    public function login()
    {
        $data = [
            'hint' => '<h3>' . $_SESSION['hint'] . '</h3>'
        ];
        $content = $this->templateEngine->render('login', $data);
        return $content;
    }
}