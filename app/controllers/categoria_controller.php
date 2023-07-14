<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

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
        $error = $this->validar_categoria();
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
        $error = $this->validar_categoria();
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
                        'nombre_categoria' => $row[0],
                        'descripcion' => $row[1],
                    ];

                    // Verificar si la categoria existe
                    $existingCategoria = $this->model->getByName($data['nombre_categoria']);

                    // Si la categoria existe, actualizarlo. De lo contrario, crear uno nuevo.
                    if ($existingCategoria) {
                        $data['id'] = $existingCategoria['id'];  // Asigna el id de la categoria existente a $data
                        $this->model->update($data);
                    } else {
                        $this->model->create($data);
                    }
                }

                // Enviar respuesta exitosa
                echo json_encode(['success' => true, 'message' => 'Categorias importados/actualizados exitosamente']);
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

$obj_categoria_controller = new Categoria_controller();
$obj_categoria_controller->doAction();
