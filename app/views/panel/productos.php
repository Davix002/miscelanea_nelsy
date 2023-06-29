<div class="row">
    <button type="button" class="col-2 btn btn-primary me-2 my-3 " onclick="scrollToBottom()">
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
                echo "<td>" . $productos[$i]['id'] . "</td>";
                echo "<td class='nombre_producto editable' contenteditable='false'>" . $productos[$i]['nombre_producto'] . "</td>";
                echo "<td class='codigo_barras editable' contenteditable='false'>" . $productos[$i]['codigo_barras'] . "</td>";
                echo "<td class='precio_compra editable moneda' contenteditable='false'>" . $productos[$i]['precio_compra'] . "</td>";
                echo "<td class='precio_venta editable moneda' contenteditable='false'>" . $productos[$i]['precio_venta'] . "</td>";
                echo "<td class='precio_mayoreo editable moneda' contenteditable='false'>" . $productos[$i]['precio_mayoreo'] . "</td>";
                echo "<td class='unidad editable' contenteditable='false'>" . $productos[$i]['unidad'] . "</td>";
                echo "<td class='existencias editable' contenteditable='false'>" . $productos[$i]['existencias'] . "</td>";

                $categoria_actual = $obj_producto->getNombreCategoria($productos[$i]['categoria_id']);
                echo "<td class='categoria_id editable'><select name='categoria_id' class='form-select border-0 bg-transparent' disabled>";
                echo "<option value=''></option>";
                foreach ($obj_categoria->getAll() as $categoria) {
                    $selected = $categoria['nombre_categoria'] == $categoria_actual ? "selected" : "";
                    echo "<option value='" . $categoria['id'] . "' $selected>" . $categoria['nombre_categoria'] . "</option>";
                }
                echo "</select></td>";

                $proveedor_actual = $obj_producto->getNombreProveedor($productos[$i]['proveedor_id']);
                echo "<td class='proveedor_id editable'><select name='proveedor_id' class='form-select border-0 bg-transparent' disabled>";
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
                echo "</tr>";
            }

            echo "<tr id='productRow_new'>";
            echo "<td>+</td>";
            echo "<td class='nombre_producto editable' id='nombre_producto_nuevo' contenteditable='true'></td>";
            echo "<td class='codigo_barras editable' contenteditable='true'></td>";
            echo "<td class='precio_compra editable' id='precio_compra_nuevo' contenteditable='true'></td>";
            echo "<td class='precio_venta editable' id='precio_venta_nuevo' contenteditable='true'></td>";
            echo "<td class='precio_mayoreo editable' id='precio_mayoreo_nuevo' contenteditable='true'></td>";
            echo "<td class='unidad editable' contenteditable='true'></td>";
            echo "<td class='existencias editable' contenteditable='true'></td>";

            echo "<td class='categoria_id editable'><select name='categoria_id' class='form-select border-0'>";
            echo "<option value=''></option>";
            foreach ($obj_categoria->getAll() as $categoria) {
                echo "<option value='" . $categoria['id'] . "'>" . $categoria['nombre_categoria'] . "</option>";
            }
            echo "</select></td>";

            echo "<td class='proveedor_id editable'><select name='proveedor_id' class='form-select border-0' >";
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
            echo "</tr>";
            ?>

        </tbody>
        <thead>
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
        </thead>

    </table>
    
    <script src="public/js/panel/productos.js"></script>
</div>