<?php

require_once '../models/User.php';
require_once "../../config/conexion.php";

class UserController {

    public function __construct() {
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $response = [];

            $userModel = new User();
            $user = $userModel->findByEmail($_POST['email']);

            // Comprueba que la contraseña coincida con un hash
            if ($user && password_verify($_POST['password'], $user['pass'])) {
                session_start();
                $_SESSION['user'] = $user['id'];
                $response['data'] = true;
            } else {
                http_response_code(409);
                $response['data']['error'] = "Correo electrónico o contraseña no válidos";
            }
            echo json_encode($response);
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $response = [];
            $error = [];

            if($_POST['nombres'] == ""){
                $error['nombres'] = "El Nombre no puede estar vacío.";
            }

            if($_POST['apellidos'] == ""){
                $error['apellidos'] = "El Apellido no puede estar vacío.";
            }

            if($_POST['email'] == ""){
                $error['email'] = "El Correo no puede estar vacío.";
            }

            if($_POST['password'] == ""){
                $error['password'] = "La Contraseña no puede estar vacía.";
            }

            if (count($error) > 0) {
                http_response_code(422);
                $response['data']['error'] = $error;
                echo json_encode($response);
                return;
            }

            $userModel = new User();
            $user = $userModel->findByEmail($_POST['email']);

            if ($user) {
                http_response_code(409);
                $response['data']['error'] = "correo electrónico ya registrado";
            } else {
                $userModel->create($_POST);
                $response['data'] = "Registro exitoso.";
            }
            echo json_encode($response);
        }
    }

    public function doAction() {
        $action = $_REQUEST['action'];

        switch ($action) {
            case 'login':
                $this->login();
                break;

            case 'register':
                $this->register();
                break;

            default:
                header('Location: index.php');
                break;
        }
    }
}

$oUserController = new UserController();
$oUserController->doAction();