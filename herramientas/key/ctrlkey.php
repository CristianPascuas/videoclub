<?php
require_once '../../include/conex.php';
require_once __DIR__ . '/../herramientas/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../herramientas/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../herramientas/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../herramientas/key/key.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_POST) {
    $email = $_POST['email'];
    
    // Validar que el email existe en la base de datos
    $conexion = Conectarse();
    $consulta = "SELECT * FROM persona WHERE Email = '$email'";
    $resultado = mysqli_query($conexion, $consulta);
    
    if (mysqli_num_rows($resultado) > 0) {
        // Generar nueva clave aleatoria
        $nueva_clave = generarClaveAleatoria();
        
        // Actualizar la clave en la base de datos
        $update = "UPDATE persona SET Clave = '$nueva_clave' WHERE Email = '$email'";
        if (mysqli_query($conexion, $update)) {
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
?>