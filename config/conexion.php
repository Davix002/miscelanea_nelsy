<?php

class Conexion
{
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbName = "miscelanea_nelsy";
    private $charset = "utf8mb4";

    private $pdo;

    public function __construct()
    {
        $dsn = "mysql:host=$this->host;dbname=$this->dbName;charset=$this->charset";

        try {
            $this->pdo = new PDO($dsn, $this->username, $this->password);
            // Se establece que queremos que los errores de PDO se manejen lanzando excepciones
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Error al conectar con la base de datos: " . $e->getMessage();
        }
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    public function cerrarConexion()
    {
        $this->pdo = null;
    }
}
