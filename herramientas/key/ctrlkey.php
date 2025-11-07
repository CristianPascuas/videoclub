<?php

namespace VideoClub\Herramientas\Key;

use VideoClub\Config\Configuration;
use VideoClub\Database\Connection;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_POST) {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    
    // Validar que el email existe en la base de datos
    $conexion = Connection::connect();
    $consulta = "SELECT * FROM persona WHERE Email = ?";
    
    $stmt = mysqli_prepare($conexion, $consulta);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($resultado) > 0) {
        // Generar nueva clave aleatoria
        $nueva_clave = generarClaveAleatoria();
        
        // Actualizar la clave en la base de datos
        $update = "UPDATE persona SET Clave = ? WHERE Email = ?";
        $stmt_update = mysqli_prepare($conexion, $update);
        mysqli_stmt_bind_param($stmt_update, 'ss', $nueva_clave, $email);
        
        if (mysqli_stmt_execute($stmt_update)) {
            // Enviar correo con la nueva clave
            if (enviarCorreoRecuperacion($email, $nueva_clave)) {
                header("Location: ../../index.php?recovery=success");
                exit();
            } else {
                header("Location: ../../index.php?recovery=error_email");
                exit();
            }
        } else {
            header("Location: ../../index.php?recovery=error_update");
            exit();
        }
    } else {
        header("Location: ../../index.php?recovery=error_notfound");
        exit();
    }
    
    mysqli_close($conexion);
} else {
    header("Location: ../../index.php");
    exit();
}

function generarClaveAleatoria($longitud = 8) {
    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $clave = '';
    // Generar clave aleatoria
    for ($i = 0; $i < $longitud; $i++) {
        $clave .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    return $clave;
}