<?php

require_once __DIR__.'/../models/Producto.php';

class Producto_controller{
    private $model;

    public function __construct() {
        $this->model = new Producto();
    }

    public function show($id) {
        $response = [];
        $response['data'] = $this->model->getById($id);
        echo json_encode($response);
    }

    public function store() {

        $error = [];
        $error = $this->validar_producto($_POST);
        if (count($error) > 0) {
            http_response_code(422);
            $response['data']['error'] = $error;
            echo json_encode($response);
            return;
        }

        $result = $this->model->create($_POST);
        if ($result) {
            $response['data'] = "Producto creado";
            echo json_encode($response);
            return;
        } else {
            http_response_code(409);
            $response['data']['error'] = "Error al crear el producto";
            echo json_encode($response);
            return;
        }
    }

    public function update() {
        $error = [];
        $error = $this->validar_producto($_POST);
        if (count($error) > 0) {
            http_response_code(422);
            $response['data']['error'] = $error;
            echo json_encode($response);
            return;
        }
    
        $result = $this->model->update($_POST);
        if ($result) {
            $response['data'] = "Producto actualizado";
            echo json_encode($response);
            return;
        } else {
            http_response_code(409);
            $response['data']['error'] = "Error al actualizar el producto";
            echo json_encode($response);
            return;
        }
    }

    public function destroy() {
        $result = $this->model->delete($_POST['id']);
        if ($result) {
            $response['data'] = "Producto eliminado";
            echo json_encode($response);
            return;
        } else {
            http_response_code(409);
            $response['data']['error'] = "Error al eliminar el producto";
            echo json_encode($response);
            return;
        }
    }

    private function validar_producto(){
        $error = [];
        if($_POST['nombre_producto'] == ""){
            $error['nombre_producto'] = "El nombre no puede estar vacío.";
        }
    
        if($_POST['precio_compra'] == ""){
            $error['precio_compra'] = "El precio de compra no puede estar vacío.";
        }
    
        if($_POST['precio_venta'] == ""){
            $error['precio_venta'] = "El precio de venta no puede estar vacío.";
        }
    
        if($_POST['precio_mayoreo'] == ""){
            $error['precio_mayoreo'] = "El precio de mayoreo no puede estar vacío.";
        }
    
        if($_POST['unidad'] == ""){
            $error['unidad'] = "El campo de unidad no puede estar vacía.";
        }
    
        if($_POST['existencias'] == ""){
            $error['existencias'] = "El campo de existencias no puede estar vacío.";
        }
    
        if($_POST['proveedor_id'] == ""){
            $error['proveedor_id'] = "El proveedor no puede estar vacío.";
        }
    
        if($_POST['categoria_id'] == ""){
            $error['categoria_id'] = "La categoría no puede estar vacía.";
        }
    
        if($_POST['codigo_barras'] == ""){
            $error['codigo_barras'] = "El código de barras no puede estar vacío.";
        }
    
        return $error;
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
            default:
                http_response_code(409);
                $response['data']['error'] = "No existe el recurso que intenta consultar.";
                echo json_encode($response);
            break;
        }
    }
}

$obj_producto_controller = new Producto_controller();
$obj_producto_controller->doAction();