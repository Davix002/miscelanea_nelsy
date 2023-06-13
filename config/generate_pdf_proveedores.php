<?php
require_once __DIR__ .'/../dompdf/autoload.inc.php';
require_once __DIR__ . "/../app/models/Proveedor.php";

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'Helvetica');

$dompdf = new Dompdf($options);

$obj_proveedor = new Proveedor();
$proveedores = $obj_proveedor->getAll();

$encabezado = '
<div>
    <h1>Miscelanea Nelsy</h1>
    <p>Teléfono: 315 394 99 21</p>
    <p>Email: papelerianelsy@gmail.com</p>
    <p>Dirección: Carrera 10 # 42B-11, Barrio Santa Barbara</p>
    <p>Ciudad: Neiva - Huila</p>
</div>';

$html = $encabezado . '
    <h2 style="text-align:center;">Lista de proveedores</h2>
    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre Proveedor</th>
                <th>NIT</th>
                <th>Dirección</th>
                <th>Teléfono</th>
                <th>Correo Electrónico</th>
            </tr>
        </thead>
        <tbody>';

foreach ($proveedores as $proveedor) {
    $html .= "
            <tr>
                <td>{$proveedor['id']}</td>
                <td>{$proveedor['nombre_proveedor']}</td>
                <td>{$proveedor['nit']}</td>
                <td>{$proveedor['direccion']}</td>
                <td>{$proveedor['telefono']}</td>
                <td>{$proveedor['correo_electronico']}</td>
            </tr>";
}

$html .= '
        </tbody>
    </table>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("proveedores.pdf", array("Attachment" => 0));
