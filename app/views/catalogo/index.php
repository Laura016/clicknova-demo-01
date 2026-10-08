<?php

require_once __DIR__ . "/../../../config/whatsapp.php";

$mensajeGeneral =
    "Hola, estoy interesado/a en conocer la colección de AUREN.";

$urlWhatsAppGeneral =
    "https://wa.me/"
    . $whatsappNumero
    . "?text="
    . urlencode($mensajeGeneral);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= $categoriaActual
            ? htmlspecialchars($categoriaActual)
            : 'Catálogo AUREN' ?>
    </title>

    <meta name="description"
        content="Catálogo AUREN. Descubre una selección contemporánea de prendas esenciales.">

    <link rel="stylesheet" href="assets/css/catalogo.css?v=3">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body>


    <!-- =====================================================
     HEADER
     ===================================================== -->

    <header class="catalog-header">

    <div class="header-inner">

        <a href="index.php?pagina=catalogo" class="brand-logo">

            <img src="assets/img/logo-oficial.png" alt="AUREN">

        </a>


        <button
            type="button"
            class="mobile-menu-button"
            id="mobileMenuButton"
            aria-label="Abrir menú"
            aria-expanded="false"
        >
            <i class="fa-solid fa-bars"></i>
        </button>


        <nav class="catalog-nav" id="catalogNav">

    <button
        type="button"
        class="mobile-menu-close"
        id="mobileMenuClose"
        aria-label="Cerrar menú"
    >
        <i class="fa-solid fa-xmark"></i>
    </button>


    <a href="index.php?pagina=catalogo">
        Inicio
    </a>


    <?php foreach ($categorias as $categoria): ?>

        <a href="index.php?pagina=categoria&id=<?= $categoria['id'] ?>">
            <?= htmlspecialchars($categoria['nombre']) ?>
        </a>

    <?php endforeach; ?>


    <form
        action="index.php"
        method="GET"
        class="navbar-search"
    >

        <input
            type="hidden"
            name="pagina"
            value="catalogo"
        >

        <input
            type="text"
            name="buscar"
            class="navbar-search-input"
            placeholder="Buscar producto..."
            value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>"
            aria-label="Buscar producto"
        >

        <button
            type="submit"
            class="navbar-search-button"
            aria-label="Buscar"
        >
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>

    </form>


</nav>


        <div
            class="mobile-menu-overlay"
            id="mobileMenuOverlay"
        ></div>

    </div>

</header>



    <!-- =====================================================
     HERO
     ===================================================== -->
<?php if (empty($_GET['buscar']) && empty($categoriaActual)): ?>
    <section class="catalog-hero">

        <div class="hero-overlay"></div>

        <div class="hero-inner">

            <div class="hero-content">

                <span class="hero-label">
                    COLECCIÓN AUREN
                </span>

                <h1>
                    Viste Tu
                    <span>
                        Esencia.
                    </span>
                </h1>

                <p>
                    Descubre prendas esenciales, siluetas contemporáneas y piezas pensadas para acompañarte. Explora tallas, disponibilidad y consúltanos directamente por WhatsApp.
                </p>

                <a href="#productos" class="hero-button">
                    Ver colección
                </a>

            </div>

        </div>

    </section>


    <!-- =====================================================
     CATEGORÍAS
     ===================================================== -->

    <section class="categories-section">

        <div class="section-container">

            <div class="section-heading">

                <span>
                    Explora
                </span>

                <h2>
                    Colecciones
                </h2>

            </div>


            <div class="category-grid">

                <?php foreach ($categorias as $categoria): ?>

    <?php

    $nombreCategoria = strtolower(
        trim($categoria['nombre'])
    );

    if ($nombreCategoria === 'hombre') {

        $imagenCategoria =
            'assets/img/categoria-hombre.jpg';

    } elseif ($nombreCategoria === 'mujer') {

        $imagenCategoria =
            'assets/img/categoria-mujer.jpg';

    } else {

        $imagenCategoria = '';

    }

    ?>

    <a
        href="index.php?pagina=categoria&id=<?= $categoria['id'] ?>"
        class="category-card"
        <?php if (!empty($imagenCategoria)): ?>
            style="background-image: url('<?= $imagenCategoria ?>');"
        <?php endif; ?>
    >

        <div class="category-overlay"></div>

        <div class="category-content">

            <small>
                Colección
            </small>

            <h3>
                <?= htmlspecialchars($categoria['nombre']) ?>
            </h3>

            <span class="category-arrow">
                Explorar
                <i class="fa-solid fa-arrow-right"></i>
            </span>

        </div>

    </a>

<?php endforeach; ?>

            </div>

        </div>

    </section>

<?php endif; ?>

    <!-- =====================================================
     PRODUCTOS
     ===================================================== -->

    <section class="products-section" id="productos">

        <div class="section-container">

        <div class="mobile-products-search">

    <form
        action="index.php"
        method="GET"
        class="mobile-products-search-form"
    >

        <input
            type="hidden"
            name="pagina"
            value="catalogo"
        >

        <div class="mobile-search-icon">
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>

        <input
            type="text"
            name="buscar"
            class="mobile-products-search-input"
            placeholder="¿Qué estás buscando?"
            value="<?= htmlspecialchars($_GET['buscar'] ?? '') ?>"
            aria-label="Buscar producto"
        >

        <button
            type="submit"
            class="mobile-products-search-button"
            aria-label="Buscar producto"
        >
            Buscar
        </button>

    </form>

</div>

           <div class="section-heading">

    <?php if (!empty($_GET['buscar'])): ?>

        <span>
            Resultados de búsqueda
        </span>

        <h2>
            “<?= htmlspecialchars($_GET['buscar']) ?>”
        </h2>

    <?php else: ?>

        <span>

            <?= $categoriaActual
                ? 'Categoría'
                : 'Selección' ?>

        </span>

<h2>

    <?= $categoriaActual
        ? htmlspecialchars($categoriaActual)
        : 'Nuestra colección' ?>

</h2>

    <?php endif; ?>

</div>


            <?php if (!empty($productos)): ?>

                <div class="products-grid">

                    <?php foreach ($productos as $producto): ?>

                        <article class="product-card">


                            <a href="index.php?pagina=producto&id=<?= $producto['id'] ?>">

                                <div class="product-image">

                                    <?php if (!empty($producto['imagen_principal'])): ?>

                                        <img src="assets/img/productos/<?= htmlspecialchars($producto['imagen_principal']) ?>"
                                            alt="<?= htmlspecialchars($producto['nombre']) ?>" loading="lazy">

                                    <?php else: ?>

                                        <div style="
                                            width:100%;
                                            height:100%;
                                            display:flex;
                                            align-items:center;
                                            justify-content:center;
                                            color:#999;
                                        ">
                                            Sin imagen
                                        </div>

                                    <?php endif; ?>

                                </div>

                            </a>


                            <div class="product-info">

                                <span class="product-category">
                                    <?= htmlspecialchars($producto['categoria']) ?>
                                </span>


                                <h3>
                                    <?= htmlspecialchars($producto['nombre']) ?>
                                </h3>


                                <span class="product-reference">

                                    Ref.
                                    <?= htmlspecialchars($producto['referencia']) ?>

                                </span>


                                <div class="product-price">

                                    $<?= number_format(
                                        $producto['precio'],
                                        0,
                                        ',',
                                        '.'
                                    ) ?>

                                </div>

                                <?php if (!empty($producto['tallas'])): ?>

    <div class="product-sizes">

        <span class="product-sizes-label">
            Tallas
        </span>

        <div class="product-sizes-list">

            <?php foreach ($producto['tallas'] as $talla): ?>

                <?php if (!empty($talla['disponible'])): ?>

                    <span class="product-size">
    <?= htmlspecialchars($talla['talla']) ?>
</span>

                <?php endif; ?>

            <?php endforeach; ?>

        </div>

    </div>

<?php endif; ?>

                                <a href="index.php?pagina=producto&id=<?= $producto['id'] ?>" class="product-button">
                                    Ver prenda
                                </a>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

           <?php else: ?>

    <?php if (!empty($_GET['buscar'])): ?>

        <div class="search-empty">

            <div class="search-empty-icon">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>

            <span>
                Sin resultados
            </span>

            <h3>
                No encontramos ese producto
            </h3>

            <p>
                No encontramos productos que coincidan con
                <strong>
                    “<?= htmlspecialchars($_GET['buscar']) ?>”
                </strong>.
                Prueba buscando otro nombre o referencia.
            </p>

            <a
                href="index.php?pagina=catalogo"
                class="search-empty-button"
            >
                Ver todos los productos
            </a>

        </div>

    <?php else: ?>

        <div class="empty-products">

            <p>
                No hay productos disponibles
                en esta categoría.
            </p>

        </div>

    <?php endif; ?>

<?php endif; ?>

        </div>

    </section>



    <!-- =====================================================
     FOOTER
     ===================================================== -->

    <footer class="catalog-footer">

        <img src="assets/img/logo-oficial.png" alt="AUREN" class="footer-logo">

        <p class="footer-text">
            Colección AUREN
        </p>

    </footer>



    <!-- =====================================================
     WHATSAPP
     ===================================================== -->

    <a href="<?= htmlspecialchars($urlWhatsAppGeneral) ?>" class="whatsapp-float" target="_blank"
        rel="noopener noreferrer" aria-label="Contactar por WhatsApp" title="Contactar por WhatsApp">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

    <script>

document.addEventListener("DOMContentLoaded", function () {

    const menuButton =
        document.getElementById("mobileMenuButton");

    const menuClose =
        document.getElementById("mobileMenuClose");

    const catalogNav =
        document.getElementById("catalogNav");

    const menuOverlay =
        document.getElementById("mobileMenuOverlay");


    function abrirMenu() {

        catalogNav.classList.add("active");

        menuOverlay.classList.add("active");

        document.body.classList.add("menu-open");

        menuButton.setAttribute(
            "aria-expanded",
            "true"
        );
    }


    function cerrarMenu() {

        catalogNav.classList.remove("active");

        menuOverlay.classList.remove("active");

        document.body.classList.remove("menu-open");

        menuButton.setAttribute(
            "aria-expanded",
            "false"
        );
    }


    menuButton.addEventListener(
        "click",
        abrirMenu
    );


    menuClose.addEventListener(
        "click",
        cerrarMenu
    );


    menuOverlay.addEventListener(
        "click",
        cerrarMenu
    );


    const enlacesMenu =
        catalogNav.querySelectorAll("a");


    enlacesMenu.forEach(function (enlace) {

        enlace.addEventListener(
            "click",
            cerrarMenu
        );

    });

});

</script>

</body>

</html>