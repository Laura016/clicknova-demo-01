<?php

$titulo = "Editar producto";

$mensajeError = '';

if (
    isset($_GET['error']) &&
    $_GET['error'] === 'referencia'
) {
    $mensajeError =
        'La referencia ingresada ya está registrada en otro producto. '
        . 'Por favor utiliza una referencia diferente.';
}

if (
    isset($_GET['error']) &&
    $_GET['error'] === 'tallas'
) {
    $mensajeError =
        'Debes seleccionar al menos una talla '
        . 'antes de guardar los cambios.';
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

require_once __DIR__ . "/../layouts/admin_header.php";
require_once __DIR__ . "/../layouts/admin_sidebar.php";

?>

<main class="admin-main">

    <!-- TOPBAR -->

    <header class="admin-topbar">

        <div class="page-title">

            <div class="breadcrumb">

                <a href="index.php?pagina=admin">
                    Dashboard
                </a>

                <span>/</span>

                <a href="index.php?pagina=productos">
                    Productos
                </a>

                <span>/</span>

                <span>Editar producto</span>

            </div>

            <h1>Editar producto</h1>

            <p>
                Actualiza la información de tu producto.
            </p>

        </div>


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


    <!-- FORMULARIO -->

    <form action="index.php?pagina=actualizar_producto" method="POST" enctype="multipart/form-data" class="admin-form">

        <input type="hidden" name="id" value="<?= $producto['id'] ?>">


        <!-- MENSAJE DE ERROR -->

        <?php if (!empty($mensajeError)): ?>

            <div class="form-alert form-alert-error">

                <div class="form-alert-icon">

                    <i class="fa-solid fa-circle-exclamation"></i>

                </div>

                <div class="form-alert-content">

                    <strong>
                        No se pudo actualizar el producto
                    </strong>

                    <span>
                        <?= htmlspecialchars($mensajeError) ?>
                    </span>

                </div>

            </div>

        <?php endif; ?>


        <!-- INFORMACIÓN DEL PRODUCTO -->

        <section class="admin-section">

            <div class="section-header">

                <div>

                    <h2>
                        Información del producto
                    </h2>

                    <p>
                        Datos principales del producto.
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

                <div class="form-group">

                    <label for="nombre">
                        Nombre del producto
                        <span class="required">*</span>
                    </label>

                    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars(
                        $producto['nombre']
                    ) ?>" required>

                </div>


                <!-- REFERENCIA -->

                <div class="form-group">

                    <label for="referencia">

                        Referencia

                        <span class="required">*</span>

                    </label>

                    <input type="text" id="referencia" name="referencia" value="<?= htmlspecialchars(
                        $producto['referencia']
                    ) ?>" required>

                </div>


                <!-- MARCA -->

                <div class="form-group">

                    <label for="marca">
                        Marca
                    </label>

                    <input type="text" id="marca" name="marca" value="<?= htmlspecialchars(
                        $producto['marca'] ?? ''
                    ) ?>">

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

                            <option value="<?= $categoria['id'] ?>" <?= $producto['categoria_id'] == $categoria['id']
                                  ? 'selected'
                                  : '' ?>>

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

                        <span>$</span>

                        <input type="number" id="precio" name="precio" min="0" step="100" value="<?= htmlspecialchars(
                            $producto['precio']
                        ) ?>" required>

                    </div>

                </div>


                <!-- ESTADO -->

                <div class="form-group">

                    <label>
                        Estado
                    </label>

                    <label class="checkbox-option">

                        <input type="checkbox" name="estado" value="1" <?= $producto['estado'] == 1
                            ? 'checked'
                            : '' ?>>

                        <span class="checkbox-custom"></span>

                        <span class="checkbox-content">

                            <strong>
                                Producto activo
                            </strong>

                            <small>
                                El producto aparecerá en el catálogo.
                            </small>

                        </span>

                    </label>

                </div>


                <!-- TALLAS -->

                <div class="form-group form-group-full">

                    <label>
                        Tallas disponibles
                    </label>

                    <div class="sizes-grid">

                        <?php

                        $tallasProducto = [];

                        foreach ($tallas as $talla) {

                            $tallasProducto[] =
                                (string) $talla['talla'];

                        }

                        ?>

                        <?php foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $tallaDisponible): ?>

                            <label class="size-option">

                                <input type="checkbox" name="tallas[]" value="<?= $tallaDisponible ?>" <?= in_array(
                                      $tallaDisponible,
                                      $tallasProducto
                                  )
                                      ? 'checked'
                                      : '' ?>>

                                <span>
                                    <?= $tallaDisponible ?>
                                </span>

                            </label>

                        <?php endforeach; ?>

                    </div>

                </div>


                <!-- DESCRIPCIÓN -->

                <div class="form-group form-group-full">

                    <label for="descripcion">
                        Descripción
                    </label>

                    <textarea id="descripcion" name="descripcion" rows="5" placeholder="Describe el producto..."><?= htmlspecialchars(
                        $producto['descripcion'] ?? ''
                    ) ?></textarea>

                </div>


                <!-- ESPECIFICACIONES -->

                <div class="form-group form-group-full">

                    <label for="especificaciones">
                        Especificaciones
                    </label>

                    <textarea id="especificaciones" name="especificaciones" rows="5"
                        placeholder="Material, composición, corte, ajuste, cuidados, etc."><?= htmlspecialchars(
                            $producto['especificaciones'] ?? ''
                        ) ?></textarea>

                </div>

            </div>

        </section>


        <!-- GALERÍA -->

        <section class="admin-section">

            <div class="section-header">

                <div>

                    <h2>
                        Fotografías del producto
                    </h2>

                    <p>
                        Administra las imágenes actuales y agrega nuevas fotografías.
                    </p>

                </div>

            </div>


            <?php if (!empty($imagenes)): ?>

                <div class="admin-gallery">

                    <?php foreach ($imagenes as $imagen): ?>

                        <?php

                        $esPrincipal =
                            isset($producto['imagen_principal']) &&
                            $producto['imagen_principal'] ===
                            $imagen['imagen'];

                        ?>

                        <div class="gallery-item">


                            <div class="gallery-image-wrapper">

                                <img src="assets/img/productos/<?= htmlspecialchars(
                                    $imagen['imagen']
                                ) ?>" alt="Imagen del producto">


                                <?php if ($esPrincipal): ?>

                                    <span class="gallery-main-badge">

                                        <i class="fa-solid fa-star"></i>

                                        Principal

                                    </span>

                                <?php endif; ?>

                            </div>


                            <div class="gallery-actions">


                                <?php if (!$esPrincipal): ?>

                                    <a href="index.php?pagina=principal_imagen&imagen_id=<?= $imagen['id'] ?>&producto_id=<?= $producto['id'] ?>"
                                        class="gallery-btn gallery-main" title="Establecer como imagen principal">

                                        <i class="fa-solid fa-star"></i>

                                        Principal

                                    </a>

                                <?php else: ?>

                                    <span class="gallery-current">

                                        <i class="fa-solid fa-check"></i>

                                        Imagen principal

                                    </span>

                                <?php endif; ?>


                                <a href="index.php?pagina=eliminar_imagen&id=<?= $imagen['id'] ?>"
                                    class="gallery-btn gallery-delete" title="Eliminar imagen"
                                    onclick="return confirm('¿Seguro que deseas eliminar esta imagen?');">

                                    <i class="fa-solid fa-trash"></i>

                                    Eliminar

                                </a>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="gallery-empty">

                    <i class="fa-regular fa-images"></i>

                    <p>
                        Este producto todavía no tiene imágenes.
                    </p>

                </div>

            <?php endif; ?>


            <!-- AGREGAR IMÁGENES -->

            <div class="upload-box">

                <div class="upload-icon">

                    <i class="fa-solid fa-cloud-arrow-up"></i>

                </div>


                <div class="upload-content">

                    <strong>
                        Agregar nuevas fotografías
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


            <div id="preview-container" class="admin-gallery preview-gallery"></div>

        </section>


        <!-- ACCIONES -->

        <div class="form-footer">

            <a href="index.php?pagina=productos" class="btn btn-secondary">

                <i class="fa-solid fa-xmark"></i>

                Cancelar

            </a>


            <button type="submit" class="btn btn-primary">

                <i class="fa-solid fa-floppy-disk"></i>

                Guardar cambios

            </button>

        </div>

    </form>

</main>

</div>


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


                archivos.forEach(
                    function (archivo) {

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


                                wrapper.appendChild(
                                    imagen
                                );

                                item.appendChild(
                                    wrapper
                                );

                                previewContainer.appendChild(
                                    item
                                );

                            };


                        reader.readAsDataURL(
                            archivo
                        );

                    }
                );

            }
        );

    }

</script>


</body>

</html>