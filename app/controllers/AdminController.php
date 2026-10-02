<?php

require_once __DIR__ . "/../models/Administrador.php";

class AdminController
{
    private $administrador;

    public function __construct()
    {
        $this->administrador = new Administrador();
    }

    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['admin_id'])) {
            header("Location: index.php?pagina=admin");
            exit;
        }

        require_once __DIR__ . "/../views/admin/login.php";
    }

    public function autenticar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $usuario = trim($_POST['usuario'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($usuario === '' || $password === '') {
            $_SESSION['login_error'] = "Debes completar todos los campos.";

            header("Location: index.php?pagina=login");
            exit;
        }

        $admin = $this->administrador->obtenerPorUsuario($usuario);

        if ($admin && password_verify($password, $admin['password'])) {

            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_nombre'] = $admin['nombre'];
            $_SESSION['admin_usuario'] = $admin['usuario'];

            header("Location: index.php?pagina=admin");
            exit;
        }

        $_SESSION['login_error'] = "Usuario o contraseña incorrectos.";

        header("Location: index.php?pagina=login");
        exit;
    }

    public function dashboard()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['admin_id'])) {
            header("Location: index.php?pagina=login");
            exit;
        }

        require_once __DIR__ . "/../models/Producto.php";

        $productoModel = new Producto();

        $totalProductos = $productoModel->contarProductos();
        $productosDestacados = $productoModel->contarProductosDestacados();
        $productosActivos = $productoModel->contarProductosActivos();

        require_once __DIR__ . "/../views/admin/dashboard.php";
    }

    public function productos()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['admin_id'])) {
            header("Location: index.php?pagina=login");
            exit;
        }

        require_once __DIR__ . "/../models/Producto.php";

        $productoModel = new Producto();

        $productos = $productoModel->obtenerProductos();

        require_once __DIR__ . "/../views/admin/productos.php";
    }

    public function nuevoProducto()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['admin_id'])) {
            header("Location: index.php?pagina=login");
            exit;
        }

        require_once __DIR__ . "/../models/Producto.php";

        $productoModel = new Producto();

        $categorias = $productoModel->obtenerCategorias();

        require_once __DIR__ . "/../views/admin/nuevo_producto.php";
    }

    public function guardarProducto()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['admin_id'])) {
            header("Location: index.php?pagina=login");
            exit;
        }

        require_once __DIR__ . "/../models/Producto.php";

        $productoModel = new Producto();

        $datos = [
            'categoria_id' => $_POST['categoria_id'] ?? 0,
            'nombre' => trim($_POST['nombre'] ?? ''),
            'referencia' => trim($_POST['referencia'] ?? ''),
            'marca' => trim($_POST['marca'] ?? ''),
            'precio' => $_POST['precio'] ?? 0,
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'especificaciones' => trim($_POST['especificaciones'] ?? ''),
            'destacado' => isset($_POST['destacado']) ? 1 : 0,
            'estado' => isset($_POST['estado']) ? 1 : 0
        ];

        if (
            empty($datos['categoria_id']) ||
            empty($datos['nombre']) ||
            empty($datos['referencia']) ||
            empty($datos['precio'])
        ) {
            die("Debes completar los campos obligatorios.");
        }


        /*
         * Validar tallas
         */
        $tallas = $_POST['tallas'] ?? [];

        $tallasValidas = [];

        if (is_array($tallas)) {

            foreach ($tallas as $talla) {

                $talla = trim($talla);

                if ($talla !== '') {
                    $tallasValidas[] = $talla;
                }
            }
        }

        if (empty($tallasValidas)) {
            die("Debes seleccionar al menos una talla.");
        }

        $referencia = trim($_POST['referencia'] ?? '');

        if ($productoModel->referenciaExiste($referencia)) {

            header(
                "Location: index.php?pagina=nuevo_producto&error=referencia"
            );

            exit;
        }

        /*
         * Validar fotografías antes de crear el producto
         */

        if (
            isset($_FILES['imagenes']) &&
            !empty($_FILES['imagenes']['name'][0])
        ) {

            $totalImagenes = count(
                $_FILES['imagenes']['name']
            );

            $extensionesPermitidas = [
                'jpg',
                'jpeg',
                'png',
                'webp'
            ];

            for (
                $i = 0;
                $i < $totalImagenes;
                $i++
            ) {

                if (
                    $_FILES['imagenes']['error'][$i]
                    !== UPLOAD_ERR_OK
                ) {

                    header(
                        "Location: index.php?pagina=nuevo_producto&error=imagen_subida"
                    );

                    exit;
                }


                $nombreArchivo =
                    $_FILES['imagenes']['name'][$i];

                $extension = strtolower(
                    pathinfo(
                        $nombreArchivo,
                        PATHINFO_EXTENSION
                    )
                );


                if (
                    !in_array(
                        $extension,
                        $extensionesPermitidas
                    )
                ) {

                    header(
                        "Location: index.php?pagina=nuevo_producto&error=imagen_formato"
                    );

                    exit;
                }


                if (
                    $_FILES['imagenes']['size'][$i]
                    > 5 * 1024 * 1024
                ) {

                    header(
                        "Location: index.php?pagina=nuevo_producto&error=imagen_tamano"
                    );

                    exit;
                }
            }
        }

        $producto_id = $productoModel->crear($datos);

        if (!$producto_id) {
            die("No fue posible guardar el producto.");
        }

        if (
            isset($_FILES['imagenes']) &&
            !empty($_FILES['imagenes']['name'][0])
        ) {

            $totalImagenes = count(
                $_FILES['imagenes']['name']
            );

            for (
                $i = 0;
                $i < $totalImagenes;
                $i++
            ) {

                $archivo = [
                    'name' => $_FILES['imagenes']['name'][$i],
                    'type' => $_FILES['imagenes']['type'][$i],
                    'tmp_name' => $_FILES['imagenes']['tmp_name'][$i],
                    'error' => $_FILES['imagenes']['error'][$i],
                    'size' => $_FILES['imagenes']['size'][$i]
                ];

                $nombreImagen = $this->subirImagen(
                    $archivo
                );

                if ($nombreImagen) {

                    $productoModel->agregarImagen(
                        $producto_id,
                        $nombreImagen,
                        $i
                    );

                    if ($i === 0) {

                        $this->actualizarImagenPrincipal(
                            $producto_id,
                            $nombreImagen
                        );
                    }
                }
            }
        }

        foreach ($tallasValidas as $talla) {

            $productoModel->guardarTalla(
                $producto_id,
                $talla
            );
        }

        header("Location: index.php?pagina=productos");
        exit;
    }

    public function eliminarProducto()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['admin_id'])) {
            header("Location: index.php?pagina=login");
            exit;
        }

        require_once __DIR__ . "/../models/Producto.php";

        $productoModel = new Producto();

        $id = $_GET['id'] ?? 0;

        if (empty($id)) {
            die("Producto no válido.");
        }

        // Obtener el producto
        $producto = $productoModel->obtenerProducto($id);

        if (!$producto) {
            die("Producto no encontrado.");
        }

        // Obtener todas sus imágenes
        $imagenes = $productoModel->obtenerImagenes($id);

        // Eliminar los archivos físicos
        if (!empty($imagenes)) {

            foreach ($imagenes as $imagen) {

                $rutaImagen =
                    __DIR__
                    . "/../../assets/img/productos/"
                    . $imagen['imagen'];

                if (file_exists($rutaImagen)) {
                    unlink($rutaImagen);
                }
            }
        }

        // Eliminar el producto
        // Las tallas e imágenes de BD se eliminan
        // automáticamente por ON DELETE CASCADE.
        $eliminado = $productoModel->eliminar($id);

        if (!$eliminado) {
            die("No fue posible eliminar el producto.");
        }

        header("Location: index.php?pagina=productos");

        exit;
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();

        header("Location: index.php?pagina=login");
        exit;
    }

    private function subirImagen($archivo)
    {
        if (
            !isset($archivo) ||
            $archivo['error'] !== UPLOAD_ERR_OK
        ) {
            return false;
        }

        $permitidas = [
            'jpg',
            'jpeg',
            'png',
            'webp'
        ];

        $extension = strtolower(
            pathinfo(
                $archivo['name'],
                PATHINFO_EXTENSION
            )
        );

        if (!in_array($extension, $permitidas)) {
            return false;
        }

        if ($archivo['size'] > 5 * 1024 * 1024) {
            return false;
        }

        $nombre = uniqid(
            'producto_',
            true
        ) . '.' . $extension;

        $ruta = __DIR__
            . "/../../assets/img/productos/"
            . $nombre;

        if (
            move_uploaded_file(
                $archivo['tmp_name'],
                $ruta
            )
        ) {
            return $nombre;
        }

        return false;
    }

    private function actualizarImagenPrincipal(
        $producto_id,
        $imagen
    ) {
        require_once __DIR__ . "/../models/Producto.php";

        $productoModel = new Producto();

        $productoModel->actualizarImagenPrincipal(
            $producto_id,
            $imagen
        );
    }

    public function editarProducto()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['admin_id'])) {
            header("Location: index.php?pagina=login");
            exit;
        }

        require_once __DIR__ . "/../models/Producto.php";

        $productoModel = new Producto();

        $id = $_GET['id'] ?? 0;

        $producto = $productoModel->obtenerProducto($id);

        if (!$producto) {
            die("Producto no encontrado.");
        }

        $categorias = $productoModel->obtenerCategorias();

        $tallas = $productoModel->obtenerTallas($id);

        $imagenes = $productoModel->obtenerImagenes($id);

        require_once __DIR__ . "/../views/admin/editar_producto.php";
    }


    public function actualizarProducto()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['admin_id'])) {
            header("Location: index.php?pagina=login");
            exit;
        }

        require_once __DIR__ . "/../models/Producto.php";

        $productoModel = new Producto();

        $id = $_POST['id'] ?? 0;

        $datos = [
            'categoria_id' => $_POST['categoria_id'] ?? 0,
            'nombre' => trim($_POST['nombre'] ?? ''),
            'referencia' => trim($_POST['referencia'] ?? ''),
            'marca' => trim($_POST['marca'] ?? ''),
            'precio' => $_POST['precio'] ?? 0,
            'descripcion' => trim($_POST['descripcion'] ?? ''),
            'especificaciones' => trim($_POST['especificaciones'] ?? ''),
            'destacado' => isset($_POST['destacado']) ? 1 : 0,
            'estado' => isset($_POST['estado']) ? 1 : 0
        ];

        if (
            empty($id) ||
            empty($datos['categoria_id']) ||
            empty($datos['nombre']) ||
            empty($datos['referencia']) ||
            empty($datos['precio'])
        ) {
            die("Debes completar los campos obligatorios.");
        }


        /*
         * Verificar que la referencia no pertenezca
         * a otro producto
         */

        if (
            $productoModel->referenciaExiste(
                $datos['referencia'],
                $id
            )
        ) {

            header(
                "Location: index.php?pagina=editar_producto&id="
                . $id
                . "&error=referencia"
            );

            exit;
        }
        /*
         * Validar fotografías antes de actualizar el producto
         */

        if (
            isset($_FILES['imagenes']) &&
            !empty($_FILES['imagenes']['name'][0])
        ) {

            $totalImagenes = count(
                $_FILES['imagenes']['name']
            );

            $extensionesPermitidas = [
                'jpg',
                'jpeg',
                'png',
                'webp'
            ];

            for (
                $i = 0;
                $i < $totalImagenes;
                $i++
            ) {

                if (
                    $_FILES['imagenes']['error'][$i]
                    !== UPLOAD_ERR_OK
                ) {

                    header(
                        "Location: index.php?pagina=editar_producto&id="
                        . $id
                        . "&error=imagen_subida"
                    );

                    exit;
                }

                $nombreArchivo =
                    $_FILES['imagenes']['name'][$i];

                $extension = strtolower(
                    pathinfo(
                        $nombreArchivo,
                        PATHINFO_EXTENSION
                    )
                );

                if (
                    !in_array(
                        $extension,
                        $extensionesPermitidas
                    )
                ) {

                    header(
                        "Location: index.php?pagina=editar_producto&id="
                        . $id
                        . "&error=imagen_formato"
                    );

                    exit;
                }

                if (
                    $_FILES['imagenes']['size'][$i]
                    > 5 * 1024 * 1024
                ) {

                    header(
                        "Location: index.php?pagina=editar_producto&id="
                        . $id
                        . "&error=imagen_tamano"
                    );

                    exit;
                }
            }
        }

        /*
         * Validar tallas
         */

        $tallas = $_POST['tallas'] ?? [];

        $tallasValidas = [];

        if (is_array($tallas)) {

            foreach ($tallas as $talla) {

                $talla = trim($talla);

                if ($talla !== '') {
                    $tallasValidas[] = $talla;
                }
            }
        }

        if (empty($tallasValidas)) {

            header(
                "Location: index.php?pagina=editar_producto&id="
                . $id
                . "&error=tallas"
            );

            exit;
        }


        /*
         * Actualizar información principal
         */

        $actualizado = $productoModel->actualizar(
            $id,
            $datos
        );

        if (!$actualizado) {
            die("No fue posible actualizar el producto.");
        }


        /*
         * Actualizar tallas
         */

        $productoModel->eliminarTallas($id);

        foreach ($tallasValidas as $talla) {

            $productoModel->guardarTalla(
                $id,
                $talla
            );
        }


        /*
         * Agregar nuevas imágenes
         */

        if (
            isset($_FILES['imagenes']) &&
            !empty($_FILES['imagenes']['name'][0])
        ) {

            $totalImagenes = count(
                $_FILES['imagenes']['name']
            );

            for ($i = 0; $i < $totalImagenes; $i++) {

                $archivo = [
                    'name' => $_FILES['imagenes']['name'][$i],
                    'type' => $_FILES['imagenes']['type'][$i],
                    'tmp_name' => $_FILES['imagenes']['tmp_name'][$i],
                    'error' => $_FILES['imagenes']['error'][$i],
                    'size' => $_FILES['imagenes']['size'][$i]
                ];

                $nombreImagen = $this->subirImagen(
                    $archivo
                );

                if ($nombreImagen) {

                    $productoModel->agregarImagen(
                        $id,
                        $nombreImagen,
                        $i
                    );

                    /*
                     * Si el producto no tenía imagen principal,
                     * usamos la primera nueva imagen.
                     */

                    $productoActual = $productoModel->obtenerProducto($id);

                    if (
                        empty($productoActual['imagen_principal'])
                    ) {

                        $productoModel->actualizarImagenPrincipal(
                            $id,
                            $nombreImagen
                        );
                    }
                }
            }
        }


        header(
            "Location: index.php?pagina=productos"
        );

        exit;
    }

    public function eliminarImagen()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['admin_id'])) {
            header("Location: index.php?pagina=login");
            exit;
        }

        require_once __DIR__ . "/../models/Producto.php";

        $productoModel = new Producto();

        $imagenId = $_GET['id'] ?? 0;

        if (empty($imagenId)) {
            die("Imagen no válida.");
        }

        // Obtener información de la imagen
        $imagen = $productoModel->obtenerImagenPorId($imagenId);

        if (!$imagen) {
            die("Imagen no encontrada.");
        }

        $productoId = $imagen['producto_id'];

        // Obtener el producto antes de eliminar la imagen
        $producto = $productoModel->obtenerProducto($productoId);

        // Eliminar archivo físico
        $rutaImagen = __DIR__ . "/../../assets/img/productos/" . $imagen['imagen'];

        if (file_exists($rutaImagen)) {
            unlink($rutaImagen);
        }

        // Eliminar registro de la base de datos
        $productoModel->eliminarImagen($imagenId);

        // Si era la imagen principal, elegir otra
        if ($producto && $producto['imagen_principal'] === $imagen['imagen']) {

            $imagenesRestantes = $productoModel->obtenerImagenes($productoId);

            if (!empty($imagenesRestantes)) {

                $nuevaPrincipal = $imagenesRestantes[0]['imagen'];

                $productoModel->establecerImagenPrincipal(
                    $productoId,
                    $nuevaPrincipal
                );

            } else {

                // No quedan imágenes
                $productoModel->establecerImagenPrincipal(
                    $productoId,
                    null
                );
            }
        }

        header("Location: index.php?pagina=editar_producto&id=" . $productoId);
        exit;
    }


    public function establecerImagenPrincipal()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['admin_id'])) {
            header("Location: index.php?pagina=login");
            exit;
        }

        require_once __DIR__ . "/../models/Producto.php";

        $productoModel = new Producto();

        $imagenId = $_GET['imagen_id'] ?? 0;
        $productoId = $_GET['producto_id'] ?? 0;

        if (empty($imagenId) || empty($productoId)) {
            die("Datos no válidos.");
        }

        // Obtener imagen
        $imagen = $productoModel->obtenerImagenPorId($imagenId);

        if (!$imagen) {
            die("Imagen no encontrada.");
        }

        // Verificar que la imagen pertenece al producto
        if ((int) $imagen['producto_id'] !== (int) $productoId) {
            die("La imagen no pertenece a este producto.");
        }

        // Establecer como principal
        $productoModel->establecerImagenPrincipal(
            $productoId,
            $imagen['imagen']
        );

        header("Location: index.php?pagina=editar_producto&id=" . $productoId);
        exit;
    }
}