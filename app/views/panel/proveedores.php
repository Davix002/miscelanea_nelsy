<div class="row">
    <!-- Button trigger modal -->
    <button type="button" class="col-2 btn btn-primary me-2 my-3" onclick="scrollToBottom()">
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

        </tbody>
    </table>

    <?php
    // Detect if we are in a local environment or in production
    if ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1') {
        $images_base_path = '../miscelanea_nelsy/public/images';
    } else {
        $images_base_path = '../public/images';
    }
    ?>
    <script>
        var IMAGES_BASE_PATH = <?php echo json_encode($images_base_path); ?>;
    </script>

    <script src="public/js/panel/proveedores.js"></script>
</div>