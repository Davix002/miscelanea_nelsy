<?php

require_once __DIR__.'/../models/Proveedor.php';

class Proveedor_controller {
    private $model;

    public function __construct() {
        $this->model = new Proveedor();
    }

    public function show($id) {
        $response = [];
        $response['data'] = $this->model->getById($id);
        echo json_encode($response);
    }

    public function store() {

        $error = [];
        $error = $this->validar_proveedor();
        if (count($error) > 0) {
            http_response_code(422);
            $response['data']['error'] = $error;
            echo json_encode($response);
            return;
        }

        $result = $this->model->create($_POST);
        if ($result) {
            $response['data'] = "Proveedor creado";
            echo json_encode($response);
            return;
        } else {
            http_response_code(409);
            $response['data']['error'] = "Error al crear proveedor";
            echo json_encode($response);
            return;
        }
    }

    public function update() {
        $error = [];
        $error = $this->validar_proveedor();
        if (count($error) > 0) {
            http_response_code(422);
            $response['data']['error'] = $error;
            echo json_encode($response);
            return;
        }
    
        $result = $this->model->update($_POST);
        if ($result) {
            $response['data'] = "Proveedor actualizado";
            echo json_encode($response);
            return;
        } else {
            http_response_code(409);
            $response['data']['error'] = "Error al actualizar proveedor";
            echo json_encode($response);
            return;
        }
    }

    public function destroy()
    {
        try {
            $result = $this->model->delete($_POST['id']);
            if ($result) {
                $response['data'] = "Proveedor eliminado";
                echo json_encode($response);
            } else {
                http_response_code(409);
                $response['data']['error'] = "Error al eliminar el proveedor";
                echo json_encode($response);
                return;
            }
        } catch (Exception $e) {
            http_response_code(409);
            $response['data']['error'] = $e->getMessage();
            echo json_encode($response);
        }
    }


    private function validar_proveedor() {
        $error = [];
    
        if ($_POST['nombre_proveedor'] == "") {
            $error['nombre_proveedor'] = "El campo no puede estar vacío.";
        }
    
        if ($_POST['direccion'] == "") {
            $error['direccion'] = "El campo no puede estar vacío.";
        }
    
        if ($_POST['telefono'] == "") {
            $error['telefono'] = "El campo no puede estar vacío.";
        }
    
        return $error;
    }
    
    public function getAll() {
        $response = [];
        $response['data'] = $this->model->getAll();
        echo json_encode($response);
    }

    public function doAction() {
        $action = $_REQUEST['action'];

        switch ($action) {
            case 'store':
                $this->store();
            break;
            case 'update':
                $this->update();
            break;
            case 'show':
                $this->show($_GET['id']);
            break;
            case 'destroy':
                $this->destroy();
            break;
            case 'getAll':
                $this->getAll();
            break;
            default:
                http_response_code(409);
                $response['data']['error'] = "No existe el recurso que intenta consultar.";
                echo json_encode($response);
            break;
        }
    }
}

$obj_proveedor_controller = new Proveedor_controller();
$obj_proveedor_controller->doAction();