<div class="row">
    <button type="button" class="col-2 btn btn-primary me-2 my-3" onclick="scrollToBottom()">
        Nueva categoría
    </button>
    <button type="button" class="col-2 btn btn-secondary me-2 my-3" onclick="printCategoriesToPDF()">
        Generar PDF
    </button>
    <button type="button" class="col-2 btn btn-success my-3" onclick="generateCategoriasExcel()">
        Generar excel
    </button>
    <div class="col-4 my-3">
        <input type="search" class="form-control" id="searchCategorie" placeholder="Buscar categoría" onkeyup="filterCategories()">
    </div>

    <table class="table table-hover" id="categorieTable">
        <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nombre de la categoría</th>
                <th scope="col">Descripción</th>
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
    <script src="public/js/panel/categorias.js"></script>

</div>