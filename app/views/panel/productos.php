<div class="row">
    <button type="button" class="col-2 btn btn-primary me-2 my-3 " onclick="scrollToBottom()">
        Nuevo producto
    </button>
    <button type="button" class="col-2 btn btn-secondary me-2 my-3" onclick="printProductsToPDF()">
        Generar PDF
    </button>
    <button type="button" class="col-2 btn btn-success my-3" onclick="generateProductsExcel()">
        Generar excel
    </button>
    <form id="uploadForm" enctype="multipart/form-data" class="col-5 my-3">
        <div class="input-group">
            <input type="file" class="form-control" name="excelFile" id="excelFile" accept=".xlsx">
            <button class="btn btn-success" type="submit">Subir archivo excel</button>
        </div>
    </form>
    <div class="col-4 mb-3 ps-0">
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
            <!-- aqui se carga la tabla con javascript -->
        </tbody>
        <tfoot>
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
        </tfoot>

    </table>

    <script src="public/js/panel/productos.js"></script>
</div>