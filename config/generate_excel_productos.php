<?php

require_once __DIR__ . "/../app/models/Producto.php";
require_once __DIR__ . '/../vendor/autoload.php'; // Asegúrate de apuntar a la ubicación correcta del autoload de composer.

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$obj_producto = new Producto();
$productos = $obj_producto->getAll();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Configura los encabezados de las columnas
$sheet->setCellValue('A1', 'Id');
$sheet->setCellValue('B1', 'Nombre');
$sheet->setCellValue('C1', 'Código de barras');
$sheet->setCellValue('D1', 'Precio compra');
$sheet->setCellValue('E1', 'Precio venta');
$sheet->setCellValue('F1', 'Precio mayoreo');
$sheet->setCellValue('G1', 'Unidad');
$sheet->setCellValue('H1', 'Existencias');
$sheet->setCellValue('I1', 'Categoria');
$sheet->setCellValue('J1', 'Proveedor');

// Inserta los datos en las filas siguientes
$row = 2;
foreach ($productos as $producto) {
    $sheet->setCellValue('A' . $row, $producto['id']);
    $sheet->setCellValue('B' . $row, $producto['nombre_producto']);
    $sheet->setCellValue('C' . $row, $producto['codigo_barras']);
    $sheet->setCellValue('D' . $row, $producto['precio_compra']);
    $sheet->setCellValue('E' . $row, $producto['precio_venta']);
    $sheet->setCellValue('F' . $row, $producto['precio_mayoreo']);
    $sheet->setCellValue('G' . $row, $producto['unidad']);
    $sheet->setCellValue('H' . $row, $producto['existencias']);
    $sheet->setCellValue('I' . $row, $obj_producto->getNombreCategoria($producto['categoria_id']));
    $sheet->setCellValue('J' . $row, $obj_producto->getNombreProveedor($producto['proveedor_id']));
    $row++;
}

// Guarda el archivo de Excel en el servidor y envíalo al navegador para su descarga
$writer = new Xlsx($spreadsheet);
$fileName = 'productos.xlsx';

// Configuración de la cabecera para forzar la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

$writer->save('php://output');
?>