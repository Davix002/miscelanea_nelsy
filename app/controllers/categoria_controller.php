<?php

require_once __DIR__ . '/../models/Categoria.php';

class Categoria_controller
{
    private $model;

    public function __construct()
    {
        $this->model = new Categoria();
    }

    public function show($id)
    {
        $response = [];
        $response['data'] = $this->model->getById($id);
        echo json_encode($response);
    }

    public function store()
    {

        $error = [];
        $error = $this->validar_categoria($_POST);
        if (count($error) > 0) {
            http_response_code(422);
            $response['data']['error'] = $error;
            echo json_encode($response);
            return;
        }

        $result = $this->model->create($_POST);
        if ($result) {
            $response['data'] = "Categoria creada";
            echo json_encode($response);
            return;
        } else {
            http_response_code(409);
            $response['data']['error'] = "Error al crear la categoría";
            echo json_encode($response);
            return;
        }
    }

    public function update()
    {
        $error = [];
        $error = $this->validar_categoria($_POST);
        if (count($error) > 0) {
            http_response_code(422);
            $response['data']['error'] = $error;
            echo json_encode($response);
            return;
        }

        $result = $this->model->update($_POST);
        if ($result) {
            $response['data'] = "Categoría actualizada";
            echo json_encode($response);
            return;
        } else {
            http_response_code(409);
            $response['data']['error'] = "Error al actualizar la categoría";
            echo json_encode($response);
            return;
        }
    }

    public function destroy()
    {
        try {
            $result = $this->model->delete($_POST['id']);
            if ($result) {
                $response['data'] = "Categoría eliminada";
                echo json_encode($response);
            } else {
                http_response_code(409);
                $response['data']['error'] = "Error al eliminar la categoría";
                echo json_encode($response);
                return;
            }
        } catch (Exception $e) {
            http_response_code(409);
            $response['data']['error'] = $e->getMessage();
            echo json_encode($response);
        }
    }


    private function validar_categoria()
    {

        $error = [];
        if ($_POST['nombre_categoria'] == "") {
            $error['nombre_categoria'] = "El nombre no puede estar vacío.";
        }

        return $error;
    }

    public function doAction()
    {
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
            default:
                http_response_code(409);
                $response['data']['error'] = "No existe el recurso que intenta consultar.";
                echo json_encode($response);
                break;
        }
    }
}

$obj_categoria_controller = new Categoria_controller();
$obj_categoria_controller->doAction();
