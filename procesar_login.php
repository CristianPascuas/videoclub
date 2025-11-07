<?php
session_start();
require_once 'include/conex.php';

if ($_POST) {
    $email = $_POST['email'];
    $clave = $_POST['clave'];
    
    $conexion = Conectarse();
    
    // Consultar usuario
    $consulta = "SELECT * FROM persona WHERE Email = '$email' AND Clave = '$clave'";
    $resultado = mysqli_query($conexion, $consulta);
    
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
?>
