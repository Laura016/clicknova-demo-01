<?php

require_once __DIR__ . "/../models/Producto.php";

class CatalogoController
{
    private $producto;

    public function __construct()
    {
        $this->producto = new Producto();
    }

    public function index()
    {
        $busqueda = isset($_GET['buscar'])
            ? trim($_GET['buscar'])
            : '';

        if ($busqueda !== '') {

            $productos =
                $this->producto->buscarProductos($busqueda);

        } else {

            $productos =
                $this->producto->obtenerProductosPublicos();

        }

        $categorias =
            $this->producto->obtenerCategorias();

        $categoriaActual = null;

        require_once __DIR__ . "/../views/catalogo/index.php";
    }

    public function categoria($id)
    {
        $productos = $this->producto->obtenerProductosPorCategoria($id);

        $categorias = $this->producto->obtenerCategorias();

        $categoriaActual = null;

        foreach ($categorias as $categoria) {

            if ((int) $categoria['id'] === (int) $id) {

                $categoriaActual = $categoria['nombre'];

                break;
            }
        }

        require_once __DIR__ . "/../views/catalogo/index.php";
    }

    public function producto($id)
    {
        $producto = $this->producto->obtenerProductoPublico($id);

        if (!$producto) {
            die("Producto no encontrado.");
        }

        $imagenes = $this->producto->obtenerImagenes($id);

        $tallas = $this->producto->obtenerTallas($id);

        $categorias = $this->producto->obtenerCategorias();

        require_once __DIR__ . "/../views/catalogo/producto.php";
    }
}