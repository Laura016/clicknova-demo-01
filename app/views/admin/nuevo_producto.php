<?php

$mensajeError = '';

if (
    isset($_GET['error']) &&
    $_GET['error'] === 'referencia'
) {

    $mensajeError =
        'La referencia ingresada ya está registrada. '
        . 'Por favor utiliza una referencia diferente.';

}

if (
    isset($_GET['error']) &&
    $_GET['error'] === 'tallas'
) {

    $mensajeError =
        'Debes seleccionar al menos una talla '
        . 'antes de guardar el producto.';

}

if (
    isset($_GET['error']) &&
    $_GET['error'] === 'imagen_formato'
) {

    $mensajeError =
        'Una de las fotografías tiene un formato no permitido. '
        . 'Utiliza JPG, JPEG, PNG o WEBP.';

}

if (
    isset($_GET['error']) &&
    $_GET['error'] === 'imagen_tamano'
) {

    $mensajeError =
        'Una de las fotografías supera el tamaño máximo permitido '
        . 'de 5 MB por imagen.';

}

if (
    isset($_GET['error']) &&
    $_GET['error'] === 'imagen_subida'
) {

    $mensajeError =
        'No fue posible cargar una de las fotografías. '
        . 'Intenta nuevamente.';

}

$titulo = "Nuevo producto";

require_once __DIR__ . "/../layouts/admin_header.php";
require_once __DIR__ . "/../layouts/admin_sidebar.php";

?>

<main class="admin-main">


    <!-- =========================================
         TOPBAR
    ========================================== -->

    <header class="admin-topbar">

        <div class="page-title">

            <!-- BREADCRUMB -->

            <div class="breadcrumb">

                <a href="index.php?pagina=admin">
                    Dashboard
                </a>

                <span>/</span>

                <a href="index.php?pagina=productos">
                    Productos
                </a>

                <span>/</span>

                <span>Nuevo producto</span>

            </div>


            <!-- TÍTULO -->

            <h1>
                Nuevo producto
            </h1>

            <p>
                Agrega un nuevo producto al catálogo.
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
         FORMULARIO
    ========================================== -->

    <form action="index.php?pagina=guardar_producto" method="POST" enctype="multipart/form-data" class="admin-form">
        <?php if (!empty($mensajeError)): ?>

            <div class="form-alert form-alert-error">

                <div class="form-alert-icon">

                    <i class="fa-solid fa-circle-exclamation"></i>

                </div>

                <div class="form-alert-content">

                    <strong>
                        No se pudo guardar el producto
                    </strong>

                    <span>
                        <?= htmlspecialchars($mensajeError) ?>
                    </span>

                </div>

            </div>

        <?php endif; ?>


        <!-- =====================================
             INFORMACIÓN PRINCIPAL
        ====================================== -->

        <section class="admin-section">

            <div class="section-header">

                <div>

                    <h2>
                        Información del producto
                    </h2>

                    <p>
                        Completa los datos principales del producto.
                    </p>

                </div>


                <div class="section-actions">

                    <a href="index.php?pagina=productos" class="btn btn-secondary">

                        <i class="fa-solid fa-arrow-left"></i>

                        Productos

                    </a>

                </div>

            </div>


            <div class="form-grid">


                <!-- NOMBRE -->

                <div class="form-group form-group-full">

                    <label for="nombre">
                        Nombre del producto
                        <span class="required">*</span>
                    </label>

                    <input type="text" id="nombre" name="nombre" placeholder="Ej. Camisa Oversize Essential" required>

                </div>


                <!-- REFERENCIA -->

                <div class="form-group">

                    <label for="referencia">
                        Referencia
                        <span class="required">*</span>
                    </label>

                    <input type="text" id="referencia" name="referencia" placeholder="Ej. AR-001" required>

                </div>


                <!-- MARCA -->

                <div class="form-group">

                    <label for="marca">
                        Marca
                    </label>

                    <input type="text" id="marca" name="marca" placeholder="Ej. AUREN">

                </div>


                <!-- CATEGORÍA -->

                <div class="form-group">

                    <label for="categoria_id">
                        Categoría
                        <span class="required">*</span>
                    </label>

                    <select id="categoria_id" name="categoria_id" required>

                        <option value="">
                            Selecciona una categoría
                        </option>

                        <?php foreach ($categorias as $categoria): ?>

                            <option value="<?= $categoria['id'] ?>">

                                <?= htmlspecialchars(
                                    $categoria['nombre']
                                ) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <!-- PRECIO -->

                <div class="form-group">

                    <label for="precio">
                        Precio
                        <span class="required">*</span>
                    </label>

                    <div class="input-prefix">

                        <span>
                            $
                        </span>

                        <input type="number" id="precio" name="precio" min="0" step="1" placeholder="0" required>

                    </div>

                </div>


            </div>

        </section>



        <!-- =====================================
             TALLAS
        ====================================== -->

        <section class="admin-section">

            <div class="section-header">

                <div>

                    <h2>
                        Tallas disponibles
                    </h2>

                    <p>
                        Selecciona las tallas disponibles para este producto.
                    </p>

                </div>

            </div>


            <?php

            $tallasDisponibles = [
                'XS',
                'S',
                'M',
                'L',
                'XL',
                'XXL'
            ];

            ?>


            <div class="sizes-grid">


                <?php foreach ($tallasDisponibles as $talla): ?>

                    <label class="size-option">

                        <input type="checkbox" name="tallas[]" value="<?= $talla ?>">

                        <span>
                            <?= $talla ?>
                        </span>

                    </label>

                <?php endforeach; ?>


            </div>

        </section>



        <!-- =====================================
             DESCRIPCIÓN Y ESPECIFICACIONES
        ====================================== -->

        <section class="admin-section">

            <div class="section-header">

                <div>

                    <h2>
                        Descripción del producto
                    </h2>

                    <p>
                        Agrega información que ayude al cliente a conocer el producto.
                    </p>

                </div>

            </div>


            <div class="form-grid">


                <!-- DESCRIPCIÓN -->

                <div class="form-group form-group-full">

                    <label for="descripcion">
                        Descripción
                    </label>

                    <textarea id="descripcion" name="descripcion" rows="5"
                        placeholder="Describe el producto, su estilo, características principales..."></textarea>

                </div>


                <!-- ESPECIFICACIONES -->

                <div class="form-group form-group-full">

                    <label for="especificaciones">
                        Especificaciones
                    </label>

                    <textarea id="especificaciones" name="especificaciones" rows="5"
                        placeholder="Ej. Material, composición, corte, ajuste, cuidados..."></textarea>

                </div>


            </div>

        </section>



        <!-- =====================================
             ESTADO DEL PRODUCTO
        ====================================== -->

        <section class="admin-section">

            <div class="section-header">

                <div>

                    <h2>
                        Visibilidad del producto
                    </h2>

                    <p>
                        Define cómo aparecerá el producto dentro del catálogo.
                    </p>

                </div>

            </div>


            <div class="checkbox-options">

                <!-- ACTIVO -->

                <label class="checkbox-option">

                    <input type="checkbox" name="estado" value="1" checked>

                    <span class="checkbox-custom"></span>

                    <span class="checkbox-content">

                        <strong>
                            Producto activo
                        </strong>

                        <small>
                            El producto estará disponible en el catálogo.
                        </small>

                    </span>

                </label>


            </div>

        </section>



        <!-- =====================================
             FOTOGRAFÍAS
        ====================================== -->

        <section class="admin-section">

            <div class="section-header">

                <div>

                    <h2>
                        Fotografías del producto
                    </h2>

                    <p>
                        Puedes seleccionar varias fotografías del producto.
                    </p>

                </div>

            </div>


            <div class="upload-box">


                <div class="upload-icon">

                    <i class="fa-regular fa-images"></i>

                </div>


                <div class="upload-content">

                    <strong>
                        Agrega las fotografías del producto
                    </strong>

                    <span>
                        JPG, JPEG, PNG o WEBP · Máximo 5 MB por imagen
                    </span>

                </div>


                <label for="imagenes" class="btn btn-secondary upload-button">

                    <i class="fa-solid fa-cloud-arrow-up"></i>

                    Seleccionar imágenes

                </label>


                <input type="file" id="imagenes" name="imagenes[]" accept=".jpg,.jpeg,.png,.webp" multiple hidden>

            </div>


            <!-- VISTA PREVIA -->

            <div id="preview-container" class="admin-gallery preview-gallery"></div>


        </section>



        <!-- =====================================
             BOTONES FINALES
        ====================================== -->

        <div class="form-footer">


            <a href="index.php?pagina=productos" class="btn btn-secondary">

                <i class="fa-solid fa-xmark"></i>

                Cancelar

            </a>


            <button type="submit" class="btn btn-primary">

                <i class="fa-solid fa-floppy-disk"></i>

                Guardar producto

            </button>


        </div>


    </form>


</main>


</div>



<!-- =========================================
     VISTA PREVIA DE IMÁGENES
========================================== -->

<script>

    const inputImagenes =
        document.getElementById('imagenes');

    const previewContainer =
        document.getElementById('preview-container');


    if (inputImagenes) {

        inputImagenes.addEventListener(
            'change',
            function () {

                previewContainer.innerHTML = '';


                const archivos =
                    Array.from(this.files);


                if (archivos.length === 0) {

                    return;

                }


                archivos.forEach(function (archivo) {


                    const reader =
                        new FileReader();


                    reader.onload =
                        function (evento) {


                            const item =
                                document.createElement('div');

                            item.className =
                                'gallery-item';


                            const wrapper =
                                document.createElement('div');

                            wrapper.className =
                                'gallery-image-wrapper';


                            const imagen =
                                document.createElement('img');

                            imagen.src =
                                evento.target.result;

                            imagen.alt =
                                'Vista previa';


                            wrapper.appendChild(imagen);


                            item.appendChild(wrapper);


                            previewContainer.appendChild(item);

                        };


                    reader.readAsDataURL(archivo);

                });

            }
        );

    }

</script>



</body>

</html>