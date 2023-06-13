<?php
class User
{
    private $pdo;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->pdo = $conexion->getPdo();
    }

    public function findByEmail($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user : null;
    }

    public function create($userData)
    {
        $fecha = date("Y-m-d H:i:s");
        $pass = password_hash($userData['password'], PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO usuarios (nombres, apellidos, email, pass, created_at, updated_at) VALUES (:nombres, :apellidos, :email, :password, :created_at, :updated_at)");
        $stmt->bindParam(':nombres', $userData['nombres']);
        $stmt->bindParam(':apellidos', $userData['apellidos']);
        $stmt->bindParam(':email', $userData['email']);
        $stmt->bindParam(':password', $pass);
        $stmt->bindParam(':created_at', $fecha);
        $stmt->bindParam(':updated_at', $fecha);
        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    public function __destruct() {
        $this->pdo = null;
    }
}