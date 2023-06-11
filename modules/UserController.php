<?php

require_once 'core/TemplateEngine.php';

class UserController
{
    private $database;
    private $templateEngine;

    public function __construct($db)
    {
        $this->database = $db;
        $this->templateEngine = new TemplateEngine('templates');
    }

    public function profile()
    {
        // Daten für das Template
        $data = [
            'username' => 'JohnDoe',
            'email' => 'johndoe@example.com'
        ];

        // Template rendern und Ergebnis anzeigen
        $content = $this->templateEngine->render('profile', $data);
        echo $content;
    }

    public function login()
    {
        $content = $this->templateEngine->render('login', []);
        return $content;
/*        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
            // Anzeige des Login-Formulars
            // Hier kannst du das HTML-Formular für den Login anzeigen
            echo '<h2 class="text-center">Login</h2>';
            echo '<form method="POST" action="index.php?route=user/login">';
            echo '  <div class="form-group">';
            echo '    <label for="username">Username</label>';
            echo '    <input type="text" class="form-control" id="username" name="username" required>';
            echo '  </div>';
            echo '  <div class="form-group">';
            echo '    <label for="password">Password</label>';
            echo '    <input type="password" class="form-control" id="password" name="password" required>';
            echo '  </div>';
            echo '  <div class="text-center">';
            echo '    <button type="submit" class="btn btn-primary">Login</button>';
            echo '    <a href="index.php?route=user/register">Register</a>';
            echo '  </div>';
            echo '</form>';
        }
    */
    }
    
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];
    
            // Überprüfen, ob der Benutzername bereits existiert
            $existingUser = $this->database->getUserByUsername($username);
    
            if ($existingUser) {
                // Benutzername bereits vergeben
                // Hier kannst du entsprechende Fehlermeldung anzeigen
                echo "Benutzername bereits vergeben.";
            } else {
                // Neuen Benutzer in die Datenbank einfügen
                $userId = $this->database->createUser($username, $password, $email);
    
                if ($userId) {
                    // Benutzer erfolgreich registriert
                    // Hier kannst du weitere Aktionen durchführen, z. B. Weiterleitung auf eine andere Seite
                    echo "Registrierung erfolgreich!";
                } else {
                    // Registrierung fehlgeschlagen
                    // Hier kannst du entsprechende Fehlermeldung anzeigen
                    echo "Registrierung fehlgeschlagen.";
                }
            }
        } else {
            // Anzeige des Registrierungsformulars
            // Hier kannst du das HTML-Formular für die Registrierung anzeigen
            echo '<form method="POST" action="index.php?route=user/register">';
            echo '<input type="text" name="username" placeholder="Benutzername" required>';
            echo '<input type="password" name="password" placeholder="Passwort" required>';
            echo '<input type="email" name="email" placeholder="E-Mail" required>';
            echo '<button type="submit">Registrieren</button>';
            echo '</form>';
        }
    }
}
