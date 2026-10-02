<?php

$titulo = "Productos";

require_once __DIR__ . "/../layouts/admin_header.php";
require_once __DIR__ . "/../layouts/admin_sidebar.php";

?>

<main class="admin-main">


    <!-- =========================================
         TOPBAR
    ========================================== -->

    <header class="admin-topbar">

        <button type="button" class="mobile-menu-button" id="mobileMenuButton" aria-label="Abrir menú">

            <i class="fa-solid fa-bars"></i>

        </button>


        <div class="page-title">

            <!-- BREADCRUMB -->

            <div class="breadcrumb">

                <a href="index.php?pagina=admin">
                    Dashboard
                </a>

                <span>/</span>

                <span>Productos</span>

            </div>


            <!-- TÍTULO -->

            <h1>
                Productos
            </h1>

            <p>
                Administra los productos de tu catálogo CalzaSport
            </p>

        </div>


        <!-- USUARIO -->

        <div class="admin-user">

            <div class="admin-avatar">

                <?= strtoupper(
                    substr($_SESSION['admin_nombre'], 0, 1)
                ) ?>

            </div>


            <div class="admin-user-info">

                <strong>
                    <?= htmlspecialchars(
                        $_SESSION['admin_nombre']
                    ) ?>
                </strong>

                <span>
                    Administrador
                </span>

            </div>

        </div>

    </header>



    <!-- =========================================
         SECCIÓN PRODUCTOS
    ========================================== -->

    <section class="admin-section">


        <!-- ENCABEZADO DE LA SECCIÓN -->

        <div class="section-header">


            <div>

                <h2>
                    Catálogo de productos
                </h2>

                <p>
                    Aquí puedes consultar y administrar tus productos.
                </p>

            </div>


            <!-- BOTONES DE NAVEGACIÓN -->

            <div class="section-actions">

                <a href="index.php?pagina=nuevo_producto" class="btn btn-primary">

                    <i class="fa-solid fa-plus"></i>

                    Nuevo producto

                </a>

            </div>


        </div>



        <!-- =========================================
             BÚSQUEDA
        ========================================== -->

        <div class="products-toolbar">

            <div class="search-box">

                <i class="fa-solid fa-magnifying-glass"></i>

                <input type="text" id="buscarProducto" placeholder="Buscar por nombre o referencia...">

            </div>

        </div>



        <!-- =========================================
             TABLA
        ========================================== -->

        <div class="admin-table-wrapper">

            <table class="admin-table" id="tablaProductos">

                <thead>

                    <tr>

                        <th>
                            Producto
                        </th>

                        <th>
                            Referencia
                        </th>

                        <th>
                            Categoría
                        </th>

                        <th>
                            Precio
                        </th>

                        <th>
                            Estado
                        </th>

                        <th>
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody>


                    <?php if (!empty($productos)): ?>


                        <?php foreach ($productos as $producto): ?>


                            <tr>


                                <!-- =================================
                                     PRODUCTO
                                ================================== -->

                                <td>

                                    <div class="product-table-info">


                                        <?php if (!empty($producto['imagen_principal'])): ?>

                                            <img src="assets/img/productos/<?= htmlspecialchars(
                                                $producto['imagen_principal']
                                            ) ?>" alt="<?= htmlspecialchars(
                                                 $producto['nombre']
                                             ) ?>" class="product-image">

                                        <?php else: ?>

                                            <div class="image-placeholder">

                                                <i class="fa-regular fa-image"></i>

                                            </div>

                                        <?php endif; ?>


                                        <div class="product-table-name">

                                            <strong>

                                                <?= htmlspecialchars(
                                                    $producto['nombre']
                                                ) ?>

                                            </strong>


                                            <?php if (!empty($producto['marca'])): ?>

                                                <span>

                                                    <?= htmlspecialchars(
                                                        $producto['marca']
                                                    ) ?>

                                                </span>

                                            <?php endif; ?>

                                        </div>


                                    </div>

                                </td>



                                <!-- =================================
                                     REFERENCIA
                                ================================== -->

                                <td>

                                    <span class="reference">

                                        <?= htmlspecialchars(
                                            $producto['referencia']
                                        ) ?>

                                    </span>

                                </td>



                                <!-- =================================
                                     CATEGORÍA
                                ================================== -->

                                <td>

                                    <?= htmlspecialchars(
                                        $producto['categoria']
                                    ) ?>

                                </td>



                                <!-- =================================
                                     PRECIO
                                ================================== -->

                                <td>

                                    <strong class="product-price">

                                        $<?= number_format(
                                            $producto['precio'],
                                            0,
                                            ',',
                                            '.'
                                        ) ?>

                                    </strong>

                                </td>



                                <!-- =================================
                                     ESTADO
                                ================================== -->

                                <td>

                                    <?php if ($producto['estado'] == 1): ?>

                                        <span class="status status-active">

                                            Activo

                                        </span>

                                    <?php else: ?>

                                        <span class="status status-inactive">

                                            Inactivo

                                        </span>

                                    <?php endif; ?>

                                </td>



                                <!-- =================================
                                     DESTACADO
                                ================================== -->



                                <!-- =================================
                                     ACCIONES
                                ================================== -->

                                <td>

                                    <div class="product-actions">


                                        <!-- VER -->

                                        <a href="index.php?pagina=producto&id=<?= $producto['id'] ?>"
                                            class="action-btn action-view" title="Ver producto">

                                            <i class="fa-regular fa-eye"></i>

                                        </a>


                                        <!-- EDITAR -->

                                        <a href="index.php?pagina=editar_producto&id=<?= $producto['id'] ?>"
                                            class="action-btn action-edit" title="Editar producto">

                                            <i class="fa-solid fa-pen"></i>

                                        </a>


                                        <!-- ELIMINAR -->

                                        <a href="index.php?pagina=eliminar_producto&id=<?= $producto['id'] ?>"
                                            class="action-btn action-delete" title="Eliminar producto"
                                            onclick="return confirm('¿Estás segura de que deseas eliminar este producto?');">

                                            <i class="fa-solid fa-trash"></i>

                                        </a>


                                    </div>

                                </td>


                            </tr>


                        <?php endforeach; ?>


                    <?php else: ?>


                        <!-- =================================
                             SIN PRODUCTOS
                        ================================== -->

                        <tr>

                            <td colspan="7" class="empty-products">

                                <i class="fa-regular fa-box-open"></i>

                                <strong>
                                    No hay productos registrados
                                </strong>

                                <span>
                                    Comienza agregando tu primer producto.
                                </span>

                            </td>

                        </tr>


                    <?php endif; ?>


                </tbody>

            </table>

        </div>


    </section>


</main>


</div>



<!-- =========================================
     BUSCADOR
========================================== -->

<script>

    const buscarProducto =
        document.getElementById('buscarProducto');

    const filas =
        document.querySelectorAll(
            '#tablaProductos tbody tr'
        );


    if (buscarProducto) {

        buscarProducto.addEventListener(
            'input',
            function () {

                const texto =
                    this.value
                        .toLowerCase()
                        .trim();


                filas.forEach(function (fila) {

                    const contenido =
                        fila.textContent
                            .toLowerCase();


                    if (contenido.includes(texto)) {

                        fila.style.display = '';

                    } else {

                        fila.style.display = 'none';

                    }

                });

            }
        );

    }

</script>


</body>

</html>