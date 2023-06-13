<div class="row">
    <!-- Button trigger modal -->
    <button type="button" class="col-2 btn btn-primary me-2 my-3" onclick="fn_modal_proveedor('CREATE')">
        Nuevo proveedor
    </button>

    <button type="button" class="col-4 btn btn-secondary my-3" onclick="printProveedoresToPDF()">
        Imprimir lista de proveedores en PDF
    </button>

    <div class="col-4 my-3">
        <input type="search" class="form-control" id="searchProveedor" placeholder="Buscar proveedor" onkeyup="filterProveedores()">
    </div>

    <table class="table table-hover" id="proveedorTable">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nombre Proveedor</th>
                <th scope="col">NIT</th>
                <th scope="col">Dirección</th>
                <th scope="col">Teléfono</th>
                <th scope="col">Correo Electrónico</th>
                <th scope="col">Editar</th>
                <th scope="col">Eliminar</th>
            </tr>
        </thead>

        <tbody>

            <?php

            require_once __DIR__ . "/../../models/Proveedor.php";
            $obj_proveedor = new Proveedor();
            $proveedores = $obj_proveedor->getAll();
            for ($i = 0; $i < count($proveedores); $i++) {
                echo "<tr>";
                echo "<td>" . $proveedores[$i]['id'] . "</td>";
                echo "<td>" . $proveedores[$i]['nombre_proveedor'] . "</td>";
                echo "<td>" . $proveedores[$i]['nit'] . "</td>";
                echo "<td>" . $proveedores[$i]['direccion'] . "</td>";
                echo "<td>" . $proveedores[$i]['telefono'] . "</td>";
                echo "<td>" . $proveedores[$i]['correo_electronico'] . "</td>";
                echo "<td>
                    <button type='button' 
                            onclick='fn_modal_proveedor(\"UPDATE\"," . $proveedores[$i]['id'] . ")' class='btn btn-primary'>
                            <img src='../miscelanea_nelsy/public/images/pencil.svg'>
                    </button>
                </td>";
                echo "
                <td>
                    <button type='button' 
                            onclick='fnDelete(" . $proveedores[$i]['id'] . ")' class='btn btn-danger'>
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
                <form class="formModal" id="form-modal-proveedor">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Nuevo proveedor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="nombre_proveedor" class="col-form-label">Nombre proveedor:</label>
                                <input type="text" name="nombre_proveedor" class="form-control" id="nombre_proveedor">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="nit" class="col-form-label">NIT:</label>
                                <input type="text" name="nit" class="form-control" id="nit">
                            </div>
                            <div class="col-6 mb-3">
                                <label for="telefono" class="col-form-label">Teléfono:</label>
                                <input type="text" name="telefono" class="form-control" id="telefono">
                            </div>
                            <div class="col-12 mb-3">
                                <label for="direccion" class="col-form-label">Dirección:</label>
                                <input type="text" name="direccion" class="form-control" id="direccion">
                            </div>
                            <div class="col-12 mb-3">
                                <label for="correo_electronico" class="col-form-label">Correo electrónico:</label>
                                <input type="email" name="correo_electronico" class="form-control" id="correo_electronico">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" id="btnCreateUpdate" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="public/js/panel/proveedores.js"></script>
</div>