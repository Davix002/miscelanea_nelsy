<div class="row">
    <!-- Button trigger modal -->

    <button type="button" class="col-2 btn btn-primary me-2 my-3 " onclick="fnCreateRow()">
        Nuevo producto
    </button>

    <button type="button" class="col-3 btn btn-secondary my-3" onclick="printProductsToPDF()">
        Imprimir lista de productos en PDF
    </button>

    <div class="col-4 my-3">
        <input type="search" class="form-control" id="searchProduct" placeholder="Buscar producto" onkeyup="filterProducts()">
    </div>

    <table class="table table-hover table-sm" id="productTable">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nombre</th>
                <th scope="col">Código de Barras</th>
                <th scope="col">Precio compra</th>
                <th scope="col">Precio venta</th>
                <th scope="col">Precio mayoreo</th>
                <th scope="col">Unidad</th>
                <th scope="col">Existencias</th>
                <th scope="col">Categoria</th>
                <th scope="col">Proveedor</th>
                <th scope="col">Editar</th>
                <th scope="col">Eliminar</th>
            </tr>
        </thead>

        <tbody>

            <?php

            require_once __DIR__ . "/../../models/Producto.php";
            $obj_producto = new Producto();
            $productos = $obj_producto->getAll();

            require_once __DIR__ . "/../../models/Categoria.php";
            $obj_categoria = new Categoria();

            require_once __DIR__ . "/../../models/Proveedor.php";
            $obj_proveedor = new Proveedor();

            for ($i = 0; $i < count($productos); $i++) {

                echo "<tr id='productRow_" . $productos[$i]['id'] . "'>";
                echo "<form id='form_" . $productos[$i]['id'] . "' method='post'>";
                echo "<td>" . $productos[$i]['id'] . "</td>";
                echo "<td><input type='text' name='nombre_producto' id='nombre_producto' class='form-control editable' value='" . $productos[$i]['nombre_producto'] . "' readonly></td>";
                echo "<td><input type='text' name='codigo_barras' id='codigo_barras' class='form-control editable' value='" . $productos[$i]['codigo_barras'] . "' readonly></td>";
                echo "<td><input type='number' name='precio_compra' id='precio_compra' class='form-control editable moneda' value='" . $productos[$i]['precio_compra'] . "' readonly></td>";
                echo "<td><input type='number' name='precio_venta' id='precio_venta' class='form-control editable moneda' value='" . $productos[$i]['precio_venta'] . "' readonly></td>";
                echo "<td><input type='number' name='precio_mayoreo' id='precio_mayoreo' class='form-control editable moneda' value='" . $productos[$i]['precio_mayoreo'] . "' readonly></td>";
                echo "<td><input type='text' name='unidad' id='unidad' class='form-control editable' value='" . $productos[$i]['unidad'] . "' readonly></td>";
                echo "<td><input type='number' name='existencias' id='existencias' class='form-control editable' value='" . $productos[$i]['existencias'] . "' readonly></td>";

                $categoria_actual = $obj_producto->getNombreCategoria($productos[$i]['categoria_id']);
                echo "<td><select name='categoria_id' id='categoria_id' class='form-select editable ' disabled>";
                echo "<option value=''></option>";
                foreach ($obj_categoria->getAll() as $categoria) {
                    $selected = $categoria['nombre_categoria'] == $categoria_actual ? "selected" : "";
                    echo "<option value='" . $categoria['id'] . "' $selected>" . $categoria['nombre_categoria'] . "</option>";
                }
                echo "</select></td>";

                $proveedor_actual = $obj_producto->getNombreProveedor($productos[$i]['proveedor_id']);
                echo "<td><select name='proveedor_id' id='proveedor_id' class='form-select editable ' disabled>";
                echo "<option value=''></option>";
                foreach ($obj_proveedor->getAll() as $proveedor) {
                    $selected = $proveedor['nombre_proveedor'] == $proveedor_actual ? "selected" : "";
                    echo "<option value='" . $proveedor['id'] . "' $selected>" . $proveedor['nombre_proveedor'] . "</option>";
                }
                echo "</select></td>";

                echo "<td>
                <button type='button' 
                        onclick='enableEditing(" . $productos[$i]['id'] . ")' class='btn btn-primary editBtn'>
                        <img src='../miscelanea_nelsy/public/images/pencil.svg'>
                </button>
                <button type='button' 
                        onclick='fnCreateUpdate(\"UPDATE\"," . $productos[$i]['id'] . ")' class='btn btn-success d-none saveBtn'>
                        <img src='../miscelanea_nelsy/public/images/check.svg'>
                </button>
                </td>";

                echo "
                <td>
                    <button type='button' 
                            onclick='fnDelete(" . $productos[$i]['id'] . ")' class='btn btn-danger deleteBtn'>
                            <img src='../miscelanea_nelsy/public/images/trash.svg'>
                    </button>
                    <button type='button' 
                            onclick='fnCancel(" . $productos[$i]['id'] . ")' class='btn btn-dark d-none cancelBtn'>
                            <img src='../miscelanea_nelsy/public/images/cancel.svg'>
                    </button>
                </td>";
                echo "</form>";
                echo "</tr>";
            }

            //fila vacia

            echo "<tr id='productRow_new' class='d-none'>";
            echo "<form id='form_new' method='post'>";
            echo "<td></td>";
            echo "<td><input type='text' name='nombre_producto' id='nombre_producto' class='form-control editable'></td>";
            echo "<td><input type='text' name='codigo_barras' id='codigo_barras' class='form-control editable'></td>";
            echo "<td><input type='number' name='precio_compra' id='precio_compra' class='form-control editable moneda'></td>";
            echo "<td><input type='number' name='precio_venta' id='precio_venta' class='form-control editable moneda'></td>";
            echo "<td><input type='number' name='precio_mayoreo' id='precio_mayoreo' class='form-control editable moneda'></td>";
            echo "<td><input type='text' name='unidad' id='unidad' class='form-control editable'></td>";
            echo "<td><input type='number' name='existencias' id='existencias' class='form-control editable'></td>";

            echo "<td><select name='categoria_id' id='categoria_id' class='form-select editable'>";
            echo "<option value=''></option>";
            foreach ($obj_categoria->getAll() as $categoria) {
                echo "<option value='" . $categoria['id'] . "'>" . $categoria['nombre_categoria'] . "</option>";
            }
            echo "</select></td>";

            echo "<td><select name='proveedor_id' id='proveedor_id' class='form-select editable' >";
            echo "<option value=''></option>";
            foreach ($obj_proveedor->getAll() as $proveedor) {
                echo "<option value='" . $proveedor['id'] . "'>" . $proveedor['nombre_proveedor'] . "</option>";
            }
            echo "</select></td>";

            echo "<td>
            
            <button type='button' 
                    onclick='fnCreateUpdate(\"CREATE\",\"new\")' class='btn btn-success saveBtn'>
                    <img src='../miscelanea_nelsy/public/images/check.svg'>
            </button>
            </td>";

            echo "
            <td>
                <button type='button' 
                onclick='deleteNewRow()' class='btn btn-dark'>
                <img src='../miscelanea_nelsy/public/images/cancel.svg'>
                </button>
            </td>";

            echo "</form>";
            echo "</tr>";

            //fin fila vacia
            ?>

            <tr>
                <td colspan="12">
                    <button type="button" class="col-2 btn btn-primary me-2 my-2 " onclick="fnCreateRow()">
                        Nuevo producto
                    </button>
                </td>
            </tr>

            <!-- titulo de abajo -->
            <tr style="border-top: 2px solid #000;">
                <th scope="col">Id</th>
                <th scope="col">Nombre</th>
                <th scope="col">Código de Barras</th>
                <th scope="col">Precio compra</th>
                <th scope="col">Precio venta</th>
                <th scope="col">Precio mayoreo</th>
                <th scope="col">Unidad</th>
                <th scope="col">Existencias</th>
                <th scope="col">Categoria</th>
                <th scope="col">Proveedor</th>
                <th scope="col">Editar</th>
                <th scope="col">Eliminar</th>
            </tr>

        </tbody>

    </table>
    <script src="public/js/panel/productos.js"></script>
</div>