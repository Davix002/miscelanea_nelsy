<div class=" row d-flex flex-column align-items-center justify-content-start mt-5 w-100 text-center">
    <div class="col-6">
        <h2>Buscar producto</h2>
        <div class="input-group mb-3">
            <input type="search" class="form-control" id="buscador" oninput="filterProducts()" placeholder="Escribe el nombre del producto">
        </div>
        <div class="form-group d-flex flex-column align-items-center">
            <label class="mb-1" for="start">Busqueda por voz</label>
            <button class="btn btn-outline-secondary btn-lg w-auto mb-3" type="button" id="start" onclick="iniciarGrabacion()">🎙️</button>
        </div>
    </div>
    <table class="table table-hover" id="productTable">
        <thead>
            <tr style='display:none;'>
                <th scope="col">Id</th>
                <th scope="col">Nombre</th>
                <th scope="col">Precio compra</th>
                <th scope="col">Precio venta</th>
                <th scope="col">Precio mayoreo</th>
                <th scope="col">Unidad</th>
                <th scope="col">Existencias</th>
                <th scope="col">Categoria</th>
                <th scope="col">Proveedor</th>
            </tr>
        </thead>

        <tbody>

            <?php

            require_once __DIR__ . "/../../models/Producto.php";
            $obj_producto = new Producto();
            $productos = $obj_producto->getAll();
            for ($i = 0; $i < count($productos); $i++) {
                echo "<tr style='display:none;'>";
                echo "<td>" . $productos[$i]['id'] . "</td>";
                echo "<td>" . $productos[$i]['nombre_producto'] . "</td>";
                echo "<td class='moneda'>" . $productos[$i]['precio_compra'] . "</td>";
                echo "<td class='moneda'>" . $productos[$i]['precio_venta'] . "</td>";
                echo "<td class='moneda'>" . $productos[$i]['precio_mayoreo'] . "</td>";
                echo "<td>" . $productos[$i]['unidad'] . "</td>";
                echo "<td>" . $productos[$i]['existencias'] . "</td>";
                echo "<td>" . $obj_producto->getNombreCategoria($productos[$i]['categoria_id']) . "</td>";
                echo "<td>" . $obj_producto->getNombreProveedor($productos[$i]['proveedor_id']) . "</td>";
                echo "</tr>";
            }
            ?>

        </tbody>
    </table>
</div>
<script src="public/js/panel/buscador.js"></script>