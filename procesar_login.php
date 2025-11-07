<?php

namespace VideoClub;

use VideoClub\Config\Configuration;
use VideoClub\Database\Connection;

session_name(Configuration::SESSION_NAME);
session_start();

if ($_POST) {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $clave = isset($_POST['clave']) ? $_POST['clave'] : '';
    
    $conexion = Connection::connect();
    
    // Consultar usuario
    $consulta = "SELECT * FROM persona WHERE Email = ? AND Clave = ?";
    
    $stmt = mysqli_prepare($conexion, $consulta);
    mysqli_stmt_bind_param($stmt, 'ss', $email, $clave);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_array($resultado);
        
        // Verificar si el usuario está validado
        if ($usuario['Id_Validacion'] == 0) {
            // Usuario no validado
            header("Location: index.php?error=5");
            exit();
        }
        
        // Crear sesión
        $_SESSION['usuario_id'] = $usuario['Id_Persona'];
        $_SESSION['usuario_nombre'] = $usuario['Nombre'];
        $_SESSION['usuario_email'] = $usuario['Email'];
        $_SESSION['usuario_rol'] = $usuario['Id_Rol'];
        
        // Redirigir al dashboard
        header("Location: vista/menu.php");
        exit();
    } else {
        // Error de credenciales
        header("Location: index.php?error=1");
        exit();
    }
    
    mysqli_close($conexion);
} else {
    header("Location: index.php");
    exit();
}
