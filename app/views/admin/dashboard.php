<?php

$titulo = "Dashboard";

require_once __DIR__ . "/../layouts/admin_header.php";
require_once __DIR__ . "/../layouts/admin_sidebar.php";

?>

<main class="admin-main">

    <header class="admin-topbar">

        <div class="page-title">

            <h1>Dashboard</h1>

            <p>Administra el catálogo digital de AUREN</p>

        </div>


        <div class="admin-user">

            <div class="admin-avatar">
                <?= strtoupper(substr($_SESSION['admin_nombre'], 0, 1)) ?>
            </div>

            <div class="admin-user-info">
                <?php
                echo '<!-- ADMIN: ';
                echo htmlspecialchars($_SESSION['admin_nombre'] ?? 'SIN NOMBRE');
                echo ' -->';
                ?>

                <strong>
                    <?= htmlspecialchars($_SESSION['admin_nombre']) ?>
                </strong>

                <span>
                    Administrador
                </span>

            </div>

        </div>

    </header>


    <section class="dashboard-cards">

        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fa-solid fa-shirt"></i>
            </div>

            <div class="card-content">
                <div class="card-label">
                    Productos registrados
                </div>

                <div class="card-value">
                    <?= $totalProductos ?>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">
                <i class="fa-solid fa-circle-check"></i>
            </div>

            <div class="card-content">
                <div class="card-label">
                    Productos activos
                </div>

                <div class="card-value">
                    <?= $productosActivos ?>
                </div>
            </div>
        </div>

    </section>

    <section class="admin-section">

        <div class="section-header">

            <div>

                <h2>Gestión de AUREN</h2>

                <p>
                    Gestiona productos, imágenes y tallas desde un solo lugar.
                </p>

            </div>

            <a href="index.php?pagina=nuevo_producto" class="btn btn-primary">
                Nuevo producto
            </a>

        </div>


        <div class="dashboard-actions">

            <a href="index.php?pagina=productos" class="btn btn-secondary">
                Ver productos
            </a>

        </div>

    </section>

</main>

</div>

</body>

</html>