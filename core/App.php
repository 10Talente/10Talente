<?php

require_once 'core/Database.php';
require_once 'core/User.php';

class App
{
    private $database;
    private $user;
    private $content;

    public function __construct()
    {
        $this->database = new Database();                           // Datenbank öffnen
        //$this->database->createUser('test', 'test', 'info@testmail');
        $this->user = new User($this->database);                    // Benutzer überprüfen
        $this->user->info();
    }
    
    public function run()
    {
        // Routen aufrufen und Anfragen verarbeiten
        $route = $_GET['route'] ?? ''; // Hier wird die Route aus der URL abgerufen (z.B. index.php?route=user/profile)

        switch ($route) {
            case 'user/profile':
                $this->callController('User', 'profile');
                break;
            case 'user/register':
                $this->callController('User', 'register');
                break;
            case 'user/login':
                $this->content = $this->user->login();
                break;
            case 'user/logout':
                $this->callController('User', 'logout');
                break;
            default:
                // Standardroute oder Fehlerbehandlung
                echo "unknown route: " . $route;
                break;
        }

        echo $this->content;
    }

    private function callController($controllerName, $actionName)
    {
        $controllerClassName = $controllerName . 'Controller';
        $controllerFileName = 'modules/' . $controllerClassName . '.php';

        if (file_exists($controllerFileName)) {
            require_once $controllerFileName;

            if (class_exists($controllerClassName)) {
                $controller = new $controllerClassName($this->database);

                if (method_exists($controller, $actionName)) {
                    $this->content = $controller->$actionName();
                } else {
                    // Aktion existiert nicht, Fehlerbehandlung
                    echo "unknown controller class action: " . $controllerClassName . " -> " . $actionName;
                }
            } else {
                // Controllerklasse existiert nicht, Fehlerbehandlung
                echo "unknown controller class: " . $controllerClassName;
            }
        } else {
            // Controllerdatei existiert nicht, Fehlerbehandlung
            echo "unknown controller: " . $controllerFileName;
        }
    }
}
