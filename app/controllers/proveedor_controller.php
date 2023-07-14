<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__.'/../models/Proveedor.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

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
            $error['nombre_proveedor'] = "El campo nombre proveedor no puede estar vacío.";
        }

        if ($_POST['nit'] == "") {
            $error['nit'] = "El campo nit no puede estar vacío.";
        }
    
        if ($_POST['telefono'] == "") {
            $error['telefono'] = "El campo telefono no puede estar vacío.";
        }
    
        return $error;
    }
    
    public function getAll() {
        $response = [];
        $response['data'] = $this->model->getAll();
        echo json_encode($response);
    }

    public function upload()
    {
        if (isset($_FILES['excelFile'])) {
            $file = $_FILES['excelFile']['tmp_name'];

            try {
                $spreadsheet = IOFactory::load($file);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();

                foreach ($rows as $index => $row) {
                    if ($index == 0) continue; // Ignorar encabezados

                    $data = [
                        'nombre_proveedor' => $row[0],
                        'nit' => $row[1],
                        'direccion' => $row[2],
                        'telefono' => $row[3],
                        'correo_electronico' => $row[4],
                    ];

                    // Verificar si el proveedor existe
                    $existingProveedor = $this->model->getByNit($data['nit']);

                    // Si el proveedor existe, actualizarlo. De lo contrario, crear uno nuevo.
                    if ($existingProveedor) {
                        $data['id'] = $existingProveedor['id'];  // Asigna el id del proveedor existente a $data
                        $this->model->update($data);
                    } else {
                        $this->model->create($data);
                    }
                }

                // Enviar respuesta exitosa
                echo json_encode(['success' => true, 'message' => 'Proveedores importados/actualizados exitosamente']);
            } catch (Exception $e) {
                // Manejar excepciones
                http_response_code(500);
                echo json_encode(['error' => 'Error al procesar el archivo']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'No se recibió ningún archivo']);
        }
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
            case 'upload':
                $this->upload();
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