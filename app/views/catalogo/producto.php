<?php
header('Content-Type: text/html; charset=UTF-8');

$imagenPrincipal = $producto['imagen_principal'] ?? null;

$galeria = [];

require_once __DIR__ . "/../../../config/whatsapp.php";


/*
 * Imagen principal
 */
if (!empty($imagenPrincipal)) {

    $galeria[] = $imagenPrincipal;
}


/*
 * Agregar imágenes adicionales
 */
if (!empty($imagenes)) {

    foreach ($imagenes as $imagen) {

        if (
            !empty($imagen['imagen']) &&
            !in_array($imagen['imagen'], $galeria)
        ) {

            $galeria[] = $imagen['imagen'];
        }
    }
}

/*
 * Crear mensaje base
 */
$mensajeWhatsApp =
    "Hola, estoy interesado/a en el producto "
    . $producto['nombre']
    . ", referencia "
    . $producto['referencia']
    . ". Precio: $"
    . number_format(
        $producto['precio'],
        0,
        ',',
        '.'
    )
    . ". Me gustaría consultar disponibilidad.";



$urlWhatsApp = '';

if (!empty($whatsappNumero)) {

    $urlWhatsApp =
        "https://wa.me/"
        . $whatsappNumero
        . "?text="
        . urlencode($mensajeWhatsApp);
}

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars($producto['nombre']) ?>
        | AUREN
    </title>

    <meta name="description" content="<?= htmlspecialchars(
        $producto['descripcion']
        ?? 'Prenda AUREN'
    ) ?>">

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


            <nav class="catalog-nav">

                <a href="index.php?pagina=catalogo">
                    Inicio
                </a>


                <?php foreach ($categorias as $categoria): ?>

                    <a href="index.php?pagina=categoria&id=<?= $categoria['id'] ?>">
                        <?= htmlspecialchars(
                            $categoria['nombre']
                        ) ?>
                    </a>

                <?php endforeach; ?>

            </nav>


        </div>

    </header>



    <!-- =====================================================
     PRODUCTO
     ===================================================== -->

    <main class="product-detail-page">


        <div class="product-detail-container">


            <!-- =============================================
             MIGAS
             ============================================== -->

            <div class="product-breadcrumb">

                <a href="index.php?pagina=catalogo">
                    Inicio
                </a>

                <span>
                    /
                </span>

                <a href="index.php?pagina=categoria&id=<?= $producto['categoria_id'] ?>">
                    <?= htmlspecialchars(
                        $producto['categoria']
                    ) ?>
                </a>

                <span>
                    /
                </span>

                <span>
                    <?= htmlspecialchars(
                        $producto['nombre']
                    ) ?>
                </span>

            </div>



            <!-- =============================================
             CONTENIDO
             ============================================== -->

            <div class="product-detail-grid">


                <!-- =========================================
                 GALERÍA
                 ========================================== -->

                <div class="product-gallery">


                    <div class="product-main-image" id="productMainImage">

                        <?php if (!empty($galeria)): ?>

                            <img src="assets/img/productos/<?= htmlspecialchars(
                                $galeria[0]
                            ) ?>" alt="<?= htmlspecialchars(
                                 $producto['nombre']
                             ) ?>" id="mainProductImage">


                            <button type="button" class="image-zoom-button" id="imageZoomButton"
                                aria-label="Ampliar imagen">

                                <i class="fa-solid fa-expand"></i>

                            </button>


                        <?php else: ?>

                            <div class="no-product-image">

                                <i class="fa-regular fa-image"></i>

                                <span>
                                    Sin imagen
                                </span>

                            </div>

                        <?php endif; ?>

                    </div>



                    <?php if (count($galeria) > 1): ?>

                        <div class="product-thumbnails">

                            <?php foreach (
                                $galeria
                                as $indice => $imagen
                            ): ?>

                                <button type="button" class="product-thumbnail <?= $indice === 0
                                    ? 'active'
                                    : '' ?>" data-image="assets/img/productos/<?= htmlspecialchars(
                                      $imagen
                                  ) ?>">

                                    <img src="assets/img/productos/<?= htmlspecialchars(
                                        $imagen
                                    ) ?>" alt="<?= htmlspecialchars(
                                         $producto['nombre']
                                     ) ?>">

                                </button>

                            <?php endforeach; ?>

                        </div>

                    <?php endif; ?>


                </div>



                <!-- =========================================
                 INFORMACIÓN
                 ========================================== -->

                <div class="product-detail-info">


                    <span class="detail-category">

                        <?= htmlspecialchars(
                            $producto['categoria']
                        ) ?>

                    </span>


                    <h1>

                        <?= htmlspecialchars(
                            $producto['nombre']
                        ) ?>

                    </h1>


                    <?php if (!empty($producto['marca'])): ?>

                        <p class="detail-brand">

                            <?= htmlspecialchars(
                                $producto['marca']
                            ) ?>

                        </p>

                    <?php endif; ?>


                    <p class="detail-reference">

                        Ref.
                        <?= htmlspecialchars(
                            $producto['referencia']
                        ) ?>

                    </p>


                    <div class="detail-price">

                        $<?= number_format(
                            $producto['precio'],
                            0,
                            ',',
                            '.'
                        ) ?>

                    </div>



                    <!-- =====================================
                     TALLAS
                     ====================================== -->

                    <?php if (!empty($tallas)): ?>

                        <div class="detail-section">

                            <div class="detail-section-title">

                                <strong>
                                    Tallas disponibles
                                </strong>

                            </div>


                            <div class="size-list">

                                <?php foreach ($tallas as $talla): ?>
                                    <?php if (!empty($talla['disponible'])): ?>
                                        <button type="button" class="size-button"
                                            data-size="<?= htmlspecialchars($talla['talla']) ?>">
                                            <?= htmlspecialchars($talla['talla']) ?>
                                        </button>
                                    <?php endif; ?>
                                <?php endforeach; ?>

                            </div>

                        </div>

                    <?php endif; ?>



                    <!-- =====================================
                     DESCRIPCIÓN
                     ====================================== -->

                    <?php if (
                        !empty($producto['descripcion'])
                    ): ?>

                        <div class="detail-section">

                            <h2>
                                Descripción
                            </h2>

                            <p class="detail-description">

                                <?= nl2br(
                                    htmlspecialchars(
                                        $producto['descripcion']
                                    )
                                ) ?>

                            </p>

                        </div>

                    <?php endif; ?>



                    <!-- =====================================
                     ESPECIFICACIONES
                     ====================================== -->

                    <?php if (
                        !empty(
                        $producto['especificaciones']
                    )
                    ): ?>

                        <div class="detail-section">

                            <h2>
                                Especificaciones
                            </h2>

                            <div class="detail-specifications">

                                <?= nl2br(
                                    htmlspecialchars(
                                        $producto[
                                            'especificaciones'
                                        ]
                                    )
                                ) ?>

                            </div>

                        </div>

                    <?php endif; ?>



                    <!-- =====================================
                     WHATSAPP
                     ====================================== -->

                    <div class="detail-whatsapp">

                        <?php if (!empty($urlWhatsApp)): ?>

                            <!-- BOTÓN 1: INFORMACIÓN DEL PRODUCTO -->

                            <a href="<?= htmlspecialchars($urlWhatsApp) ?>" target="_blank" rel="noopener noreferrer"
                                class="whatsapp-product-button">
                                <i class="fa-brands fa-whatsapp"></i>
                                Consultar por WhatsApp
                            </a>


                            <!-- BOTÓN 2: CONTINUAR CON LA COMPRA -->

                            <button type="button" class="whatsapp-product-button purchase-button" id="openPurchaseForm">
                                <i class="fa-solid fa-bag-shopping"></i>
                                Continuar con la compra
                            </button>


                            <?php if (!empty($tallas)): ?>

                                <p class="whatsapp-size-message" id="whatsappSizeMessage">
                                    Selecciona una talla para consultar por WhatsApp.
                                </p>

                            <?php endif; ?>


                            <!-- FORMULARIO DE COMPRA -->

                            <div class="purchase-form-container" id="purchaseFormContainer" hidden>

                                <div class="purchase-form-header">

                                    <span>
                                        Compra
                                    </span>

                                    <h3>
                                        Completa tus datos
                                    </h3>

                                    <p>
                                        Déjanos tus datos para preparar tu pedido
                                        y continuar la compra por WhatsApp.
                                    </p>

                                </div>


                                <form id="purchaseForm" class="purchase-form">

                                    <!-- DATOS PERSONALES -->

                                    <div class="purchase-form-section">

                                        <div class="purchase-form-section-title">
                                            Datos personales
                                        </div>


                                        <div class="purchase-form-grid">

                                            <div class="purchase-field purchase-field-full">

                                                <label for="purchaseName">
                                                    Nombre completo
                                                </label>

                                                <input type="text" id="purchaseName" name="nombre" required
                                                    autocomplete="name" placeholder="Tu nombre completo">

                                            </div>


                                            <div class="purchase-field">

                                                <label for="purchasePhone">
                                                    Teléfono
                                                </label>

                                                <input type="tel" id="purchasePhone" name="telefono" required
                                                    autocomplete="tel" placeholder="Ej. 300 000 0000">

                                            </div>


                                            <div class="purchase-field">

                                                <label for="purchaseEmail">
                                                    Correo electrónico
                                                </label>

                                                <input type="email" id="purchaseEmail" name="correo" required
                                                    autocomplete="email" placeholder="correo@ejemplo.com">

                                            </div>

                                        </div>

                                    </div>


                                    <!-- DATOS DE ENVÍO -->

                                    <div class="purchase-form-section">

                                        <div class="purchase-form-section-title">
                                            Datos de envío
                                        </div>


                                        <div class="purchase-form-grid">

                                            <div class="purchase-field">

                                                <label for="purchaseCity">
                                                    Ciudad
                                                </label>

                                                <input type="text" id="purchaseCity" name="ciudad" required
                                                    autocomplete="address-level2" placeholder="Ej. Medellín">

                                            </div>


                                            <div class="purchase-field">

                                                <label for="purchaseNeighborhood">
                                                    Barrio
                                                </label>

                                                <input type="text" id="purchaseNeighborhood" name="barrio" required
                                                    placeholder="Tu barrio">

                                            </div>


                                            <div class="purchase-field purchase-field-full">

                                                <label for="purchaseAddress">
                                                    Dirección de entrega
                                                </label>

                                                <input type="text" id="purchaseAddress" name="direccion" required
                                                    autocomplete="street-address"
                                                    placeholder="Calle, carrera, número, apartamento...">

                                            </div>

                                        </div>

                                    </div>


                                    <!-- DETALLES DEL PRODUCTO -->

                                    <div class="purchase-form-section">

                                        <div class="purchase-form-section-title">
                                            Detalles del pedido
                                        </div>


                                        <div class="purchase-form-grid">

                                            <div class="purchase-field">

                                                <label for="purchaseSize">
                                                    Talla
                                                </label>

                                                <select id="purchaseSize" name="talla" required>

                                                    <option value="">
                                                        Selecciona tu talla
                                                    </option>

                                                    <?php foreach ($tallas as $talla): ?>

                                                        <?php if (!empty($talla['disponible'])): ?>

                                                            <option value="<?= htmlspecialchars($talla['talla']) ?>">
                                                                <?= htmlspecialchars($talla['talla']) ?>
                                                            </option>

                                                        <?php endif; ?>

                                                    <?php endforeach; ?>

                                                </select>

                                            </div>


                                            <div class="purchase-field">

                                                <label for="purchaseQuantity">
                                                    Cantidad
                                                </label>

                                                <input type="number" id="purchaseQuantity" name="cantidad" min="1" value="1"
                                                    required>

                                            </div>


                                            <div class="purchase-field purchase-field-full">

                                                <label for="purchasePayment">
                                                    Forma de pago
                                                </label>

                                                <select id="purchasePayment" name="pago" required>

                                                    <option value="">
                                                        Selecciona una forma de pago
                                                    </option>

                                                    <option value="Transferencia a Nequi">
                                                        Transferencia a Nequi
                                                    </option>

                                                    <option value="Transferencia a Bancolombia">
                                                        Transferencia a Bancolombia
                                                    </option>

                                                </select>

                                            </div>


                                            <div class="purchase-field purchase-field-full">

                                                <label for="purchaseNotes">
                                                    Observaciones
                                                    <span>(opcional)</span>
                                                </label>

                                                <textarea id="purchaseNotes" name="observaciones" rows="3"
                                                    placeholder="¿Hay algo que debamos tener en cuenta?"></textarea>

                                            </div>

                                        </div>

                                    </div>


                                    <!-- RESUMEN -->

                                    <div class="purchase-summary">

                                        <div class="purchase-summary-row">

                                            <span>
                                                Producto
                                            </span>

                                            <strong>
                                                <?= htmlspecialchars($producto['nombre']) ?>
                                            </strong>

                                        </div>


                                        <div class="purchase-summary-row">

                                            <span>
                                                Referencia
                                            </span>

                                            <strong>
                                                <?= htmlspecialchars($producto['referencia']) ?>
                                            </strong>

                                        </div>


                                        <div class="purchase-summary-row purchase-summary-total">

                                            <span>
                                                Precio
                                            </span>

                                            <strong>
                                                $<?= number_format(
                                                    $producto['precio'],
                                                    0,
                                                    ',',
                                                    '.'
                                                ) ?>
                                            </strong>

                                        </div>

                                    </div>


                                    <button type="submit" class="purchase-submit-button">
                                        <i class="fa-brands fa-whatsapp"></i>
                                        Continuar por WhatsApp
                                    </button>


                                    <p class="purchase-form-note">
                                        Al continuar, se abrirá WhatsApp con los datos
                                        que ingresaste para completar la solicitud de compra.
                                    </p>

                                </form>

                            </div>


                        <?php else: ?>

                            <button type="button" class="whatsapp-product-button disabled" disabled>
                                <i class="fa-brands fa-whatsapp"></i>
                                WhatsApp no disponible
                            </button>

                        <?php endif; ?>


                        <p class="whatsapp-description">
                            Te responderemos para confirmar disponibilidad y detalles del producto.
                        </p>

                    </div>


                </div>


            </div>


        </div>

    </main>



    <!-- =====================================================
     MODAL DE IMAGEN
     ===================================================== -->

    <div class="image-modal" id="imageModal" aria-hidden="true">


        <button type="button" class="image-modal-close" id="imageModalClose" aria-label="Cerrar imagen">

            <i class="fa-solid fa-xmark"></i>

        </button>


        <img src="" alt="<?= htmlspecialchars(
            $producto['nombre']
        ) ?>" id="modalProductImage">


    </div>



    <!-- =====================================================
     FOOTER
     ===================================================== -->

    <footer class="catalog-footer">

        <img src="assets/img/logo-oficial.png" alt="AUREN" class="footer-logo">

        <p class="footer-text">

            Catálogo oficial AUREN

        </p>

    </footer>



    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {


                /* ============================================
                   GALERÍA
                   ============================================ */

                const mainImage =
                    document.getElementById(
                        'mainProductImage'
                    );

                const thumbnails =
                    document.querySelectorAll(
                        '.product-thumbnail'
                    );


                thumbnails.forEach(
                    function (thumbnail) {

                        thumbnail.addEventListener(
                            'click',
                            function () {

                                if (!mainImage) {
                                    return;
                                }

                                mainImage.src =
                                    this.dataset.image;


                                thumbnails.forEach(
                                    function (item) {

                                        item.classList.remove(
                                            'active'
                                        );

                                    }
                                );


                                this.classList.add(
                                    'active'
                                );

                            }
                        );

                    }
                );



                /* ============================================
                   MODAL
                   ============================================ */

                const modal =
                    document.getElementById(
                        'imageModal'
                    );

                const modalImage =
                    document.getElementById(
                        'modalProductImage'
                    );

                const zoomButton =
                    document.getElementById(
                        'imageZoomButton'
                    );

                const closeButton =
                    document.getElementById(
                        'imageModalClose'
                    );


                function openModal() {

                    if (!mainImage || !modal) {
                        return;
                    }

                    modalImage.src =
                        mainImage.src;

                    modal.classList.add(
                        'active'
                    );

                    modal.setAttribute(
                        'aria-hidden',
                        'false'
                    );

                    document.body.style.overflow =
                        'hidden';

                }


                function closeModal() {

                    if (!modal) {
                        return;
                    }

                    modal.classList.remove(
                        'active'
                    );

                    modal.setAttribute(
                        'aria-hidden',
                        'true'
                    );

                    document.body.style.overflow =
                        '';

                }


                if (zoomButton) {

                    zoomButton.addEventListener(
                        'click',
                        openModal
                    );

                }


                if (mainImage) {

                    mainImage.addEventListener(
                        'click',
                        openModal
                    );

                }


                if (closeButton) {

                    closeButton.addEventListener(
                        'click',
                        closeModal
                    );

                }


                if (modal) {

                    modal.addEventListener(
                        'click',
                        function (event) {

                            if (
                                event.target === modal
                            ) {

                                closeModal();

                            }

                        }
                    );

                }


                document.addEventListener(
                    'keydown',
                    function (event) {

                        if (
                            event.key === 'Escape'
                        ) {

                            closeModal();

                        }

                    }
                );



                /* ============================================
   TALLAS + WHATSAPP
   ============================================ */

                const sizeButtons =
                    document.querySelectorAll('.size-button');

                const whatsappButton =
                    document.getElementById('whatsappProductButton');

                const whatsappBaseMessage =
                    <?= json_encode($mensajeWhatsApp) ?>;


                sizeButtons.forEach(function (button) {

                    button.addEventListener('click', function () {

                        /*
                         * Quitar selección anterior
                         */
                        sizeButtons.forEach(function (item) {

                            item.classList.remove('selected');

                        });


                        /*
                         * Seleccionar nueva talla
                         */
                        this.classList.add('selected');


                        const talla =
                            this.dataset.size;


                        /*
                         * Crear mensaje completo
                         */
                        const mensaje =
                            whatsappBaseMessage
                            + " Talla: "
                            + talla
                            + ".";


                        /*
                         * Actualizar enlace de WhatsApp
                         */
                        if (
                            whatsappButton &&
                            whatsappButton.tagName === 'A'
                        ) {

                            whatsappButton.href =
                                "https://wa.me/"
                                + <?= json_encode($whatsappNumero) ?>
                                + "?text="
                                + encodeURIComponent(mensaje);

                        }

                    });

                });

            }
        );

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const openPurchaseForm =
                document.getElementById('openPurchaseForm');

            const purchaseFormContainer =
                document.getElementById('purchaseFormContainer');

            const purchaseForm =
                document.getElementById('purchaseForm');

            const whatsappNumber =
                <?= json_encode($whatsappNumero) ?>;


            /*
            |--------------------------------------------------------------------------
            | ABRIR / CERRAR FORMULARIO
            |--------------------------------------------------------------------------
            */

            if (
                openPurchaseForm &&
                purchaseFormContainer
            ) {

                openPurchaseForm.addEventListener('click', function () {

                    const formularioAbierto =
                        !purchaseFormContainer.hasAttribute('hidden');


                    if (formularioAbierto) {

                        purchaseFormContainer.setAttribute(
                            'hidden',
                            ''
                        );

                        openPurchaseForm.innerHTML =
                            '<i class="fa-solid fa-bag-shopping"></i>' +
                            ' Continuar con la compra';

                        return;
                    }


                    purchaseFormContainer.removeAttribute('hidden');

                    openPurchaseForm.innerHTML =
                        '<i class="fa-solid fa-xmark"></i>' +
                        ' Cerrar formulario';


                    setTimeout(function () {

                        purchaseFormContainer.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });

                    }, 100);

                });

            }


            /*
            |--------------------------------------------------------------------------
            | ENVIAR DATOS DE COMPRA A WHATSAPP
            |--------------------------------------------------------------------------
            */

            if (purchaseForm) {

                purchaseForm.addEventListener(
                    'submit',
                    function (event) {

                        event.preventDefault();


                        /*
                        | Obtener datos del formulario
                        */

                        const nombre =
                            document.getElementById('purchaseName').value.trim();

                        const telefono =
                            document.getElementById('purchasePhone').value.trim();

                        const correo =
                            document.getElementById('purchaseEmail').value.trim();

                        const ciudad =
                            document.getElementById('purchaseCity').value.trim();

                        const barrio =
                            document.getElementById('purchaseNeighborhood').value.trim();

                        const direccion =
                            document.getElementById('purchaseAddress').value.trim();

                        const talla =
                            document.getElementById('purchaseSize').value;

                        const cantidad =
                            document.getElementById('purchaseQuantity').value;

                        const formaPago =
                            document.getElementById('purchasePayment').value;

                        const observaciones =
                            document.getElementById('purchaseNotes').value.trim();


                        /*
                        | Datos del producto
                        */

                        const producto =
                            <?= json_encode($producto['nombre']) ?>;

                        const referencia =
                            <?= json_encode($producto['referencia']) ?>;

                        const precio =
                            <?= json_encode($producto['precio']) ?>;


                        /*
                        | Validación básica
                        */

                        if (
                            !nombre ||
                            !telefono ||
                            !correo ||
                            !ciudad ||
                            !barrio ||
                            !direccion ||
                            !talla ||
                            !cantidad ||
                            !formaPago
                        ) {

                            alert(
                                'Por favor completa todos los campos obligatorios antes de continuar.'
                            );

                            return;
                        }


                        /*
                        | Formatear precio
                        */

                        const precioNumerico =
                            Number(precio);

                        const cantidadNumerica =
                            Number(cantidad);

                        const total =
                            precioNumerico * cantidadNumerica;


                        const precioFormateado =
                            new Intl.NumberFormat(
                                'es-CO',
                                {
                                    style: 'currency',
                                    currency: 'COP',
                                    maximumFractionDigits: 0
                                }
                            ).format(precioNumerico);


                        const totalFormateado =
                            new Intl.NumberFormat(
                                'es-CO',
                                {
                                    style: 'currency',
                                    currency: 'COP',
                                    maximumFractionDigits: 0
                                }
                            ).format(total);


                        /*
                        | Crear mensaje para WhatsApp
                        */

                        let mensaje =
                            'Hola, quiero realizar una compra en AUREN.';

                        mensaje += '\n\n';
                        mensaje += '[ICONO_PRODUCTO] *DATOS DEL PRODUCTO*';
                        mensaje += '\n';
                        mensaje += 'Producto: ' + producto;
                        mensaje += '\n';
                        mensaje += 'Referencia: ' + referencia;
                        mensaje += '\n';
                        mensaje += 'Talla: ' + talla;
                        mensaje += '\n';
                        mensaje += 'Cantidad: ' + cantidad;
                        mensaje += '\n';
                        mensaje += 'Precio unitario: ' + precioFormateado;
                        mensaje += '\n';
                        mensaje += 'Total: ' + totalFormateado;

                        mensaje += '\n\n';
                        mensaje += '[ICONO_CLIENTE] *DATOS DEL CLIENTE*';
                        mensaje += '\n';
                        mensaje += 'Nombre: ' + nombre;
                        mensaje += '\n';
                        mensaje += 'Teléfono: ' + telefono;
                        mensaje += '\n';
                        mensaje += 'Correo: ' + correo;

                        mensaje += '\n\n';
                        mensaje += '[ICONO_ENTREGA] *DATOS DE ENTREGA*';
                        mensaje += '\n';
                        mensaje += 'Ciudad: ' + ciudad;
                        mensaje += '\n';
                        mensaje += 'Barrio: ' + barrio;
                        mensaje += '\n';
                        mensaje += 'Dirección: ' + direccion;

                        mensaje += '\n\n';
                        mensaje += '[ICONO_PAGO] *FORMA DE PAGO*';
                        mensaje += '\n';
                        mensaje += formaPago;


                        if (observaciones) {

                            mensaje += '\n\n';
                            mensaje += '[ICONO_OBSERVACIONES] *OBSERVACIONES*';
                            mensaje += '\n';
                            mensaje += observaciones;

                        }


                        mensaje += '\n\n';
                        mensaje +=
                            'Quedo atento/a para continuar con el proceso de compra.';


                        /*
                        | Abrir WhatsApp
                        */

                        const mensajeCodificado =
                            encodeURIComponent(mensaje)
                                .replace(
                                    '%5BICONO_PRODUCTO%5D',
                                    '%F0%9F%9B%8D%EF%B8%8F'
                                )
                                .replace(
                                    '%5BICONO_CLIENTE%5D',
                                    '%F0%9F%91%A4'
                                )
                                .replace(
                                    '%5BICONO_ENTREGA%5D',
                                    '%F0%9F%93%A6'
                                )
                                .replace(
                                    '%5BICONO_PAGO%5D',
                                    '%F0%9F%92%B3'
                                )
                                .replace(
                                    '%5BICONO_OBSERVACIONES%5D',
                                    '%F0%9F%93%9D'
                                );


                        const urlWhatsApp =
                            'https://wa.me/' +
                            whatsappNumber +
                            '?text=' +
                            mensajeCodificado;


                        window.open(
                            urlWhatsApp,
                            '_blank'
                        );

                    }
                );

            }

        });
    </script>


</body>

</html>