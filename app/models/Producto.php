<?php

require_once __DIR__ . '/../../config/conexion.php';

class Producto
{
    private $pdo;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->pdo = $conexion->getPdo();
    }

    public function getAll()
    {
        $statement = $this->pdo->prepare("SELECT * FROM productos");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM productos WHERE id = ?");
        $statement->execute([$id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getByName($name)
    {
        $name = '%' . $name . '%';
        $statement = $this->pdo->prepare("SELECT * FROM productos WHERE nombre_producto like ?");
        $statement->execute([$name]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByBarcode($codigo_barras)
    {
        $statement = $this->pdo->prepare("SELECT * FROM productos WHERE codigo_barras = ?");
        $statement->execute([$codigo_barras]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function getNombreCategoria($id)
    {
        $statement = $this->pdo->prepare("SELECT nombre_categoria FROM categorias WHERE id = ?");
        $statement->execute([$id]);
        return $statement->fetch(PDO::FETCH_ASSOC)['nombre_categoria'];
    }

    public function getNombreProveedor($id)
    {
        $statement = $this->pdo->prepare("SELECT nombre_proveedor FROM proveedores WHERE id = ?");
        $statement->execute([$id]);
        return $statement->fetch(PDO::FETCH_ASSOC)['nombre_proveedor'];
    }

    public function getCategoriaIdPorNombre($nombreCategoria)
    {
        $nombreCategoria = mb_strtolower($nombreCategoria, 'UTF-8');
        $statement = $this->pdo->prepare("SELECT id FROM categorias WHERE LOWER(nombre_categoria) = LOWER(?)");
        $statement->execute([$nombreCategoria]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null;
    }

    public function getProveedorIdPorNombre($nombreProveedor)
    {
        $nombreProveedor = mb_strtolower($nombreProveedor, 'UTF-8');
        $statement = $this->pdo->prepare("SELECT id FROM proveedores WHERE LOWER(nombre_proveedor) = LOWER(?)");
        $statement->execute([$nombreProveedor]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : null;
    }



    public function create($product_data)
    {
        $statement = $this->pdo->prepare("INSERT INTO productos (nombre_producto, codigo_barras, precio_compra, precio_venta, precio_mayoreo, unidad, existencias, proveedor_id, categoria_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $statement->execute(
            [
                trim($product_data['nombre_producto']),
                trim($product_data['codigo_barras']),
                trim($product_data['precio_compra']),
                trim($product_data['precio_venta']),
                trim($product_data['precio_mayoreo']),
                trim($product_data['unidad']),
                trim($product_data['existencias']),
                trim($product_data['proveedor_id']),
                trim($product_data['categoria_id'])
            ]
        );
    }

    public function update($product_data)
    {
        $statement = $this->pdo->prepare("UPDATE productos SET nombre_producto = ?, codigo_barras = ?, precio_compra = ?, precio_venta = ?, precio_mayoreo = ?, unidad = ?, existencias = ?, proveedor_id = ?, categoria_id = ? WHERE id = ?");
        return $statement->execute(
            [
                trim($product_data['nombre_producto']),
                trim($product_data['codigo_barras']),
                trim($product_data['precio_compra']),
                trim($product_data['precio_venta']),
                trim($product_data['precio_mayoreo']),
                trim($product_data['unidad']),
                trim($product_data['existencias']),
                trim($product_data['proveedor_id']),
                trim($product_data['categoria_id']),
                trim($product_data['id'])
            ]
        );
    }

    public function delete($id)
    {
        $statement = $this->pdo->prepare("DELETE FROM productos WHERE id = ?");
        return $statement->execute([$id]);
    }
}
