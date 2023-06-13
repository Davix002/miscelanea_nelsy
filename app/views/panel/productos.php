<div class="row">
    <!-- Button trigger modal -->

    <button type="button" class="col-2 btn btn-primary me-2 my-3 " onclick="fn_modal_producto('CREATE')">
        Nuevo producto
    </button>

    <button type="button" class="col-3 btn btn-secondary my-3" onclick="printProductsToPDF()">
        Imprimir lista de productos en PDF
    </button>

    <div class="col-4 my-3">
        <input type="search" class="form-control" id="searchProduct" placeholder="Buscar producto" onkeyup="filterProducts()">
    </div>

    <table class="table table-hover" id="productTable">
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
            for ($i = 0; $i < count($productos); $i++) {
                echo "<tr>";
                echo "<td>" . $productos[$i]['id'] . "</td>";
                echo "<td>" . $productos[$i]['nombre_producto'] . "</td>";
                echo "<td>" . $productos[$i]['codigo_barras'] . "</td>";
                echo "<td class='moneda'>" . $productos[$i]['precio_compra'] . "</td>";
                echo "<td class='moneda'>" . $productos[$i]['precio_venta'] . "</td>";
                echo "<td class='moneda'>" . $productos[$i]['precio_mayoreo'] . "</td>";
                echo "<td>" . $productos[$i]['unidad'] . "</td>";
                echo "<td>" . $productos[$i]['existencias'] . "</td>";
                echo "<td>" . $obj_producto->getNombreCategoria($productos[$i]['categoria_id']) . "</td>";
                echo "<td>" . $obj_producto->getNombreProveedor($productos[$i]['proveedor_id']) . "</td>";
                echo "<td>
                    <button type='button' 
                            onclick='fn_modal_producto(\"UPDATE\"," . $productos[$i]['id'] . ")' class='btn btn-primary'>
                            <img src='../miscelanea_nelsy/public/images/pencil.svg'>
                    </button>
                </td>";
                echo "
                <td>
                    <button type='button' 
                            onclick='fnDelete(" . $productos[$i]['id'] . ")' class='btn btn-danger'>
                            <img src='../miscelanea_nelsy/public/images/trash.svg'>
                    </button>
                </td>";
                echo "</tr>";
            }
            ?>

        </tbody>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="btnAbrirModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="formModal" id="form-modal-producto">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Nuevo producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="nombre_producto" class="col-form-label">Nombre producto:</label>
                                <input type="text" name="nombre_producto" class="form-control" id="nombre_producto">
                            </div>
                            <div class="col-12 mb-3">
                                <label for="codigo_barras" class="col-form-label">Código de Barras:</label>
                                <input type="text" name="codigo_barras" class="form-control" id="codigo_barras">
                            </div>
                            <div class="col-4 mb-3">
                                <label for="precio_compra" class="col-form-label">Precio compra:</label>
                                <input type="number" name="precio_compra" class="form-control" id="precio_compra">
                            </div>
                            <div class="col-4 mb-3">
                                <label for="precio_venta" class="col-form-label">Precio venta:</label>
                                <input type="number" name="precio_venta" class="form-control" id="precio_venta" placeholder="(+25%)">
                            </div>
                            <div class="col-4 mb-3">
                                <label for="precio_mayoreo" class="col-form-label">Precio mayoreo:</label>
                                <input type="number" name="precio_mayoreo" class="form-control" id="precio_mayoreo" placeholder="(-10%)">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="unidad" class="col-form-label">Unidad:</label>
                                <input type="text" name="unidad" class="form-control" id="unidad">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="existencias" class="col-form-label">Existencias:</label>
                                <input type="number" name="existencias" class="form-control" id="existencias">
                            </div>

                            <div class="col-6 mb-3">
                                <label for="categoria_id" class="col-form-label">Categoría:</label>
                                <select name="categoria_id" class="form-control" id="categoria_id">
                                    <option value=""></option>
                                    <?php
                                    require_once __DIR__ . "/../../models/Categoria.php";
                                    $obj_categoria = new Categoria();  ?>
                                    <?php foreach ($obj_categoria->getAll() as $categoria) : ?>
                                        <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['nombre_categoria']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-6 mb-3">
                                <label for="proveedor_id" class="col-form-label">Proveedor:</label>
                                <select name="proveedor_id" class="form-control" id="proveedor_id">
                                    <option value=""></option>
                                    <?php
                                    require_once __DIR__ . "/../../models/Proveedor.php";
                                    $obj_proveedor = new Proveedor();  ?>
                                    <?php foreach ($obj_proveedor->getAll() as $proveedor) : ?>
                                        <option value="<?php echo $proveedor['id']; ?>"><?php echo $proveedor['nombre_proveedor']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btn_cerrar" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" id="btnCreateUpdate" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="public/js/panel/productos.js"></script>
</div>