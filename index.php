<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VideoClub - Inicio de Sesión</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="shortcut icon" href="img/mdb-favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <!-- Header con logo -->
            <div class="header-section">
                <div class="logo">
                    <img src="img/logo.png" alt="VideoClub Logo">
                </div>
                <h1>VideoClub</h1>
            </div>
            
            <?php
            // Mostrar mensajes de error o éxito
            if (isset($_GET['error'])) {
                switch ($_GET['error']) {
                    case 1:
                        echo '<div class="alert alert-error">Credenciales incorrectas.</div>';
                        break;
                    case 2:
                        echo '<div class="alert alert-error">El email ya está registrado.</div>';
                        break;
                    case 3:
                        echo '<div class="alert alert-error">La identificación ya está registrada.</div>';
                        break;
                    case 4:
                        echo '<div class="alert alert-error">Error al registrar usuario.</div>';
                        break;
                    case 5:
                        echo '<div class="alert alert-error">Tu cuenta no ha sido validada. Contacta al administrador.</div>';
                        break;
                }
            }
            
            if (isset($_GET['success'])) {
                switch ($_GET['success']) {
                    case 1:
                        $mensaje = 'Usuario registrado correctamente. Ya puedes iniciar sesión.';
                        if (isset($_GET['email'])) {
                            if ($_GET['email'] == 'sent') {
                                $mensaje .= ' Se ha enviado un correo de bienvenida a tu email.';
                            } elseif ($_GET['email'] == 'error') {
                                $mensaje .= ' (Nota: Hubo un problema al enviar el correo de bienvenida, pero tu cuenta se creó correctamente)';
                            }
                        }
                        echo '<div class="alert alert-success">' . $mensaje . '</div>';
                        break;
                }
            }
            
            // Mensajes de recuperación de contraseña
            if (isset($_GET['recovery'])) {
                switch ($_GET['recovery']) {
                    case 'success':
                        echo '<div class="alert alert-success">Se ha enviado una nueva contraseña a tu correo electrónico.</div>';
                        break;
                    case 'error_email':
                        echo '<div class="alert alert-error">Error al enviar el correo. Inténtalo de nuevo.</div>';
                        break;
                    case 'error_update':
                        echo '<div class="alert alert-error">Error al actualizar la contraseña.</div>';
                        break;
                    case 'error_notfound':
                        echo '<div class="alert alert-error">El correo electrónico no está registrado.</div>';
                        break;
                }
            }
            ?>
            
            <!-- Formulario de Login -->
            <div id="loginForm" class="form-section active">
                <h2>Iniciar Sesión</h2>
                <form action="procesar_login.php" method="POST">
                    <div class="form-group">
                        <label for="email_login" class="icon-email">Email:</label>
                        <input type="email" id="email_login" name="email" required placeholder="Ingresa tu email">
                    </div>
                    <div class="form-group">
                        <label for="clave_login" class="icon-password">Contraseña:</label>
                        <input type="password" id="clave_login" name="clave" required placeholder="Ingresa tu contraseña">
                    </div>
                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                </form>
                
                <div class="form-links">
                    <a href="#" onclick="mostrarRecuperacion(); limpiarCampos();">¿Has olvidado tu contraseña?</a>
                    <a href="#" onclick="mostrarRegistro(); limpiarCampos();">¿No tienes cuenta? Regístrate</a>
                </div>
            </div>
            
            <!-- Formulario de Registro -->
            <div id="registerForm" class="form-section">
                <h2>Registrarse</h2>
                <form action="procesar_registro.php" method="POST">
                    <div class="form-group">
                        <label for="nombre" class="icon-user">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" required placeholder="Tu nombre">
                    </div>
                    <div class="form-group">
                        <label for="apellido" class="icon-user">Apellido:</label>
                        <input type="text" id="apellido" name="apellido" required placeholder="Tu apellido">
                    </div>
                    <div class="form-group">
                        <label for="identificacion" class="icon-id">Identificación:</label>
                        <input type="text" id="identificacion" name="identificacion" required placeholder="Número de identificación">
                    </div>
                    <div class="form-group">
                        <label for="email_registro" class="icon-email">Email:</label>
                        <input type="email" id="email_registro" name="email" required placeholder="Tu email">
                    </div>
                    <div class="form-group">
                        <label for="clave_registro" class="icon-password">Contraseña:</label>
                        <input type="password" id="clave_registro" name="clave" required placeholder="Crea una contraseña">
                    </div>
                    <button type="submit" class="btn btn-primary">Registrarse</button>
                </form>
                
                <div class="form-links">
                    <a href="#" onclick="mostrarLogin(); limpiarCampos();">¿Ya tienes cuenta? Inicia sesión</a>
                </div>
            </div>
            
            <!-- Formulario de Recuperación -->
            <div id="recoveryForm" class="form-section">
                <h2>Recuperar Contraseña</h2>
                <form action="herramientas/key/ctrlkey.php" method="POST">
                    <div class="form-group">
                        <label for="email_recovery" class="icon-email">Email:</label>
                        <input type="email" id="email_recovery" name="email" required placeholder="Ingresa tu email registrado">
                    </div>
                    <button type="submit" class="btn btn-primary">Enviar Nueva Contraseña</button>
                </form>
                
                <div class="form-links">
                    <a href="#" onclick="mostrarLogin(); limpiarCampos();">Volver al inicio de sesión</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = limpiarCampos();
        function mostrarFormulario(formularioActivo) {
            // Ocultar todos los formularios
            const formularios = ['loginForm', 'registerForm', 'recoveryForm'];
            
            formularios.forEach(form => {
                const elemento = document.getElementById(form);
                if (form === formularioActivo) {
                    elemento.classList.add('active');
                } else {
                    elemento.classList.remove('active');
                }
            });
        }
        
        function mostrarLogin() {
            mostrarFormulario('loginForm');
        }
        
        function mostrarRegistro() {
            mostrarFormulario('registerForm');
        }
        
        function mostrarRecuperacion() {
            mostrarFormulario('recoveryForm');
        }

        function limpiarCampos() {
            const inputs = document.querySelectorAll('input');
            // Limpiar todos los campos de entrada usando 
            inputs.forEach(input => {
                input.value = '';
            });
        }

        // Efecto de enfoque en inputs
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>