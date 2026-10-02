<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = $_SESSION['login_error'] ?? '';

unset($_SESSION['login_error']);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Acceso administrativo | CalzaSport</title>

    <link rel="stylesheet" href="assets/css/admin.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>

<body class="login-page">

    <main class="login-container">

        <section class="login-card">

            <!-- LOGO -->

            <div class="login-brand">

                <div class="login-logo">
                    <img src="assets/img/logo-oficial.png" alt="CalzaSport">
                </div>

                <p>
                    Panel administrativo
                </p>

            </div>


            <!-- ENCABEZADO -->

            <div class="login-heading">

                <span>
                    ADMINISTRACIÓN
                </span>

                <h1>
                    Bienvenido
                </h1>

                <p>
                    Ingresa tus credenciales para continuar.
                </p>

            </div>


            <!-- ERROR -->

            <?php if ($error): ?>

                <div class="login-error">

                    <i class="fa-solid fa-circle-exclamation"></i>

                    <span>
                        <?= htmlspecialchars($error) ?>
                    </span>

                </div>

            <?php endif; ?>


            <!-- FORMULARIO -->

            <form action="index.php?pagina=login&accion=autenticar" method="POST" class="login-form">

                <!-- USUARIO -->

                <div class="login-field">

                    <label for="usuario">
                        Usuario
                    </label>

                    <div class="login-input-wrapper">

                        <i class="fa-regular fa-user"></i>

                        <input type="text" id="usuario" name="usuario" autocomplete="username"
                            placeholder="Ingresa tu usuario" required>

                    </div>

                </div>


                <!-- CONTRASEÑA -->

                <div class="login-field">

                    <label for="password">
                        Contraseña
                    </label>

                    <div class="login-input-wrapper">

                        <i class="fa-solid fa-lock"></i>

                        <input type="password" id="password" name="password" autocomplete="current-password"
                            placeholder="Ingresa tu contraseña" required>

                        <button type="button" class="password-toggle" id="passwordToggle"
                            aria-label="Mostrar contraseña">

                            <i class="fa-regular fa-eye"></i>

                        </button>

                    </div>

                </div>


                <!-- BOTÓN -->

                <button type="submit" class="login-button">

                    <span>
                        Iniciar sesión
                    </span>

                    <i class="fa-solid fa-arrow-right"></i>

                </button>

            </form>


            <!-- PIE -->

            <div class="login-footer">

                <span>
                    CalzaSport
                </span>

                <span class="login-footer-separator">
                    ·
                </span>

                <span>
                    Administración
                </span>

            </div>

        </section>

    </main>


    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const password =
                    document.getElementById('password');

                const passwordToggle =
                    document.getElementById('passwordToggle');


                if (
                    !password ||
                    !passwordToggle
                ) {
                    return;
                }


                passwordToggle.addEventListener(
                    'click',
                    function () {

                        const isPassword =
                            password.type === 'password';


                        password.type =
                            isPassword
                                ? 'text'
                                : 'password';


                        const icon =
                            passwordToggle.querySelector('i');


                        if (icon) {

                            icon.className =
                                isPassword
                                    ? 'fa-regular fa-eye-slash'
                                    : 'fa-regular fa-eye';

                        }


                        passwordToggle.setAttribute(
                            'aria-label',
                            isPassword
                                ? 'Ocultar contraseña'
                                : 'Mostrar contraseña'
                        );

                    }
                );

            }
        );

    </script>

</body>

</html>