<?php

//Codigo para depurar errores
/* ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); */

require_once __DIR__ . '/../dompdf/autoload.inc.php';
require_once __DIR__ . "/../app/models/Producto.php";

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'Helvetica');

$dompdf = new Dompdf($options);

$obj_producto = new Producto();
$productos = $obj_producto->getAll();

$encabezado = '
<div>
    <h1>Miscelanea Nelsy</h1>
    <p>Teléfono: 315 394 99 21</p>
    <p>Email: papelerianelsy@gmail.com</p>
    <p>Dirección: Carrera 10 # 42B-11, Barrio Santa Barbara</p>
    <p>Ciudad: Neiva - Huila</p>
</div>';

$html = $encabezado . '
    <h2 style="text-align:center;">Lista de productos</h2>
    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Código de barras</th>
                <th>Precio compra</th>
                <th>Precio venta</th>
                <th>Precio mayoreo</th>
                <th>Unidad</th>
                <th>Existencias</th>
                <th>Categoria</th>
                <th>Proveedor</th>
            </tr>
        </thead>
        <tbody>';

foreach ($productos as $producto) {
    $precio_compra = '$ ' . number_format($producto['precio_compra'], 2, ',', '.');
    $precio_venta = '$ ' . number_format($producto['precio_venta'], 2, ',', '.');
    $precio_mayoreo = '$ ' . number_format($producto['precio_mayoreo'], 2, ',', '.');

    $html .= "
            <tr>
                <td>{$producto['id']}</td>
                <td>{$producto['nombre_producto']}</td>
                <td>{$producto['codigo_barras']}</td>
                <td style='text-align: right;'>{$precio_compra}</td>
                <td style='text-align: right;'>{$precio_venta}</td>
                <td style='text-align: right;'>{$precio_mayoreo}</td>
                <td>{$producto['unidad']}</td>
                <td>{$producto['existencias']}</td>
                <td>" . $obj_producto->getNombreCategoria($producto['categoria_id']) . "</td>
                <td>" . $obj_producto->getNombreProveedor($producto['proveedor_id']) . "</td>
            </tr>";
}

$html .= '
        </tbody>
    </table>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();

$dompdf->stream("productos.pdf", array("Attachment" => 0));

?>
