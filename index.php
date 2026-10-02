<?php

session_start();

require_once "app/controllers/CatalogoController.php";
require_once "app/controllers/AdminController.php";

$pagina = $_GET['pagina'] ?? 'catalogo';

$catalogoController = new CatalogoController();
$adminController = new AdminController();


switch ($pagina) {

    case 'catalogo':

        $catalogoController->index();

        break;


    case 'categoria':

        $id = $_GET['id'] ?? 0;

        $catalogoController->categoria($id);

        break;


    case 'producto':

        $id = $_GET['id'] ?? 0;

        $catalogoController->producto($id);

        break;


    case 'login':

        if (isset($_GET['accion']) && $_GET['accion'] === 'autenticar') {

            $adminController->autenticar();

        } else {

            $adminController->login();

        }

        break;


    case 'admin':

        $adminController->dashboard();

        break;


    case 'logout':

        $adminController->logout();

        break;

    case 'productos':

        $adminController->productos();

        break;

    case 'eliminar_producto':
        $adminController->eliminarProducto();
        break;


    case 'nuevo_producto':

        $adminController->nuevoProducto();

        break;

    case 'editar_producto':
        $adminController->editarProducto();
        break;

    case 'actualizar_producto':
        $adminController->actualizarProducto();
        break;

    case 'eliminar_imagen':
        $adminController->eliminarImagen();
        break;

    case 'principal_imagen':
        $adminController->establecerImagenPrincipal();
        break;


    case 'guardar_producto':

        $adminController->guardarProducto();

        break;

    default:

        $catalogoController->index();

        break;
}