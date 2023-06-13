<?php
require_once __DIR__ .'/../dompdf/autoload.inc.php';
require_once __DIR__ . "/../app/models/Categoria.php";

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'Helvetica');

$dompdf = new Dompdf($options);

$obj_categoria = new Categoria();
$categorias = $obj_categoria->getAll();

$encabezado = '
<div>
    <h1>Miscelanea Nelsy</h1>
    <p>Teléfono: 315 394 99 21</p>
    <p>Email: papelerianelsy@gmail.com</p>
    <p>Dirección: Carrera 10 # 42B-11, Barrio Santa Barbara</p>
    <p>Ciudad: Neiva - Huila</p>
</div>';

$html = $encabezado . '
    <h2 style="text-align:center;">Lista de categorías</h2>
    <table border="1" cellpadding="5" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre de la categoría</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>';

foreach ($categorias as $categoria) {
    $html .= "
            <tr>
                <td>{$categoria['id']}</td>
                <td>{$categoria['nombre_categoria']}</td>
                <td>{$categoria['descripcion']}</td>
            </tr>";
}

$html .= '
        </tbody>
    </table>';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("categorias.pdf", array("Attachment" => 0));