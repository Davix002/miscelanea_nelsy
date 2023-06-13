<?php

require_once __DIR__ . '/../../config/conexion.php';

class Categoria
{
    private $pdo;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->pdo = $conexion->getPdo();
    }

    public function getAll()
    {
        $statement = $this->pdo->prepare("SELECT * FROM categorias");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM categorias WHERE id = ?");
        $statement->execute([$id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function create($categoria_data)
    {
        $statement = $this->pdo->prepare("INSERT INTO categorias (nombre_categoria, descripcion) VALUES (?, ?)");
        return $statement->execute(
            [
                trim($categoria_data['nombre_categoria']),
                trim($categoria_data['descripcion'])
            ]
        );
    }

    public function update($categoria_data)
    {
        $statement = $this->pdo->prepare("UPDATE categorias SET nombre_categoria = ?, descripcion = ? WHERE id = ?");
        return $statement->execute(
            [
                trim($categoria_data['nombre_categoria']),
                trim($categoria_data['descripcion']),
                trim($categoria_data['id'])
            ]
        );
    }

    public function delete($id)
    {
        try {
            $statement = $this->pdo->prepare("DELETE FROM categorias WHERE id = ?");
            return $statement->execute([$id]);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000' && strpos($e->getMessage(), 'Cannot delete or update a parent row') !== false) {
                throw new Exception("No se puede eliminar esta categoría hasta que se eliminen todos los productos que dependen de ella.");
            } else {
                throw $e;
            }
        }
    }
}
