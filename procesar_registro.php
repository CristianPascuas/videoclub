<?php
require_once 'include/conex.php';
require_once 'herramientas/key/ctrlregistro.php';

if ($_POST) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $identificacion = $_POST['identificacion'];
    $email = $_POST['email'];
    $clave = $_POST['clave'];
    
    $conexion = Conectarse();
    
    // Verificar si el email ya existe
    $verificar = "SELECT * FROM persona WHERE Email = '$email'";
    $resultado_verificar = mysqli_query($conexion, $verificar);
    
    if (mysqli_num_rows($resultado_verificar) > 0) {
        // El email ya existe
        header("Location: index.php?error=2");
        exit();
    }
    
    // Verificar si la identificación ya existe
    $verificar_id = "SELECT * FROM persona WHERE Identificacion = '$identificacion'";
    $resultado_verificar_id = mysqli_query($conexion, $verificar_id);
    
    if (mysqli_num_rows($resultado_verificar_id) > 0) {
        // La identificación ya existe
        header("Location: index.php?error=3");
        exit();
    }
    
    // Insertar nuevo usuario (Id_Rol = 5 para cliente, Id_Validacion = 1 para validado)
    $insertar = "INSERT INTO persona (Nombre, Apellido, Identificacion, Email, Clave, Id_Rol, Id_Validacion) 
                 VALUES ('$nombre', '$apellido', '$identificacion', '$email', '$clave', 5, 1)";
    
    if (mysqli_query($conexion, $insertar)) {
        // Registro exitoso - Enviar correo de bienvenida
        $correo_enviado = enviarCorreoBienvenida($email, $nombre, $apellido);
        
        if ($correo_enviado) {
            // Registro exitoso con correo enviado
            header("Location: index.php?success=1&email=sent");
        } else {
            // Registro exitoso pero error en el envío del correo
            header("Location: index.php?success=1&email=error");
        }
        exit();
    } else {
        // Error al registrar
        header("Location: index.php?error=4");
        exit();
    }
    
    mysqli_close($conexion);
} else {
    header("Location: index.php");
    exit();
}
?>
