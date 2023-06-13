<div class="row">
    <!-- Button trigger modal -->
    <button type="button" class="col-2 btn btn-primary me-2 my-3" onclick="fn_modal_categoria('CREATE')">
        Nueva categoría
    </button>

    <button type="button" class="col-4 btn btn-secondary my-3" onclick="printCategoriesToPDF()">
        Imprimir lista de categorías en PDF
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

            <?php
            require_once __DIR__ . "/../../models/Categoria.php";
            $obj_categoria = new Categoria();
            $categorias = $obj_categoria->getAll();
            for ($i = 0; $i < count($categorias); $i++) {
                echo "<tr>";
                echo "<td>" . $categorias[$i]['id'] . "</td>";
                echo "<td>" . $categorias[$i]['nombre_categoria'] . "</td>";
                echo "<td>" . $categorias[$i]['descripcion'] . "</td>";
                echo "<td>
                    <button type='button' 
                            onclick='fn_modal_categoria(\"UPDATE\"," . $categorias[$i]['id'] . ")' class='btn btn-primary'>
                            <img src='../miscelanea_nelsy/public/images/pencil.svg'>
                    </button>
                </td>";
                echo "
                <td>
                    <button type='button' 
                            onclick='fnDelete(" . $categorias[$i]['id'] . ")' class='btn btn-danger'>
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
                <form class="formModal" id="form-modal-categoria">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Nueva categoría</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="nombre_categoria" class="col-form-label">Nombre de la categoría:</label>
                                <input type="text" name="nombre_categoria" class="form-control" id="nombre_categoria">
                            </div>
                            <div class="col-12 mb-3">
                                <label for="descripcion" class="col-form-label">Descripción:</label>
                                <textarea name="descripcion" class="form-control" id="descripcion"></textarea>
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
    <script src="public/js/panel/categorias.js"></script>

</div>