<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        *,
        ::after,
        ::before {
            box-sizing: border-box;
        }

        .error-message {
            margin-top: .25rem;
            font-size: .875em;
            color: #dc3545;
        }

         table tr td{
            text-align: left;
            vertical-align: middle;
        }

        #productRow_new .editable, #categoriaRow_new .editable, #proveedorRow_new .editable{
            border-color:#333;
            border-width: 2px;
            box-shadow: inset 2px 2px 2px 0 gray;
        }
        
        .editable-row .editable{
            border-color:#333;
            border-width: 2px;
            box-shadow: inset 2px 2px 2px 0 gray;
        } 

    </style>

    <title>MISCELANEA NELSY</title>
</head>

<body>
    <?php
    
    session_start();
    if (isset($_SESSION["user"])) {
        require_once __DIR__ . "/../app/views/panel/index.php";
    } else {
        if (isset($_GET["Pages"])) {
            switch ($_GET["Pages"]) {
                case "panel":
                case "login":
                    require_once __DIR__ . "/../app/views/auth/login.php";
                    break;
                case "register":
                    require_once __DIR__ . "/../app/views/auth/registry.php";
                    break;
                default:
                    echo "<h1 class='d-flex align-items-center justify-content-center flex-column vh-100 display-1 fw-bold'>404</h1>";
                    break;
            }
        } else {
            require_once __DIR__ . "/../app/views/auth/login.php";
        }
    }

    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>