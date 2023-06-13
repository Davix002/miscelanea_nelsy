<?php

require_once __DIR__ . '/../../config/conexion.php';

class Proveedor
{
    private $pdo;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->pdo = $conexion->getPdo();
    }

    public function getAll()
    {
        $statement = $this->pdo->prepare("SELECT * FROM proveedores");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM proveedores WHERE id = ?");
        $statement->execute([$id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function create($proveedor_data)
    {
        $statement = $this->pdo->prepare("INSERT INTO proveedores (nombre_proveedor, nit, direccion, telefono, correo_electronico) VALUES (?, ?, ?, ?, ?)");
        return $statement->execute(
            [
                trim($proveedor_data['nombre_proveedor']),
                trim($proveedor_data['nit']),
                trim($proveedor_data['direccion']),
                trim($proveedor_data['telefono']),
                trim($proveedor_data['correo_electronico'])
            ]
        );
    }

    public function update($proveedor_data)
    {
        $statement = $this->pdo->prepare("UPDATE proveedores SET nombre_proveedor = ?, nit = ?, direccion = ?, telefono = ?, correo_electronico = ? WHERE id = ?");
        return $statement->execute(
            [
                trim($proveedor_data['nombre_proveedor']),
                trim($proveedor_data['nit']),
                trim($proveedor_data['direccion']),
                trim($proveedor_data['telefono']),
                trim($proveedor_data['correo_electronico']),
                trim($proveedor_data['id'])
            ]
        );
    }

    public function delete($id)
    {
        try {
            $statement = $this->pdo->prepare("DELETE FROM proveedores WHERE id = ?");
            return $statement->execute([$id]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000' && strpos($e->getMessage(), 'Cannot delete or update a parent row') !== false) {
                throw new Exception("No se puede eliminar este proveedor hasta que se eliminen todos los productos que dependen de el");
            } else {
                throw $e;
            }
        }
    }
}
