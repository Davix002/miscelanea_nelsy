<?php include __DIR__ . "/../modules/header.php"; ?>

<div class='mx-5'>
<div class="min-vh-100 container-fluid d-flex align-items-start justify-content-center">

        <?php
        if (isset($_GET["Pages"])) {
            switch ($_GET["Pages"]) {
                case "panel":
                    require_once __DIR__ . "/inicio.php";
                    break;
                case "inicio":
                    require_once __DIR__ . "/inicio.php";
                    break;
                case "productos":
                    require_once __DIR__ . "/productos.php";
                    break;
                case "categorias":
                    require_once __DIR__ . "/categorias.php";
                    break;
                case "proveedores":
                    require_once __DIR__ . "/proveedores.php";
                    break;
                case "facturas":
                    require_once __DIR__ . "/facturas.php";
                    break;
                case "clientes":
                    require_once __DIR__ . "/clientes.php";
                    break;
                case "deudas":
                    require_once __DIR__ . "/deudas.php";
                    break;
                default:
                    require_once __DIR__ . "/404.php";
                    break;
            }
        }
        ?>

</div>

</div>


<?php include __DIR__ . "/../modules/footer.php"; ?>