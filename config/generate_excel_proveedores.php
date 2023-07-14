<?php

require_once __DIR__ . "/../app/models/Proveedor.php";
require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$obj_proveedor = new Proveedor();
$proveedores = $obj_proveedor->getAll();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Configura los encabezados de las columnas
$sheet->setCellValue('A1', 'Nombre Proveedor');
$sheet->setCellValue('B1', 'NIT');
$sheet->setCellValue('C1', 'Dirección');
$sheet->setCellValue('D1', 'Teléfono');
$sheet->setCellValue('E1', 'Correo Electrónico');

// Inserta los datos en las filas siguientes
$row = 2;
foreach ($proveedores as $proveedor) {
    $sheet->setCellValue('A' . $row, $proveedor['nombre_proveedor']);
    $sheet->setCellValue('B' . $row, $proveedor['nit']);
    $sheet->setCellValue('C' . $row, $proveedor['direccion']);
    $sheet->setCellValue('D' . $row, $proveedor['telefono']);
    $sheet->setCellValue('E' . $row, $proveedor['correo_electronico']);
    $row++;
}

foreach (range('A', $spreadsheet->getActiveSheet()->getHighestDataColumn()) as $col) {
    $spreadsheet->getActiveSheet()
                ->getColumnDimension($col)
                ->setAutoSize(true);
}

// Guarda el archivo de Excel en el servidor y envíalo al navegador para su descarga
$writer = new Xlsx($spreadsheet);
$fileName = 'proveedores.xlsx';

// Configuración de la cabecera para forzar la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
?>
