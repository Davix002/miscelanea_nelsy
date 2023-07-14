<?php

require_once __DIR__ . "/../app/models/Categoria.php";
require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$obj_categoria = new Categoria();
$categorias = $obj_categoria->getAll();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Configura los encabezados de las columnas
$sheet->setCellValue('A1', 'Nombre de la categoría');
$sheet->setCellValue('B1', 'Descripción');

// Inserta los datos en las filas siguientes
$row = 2;
foreach ($categorias as $categoria) {
    $sheet->setCellValue('A' . $row, $categoria['nombre_categoria']);
    $sheet->setCellValue('B' . $row, $categoria['descripcion']);
    $row++;
}

// Guarda el archivo de Excel en el servidor y envíalo al navegador para su descarga
$writer = new Xlsx($spreadsheet);
$fileName = 'categorias.xlsx';

// Configuración de la cabecera para forzar la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
?>
