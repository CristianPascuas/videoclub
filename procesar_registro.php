<?php

namespace VideoClub;

use VideoClub\Config\Configuration;
use VideoClub\Database\Connection;
use VideoClub\Herramientas\Key\CtrlRegistro;

if ($_POST) {
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
    $apellido = isset($_POST['apellido']) ? $_POST['apellido'] : '';
    $identificacion = isset($_POST['identificacion']) ? $_POST['identificacion'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $clave = isset($_POST['clave']) ? $_POST['clave'] : '';
    
    $conexion = Connection::connect();
    
    // Verificar si el email ya existe
    $verificar = "SELECT * FROM persona WHERE Email = ?";
    $stmt_verificar = mysqli_prepare($conexion, $verificar);
    mysqli_stmt_bind_param($stmt_verificar, 's', $email);
    mysqli_stmt_execute($stmt_verificar);
    $resultado_verificar = mysqli_stmt_get_result($stmt_verificar);
    
    if (mysqli_num_rows($resultado_verificar) > 0) {
        // El email ya existe
        header("Location: index.php?error=2");
        exit();
    }
    
    // Verificar si la identificación ya existe
    $verificar_id = "SELECT * FROM persona WHERE Identificacion = ?";
    $stmt_verificar_id = mysqli_prepare($conexion, $verificar_id);
    mysqli_stmt_bind_param($stmt_verificar_id, 's', $identificacion);
    mysqli_stmt_execute($stmt_verificar_id);
    $resultado_verificar_id = mysqli_stmt_get_result($stmt_verificar_id);
    
    if (mysqli_num_rows($resultado_verificar_id) > 0) {
        // La identificación ya existe
        header("Location: index.php?error=3");
        exit();
    }
    
    // Insertar nuevo usuario (Id_Rol = 5 para cliente, Id_Validacion = 1 para validado)
    $insertar = "INSERT INTO persona (Nombre, Apellido, Identificacion, Email, Clave, Id_Rol, Id_Validacion)
                 VALUES (?, ?, ?, ?, ?, 5, 1)";
    
    $stmt_insertar = mysqli_prepare($conexion, $insertar);
    mysqli_stmt_bind_param($stmt_insertar, 'sssss', $nombre, $apellido, $identificacion, $email, $clave);
    
    if (mysqli_stmt_execute($stmt_insertar)) {
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
