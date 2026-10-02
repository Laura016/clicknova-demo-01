<?php

require_once __DIR__ . "/../../config/database-local.php";

class Producto
{
    private $conexion;

    public function __construct()
    {
        $database = new Database();
        $this->conexion = $database->conectar();
    }

    public function obtenerProductos()
    {
        $sql = "SELECT 
                    p.*,
                    c.nombre AS categoria
                FROM productos p
                INNER JOIN categorias c
                    ON p.categoria_id = c.id
                ORDER BY p.id DESC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function obtenerProductosPublicos()
    {
        $sql = "SELECT 
                    p.*,
                    c.nombre AS categoria
                FROM productos p
                INNER JOIN categorias c
                    ON p.categoria_id = c.id
                WHERE p.estado = 1
                ORDER BY p.destacado DESC, p.id DESC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function obtenerProductosDestacados()
    {
        $sql = "SELECT 
                p.*,
                c.nombre AS categoria
            FROM productos p
            INNER JOIN categorias c
                ON p.categoria_id = c.id
            WHERE p.estado = 1
            AND p.destacado = 1
            ORDER BY p.id DESC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        $productos = $stmt->fetchAll();

        foreach ($productos as &$producto) {

            $producto['tallas'] =
                $this->obtenerTallas($producto['id']);

        }

        unset($producto);

        return $productos;
    }

    public function buscarProductos($busqueda)
    {
        $sql = "SELECT
                p.*,
                c.nombre AS categoria
            FROM productos p
            INNER JOIN categorias c
                ON p.categoria_id = c.id
            WHERE p.estado = 1
            AND (
                p.nombre LIKE :busqueda
                OR p.referencia LIKE :busqueda
            )
            ORDER BY p.destacado DESC, p.id DESC";

        $stmt = $this->conexion->prepare($sql);

        $termino = '%' . $busqueda . '%';

        $stmt->bindValue(
            ":busqueda",
            $termino,
            PDO::PARAM_STR
        );

        $stmt->execute();

        $productos = $stmt->fetchAll();

        foreach ($productos as &$producto) {

            $producto['tallas'] =
                $this->obtenerTallas($producto['id']);

        }

        unset($producto);

        return $productos;
    }

    public function obtenerProductosPorCategoria($categoria_id)
    {
        $sql = "SELECT 
                p.*,
                c.nombre AS categoria
            FROM productos p
            INNER JOIN categorias c
                ON p.categoria_id = c.id
            WHERE p.estado = 1
            AND p.categoria_id = :categoria_id
            ORDER BY p.destacado DESC, p.id DESC";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindParam(
            ":categoria_id",
            $categoria_id,
            PDO::PARAM_INT
        );

        $stmt->execute();

        $productos = $stmt->fetchAll();

        foreach ($productos as &$producto) {

            $producto['tallas'] =
                $this->obtenerTallas($producto['id']);

        }

        unset($producto);

        return $productos;
    }

    public function obtenerProducto($id)
    {
        $sql = "SELECT 
                    p.*,
                    c.nombre AS categoria
                FROM productos p
                INNER JOIN categorias c
                    ON p.categoria_id = c.id
                WHERE p.id = :id
                LIMIT 1";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindParam(
            ":id",
            $id,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetch();
    }

    public function obtenerProductoPublico($id)
    {
        $sql = "SELECT
                p.*,
                c.nombre AS categoria
            FROM productos p
            INNER JOIN categorias c
                ON p.categoria_id = c.id
            WHERE p.id = :id
            AND p.estado = 1
            LIMIT 1";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(
            ":id",
            $id,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetch();
    }


    public function obtenerCategorias()
    {
        $sql = "SELECT *
                FROM categorias
                WHERE estado = 1
                ORDER BY nombre ASC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function crear($datos)
    {
        $sql = "INSERT INTO productos
                (
                    categoria_id,
                    nombre,
                    referencia,
                    marca,
                    precio,
                    descripcion,
                    especificaciones,
                    destacado,
                    estado
                )
                VALUES
                (
                    :categoria_id,
                    :nombre,
                    :referencia,
                    :marca,
                    :precio,
                    :descripcion,
                    :especificaciones,
                    :destacado,
                    :estado
                )";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(
            ":categoria_id",
            $datos['categoria_id'],
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ":nombre",
            $datos['nombre'],
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ":referencia",
            $datos['referencia'],
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ":marca",
            $datos['marca'],
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ":precio",
            $datos['precio']
        );

        $stmt->bindValue(
            ":descripcion",
            $datos['descripcion'],
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ":especificaciones",
            $datos['especificaciones'],
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ":destacado",
            $datos['destacado'],
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ":estado",
            $datos['estado'],
            PDO::PARAM_INT
        );

        if ($stmt->execute()) {
            return $this->conexion->lastInsertId();
        }

        return false;
    }

    public function guardarTalla($producto_id, $talla)
    {
        $sql = "INSERT INTO producto_tallas
                (
                    producto_id,
                    talla,
                    disponible
                )
                VALUES
                (
                    :producto_id,
                    :talla,
                    1
                )";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(
            ":producto_id",
            $producto_id,
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ":talla",
            $talla,
            PDO::PARAM_STR
        );

        return $stmt->execute();
    }

    public function obtenerTallas($producto_id)
    {
        $sql = "SELECT *
                FROM producto_tallas
                WHERE producto_id = :producto_id
                ORDER BY talla ASC";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(
            ":producto_id",
            $producto_id,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function agregarImagen($producto_id, $imagen, $orden = 0)
    {
        $sql = "INSERT INTO producto_imagenes
            (
                producto_id,
                imagen,
                orden
            )
            VALUES
            (
                :producto_id,
                :imagen,
                :orden
            )";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(
            ":producto_id",
            $producto_id,
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ":imagen",
            $imagen,
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ":orden",
            $orden,
            PDO::PARAM_INT
        );

        return $stmt->execute();
    }


    public function obtenerImagenes($producto_id)
    {
        $sql = "SELECT *
            FROM producto_imagenes
            WHERE producto_id = :producto_id
            ORDER BY orden ASC, id ASC";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(
            ":producto_id",
            $producto_id,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }


    public function eliminarImagen($id)
    {
        $sql = "DELETE FROM producto_imagenes
            WHERE id = :id";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(
            ":id",
            $id,
            PDO::PARAM_INT
        );

        return $stmt->execute();
    }

    public function obtenerImagenPorId($id)
    {
        $sql = "SELECT *
            FROM producto_imagenes
            WHERE id = :id
            LIMIT 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function establecerImagenPrincipal($producto_id, $imagen)
    {
        $sql = "UPDATE productos
            SET imagen_principal = :imagen
            WHERE id = :producto_id";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(":imagen", $imagen, PDO::PARAM_STR);
        $stmt->bindValue(":producto_id", $producto_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function actualizarImagenPrincipal(
        $producto_id,
        $imagen
    ) {
        $sql = "UPDATE productos
            SET imagen_principal = :imagen
            WHERE id = :producto_id";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(
            ":imagen",
            $imagen,
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ":producto_id",
            $producto_id,
            PDO::PARAM_INT
        );

        return $stmt->execute();
    }

    public function actualizar($id, $datos)
    {
        $sql = "UPDATE productos
            SET
                categoria_id = :categoria_id,
                nombre = :nombre,
                referencia = :referencia,
                marca = :marca,
                precio = :precio,
                descripcion = :descripcion,
                especificaciones = :especificaciones,
                destacado = :destacado,
                estado = :estado
            WHERE id = :id";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(
            ":categoria_id",
            $datos['categoria_id'],
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ":nombre",
            $datos['nombre'],
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ":referencia",
            $datos['referencia'],
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ":marca",
            $datos['marca'],
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ":precio",
            $datos['precio']
        );

        $stmt->bindValue(
            ":descripcion",
            $datos['descripcion'],
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ":especificaciones",
            $datos['especificaciones'],
            PDO::PARAM_STR
        );

        $stmt->bindValue(
            ":destacado",
            $datos['destacado'],
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ":estado",
            $datos['estado'],
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ":id",
            $id,
            PDO::PARAM_INT
        );

        return $stmt->execute();
    }


    public function eliminarTallas($producto_id)
    {
        $sql = "DELETE FROM producto_tallas
            WHERE producto_id = :producto_id";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(
            ":producto_id",
            $producto_id,
            PDO::PARAM_INT
        );

        return $stmt->execute();
    }

    public function contarProductos()
    {
        $sql = "SELECT COUNT(*) AS total
            FROM productos";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }


    public function contarProductosDestacados()
    {
        $sql = "SELECT COUNT(*) AS total
            FROM productos
            WHERE destacado = 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }


    public function contarProductosActivos()
    {
        $sql = "SELECT COUNT(*) AS total
            FROM productos
            WHERE estado = 1";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    /**
     * =========================================================
     * VERIFICAR REFERENCIA
     * =========================================================
     *
     * Comprueba si una referencia ya está registrada.
     *
     * @param string $referencia
     * @param int|null $idExcluir
     * @return bool
     */
    public function referenciaExiste($referencia, $idExcluir = null)
    {
        if ($idExcluir !== null) {

            $sql = "SELECT id
                FROM productos
                WHERE referencia = :referencia
                AND id != :id
                LIMIT 1";

            $stmt = $this->conexion->prepare($sql);

            $stmt->bindValue(
                ":referencia",
                $referencia,
                PDO::PARAM_STR
            );

            $stmt->bindValue(
                ":id",
                $idExcluir,
                PDO::PARAM_INT
            );

        } else {

            $sql = "SELECT id
                FROM productos
                WHERE referencia = :referencia
                LIMIT 1";

            $stmt = $this->conexion->prepare($sql);

            $stmt->bindValue(
                ":referencia",
                $referencia,
                PDO::PARAM_STR
            );
        }

        $stmt->execute();

        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM productos
            WHERE id = :id";

        $stmt = $this->conexion->prepare($sql);

        $stmt->bindValue(
            ":id",
            $id,
            PDO::PARAM_INT
        );

        return $stmt->execute();
    }
}