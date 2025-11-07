<?php
require_once __DIR__ . '/../../include/conex.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/key.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function enviarCorreoBienvenida($email_destino, $nombre, $apellido) {
    global $email_remitente, $password_email;
    $mail = new PHPMailer(true);
    $rol_nombre = 'Cliente'; // Valor por defecto
    // Consultar el nombre del rol desde la base de datos
    $con = Conectarse();
    $sql = "SELECT Rol FROM rol WHERE Id_Rol = 5";
    $res = mysqli_query($con, $sql);
    if ($res && $row = mysqli_fetch_assoc($res)) {
        $rol_nombre = $row['Rol'];
    }
    mysqli_close($con);
    try {
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $email_remitente;
        $mail->Password   = $password_email;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        // Configuración del correo
        $mail->setFrom($email_remitente, 'VideoClub');
        $mail->addAddress($email_destino);
        $mail->isHTML(true);
        $mail->Subject = '¡Bienvenido a VideoClub!';
        $mail->Body    = '
        <h2>Cuenta creada exitosamente</h2>
        <p>Hola, ' . $nombre . ' ' . $apellido . '</p>
        <p>Has creado exitosamente una cuenta en nuestro Videoclub:</p>
        <h3 style="color: #007bff; background: #f8f9fa; padding: 10px; border-radius: 5px;">' . $email_destino . '</h3>
        <p>Con el rol:</p>
        <h4 style="color: #007bff; background: #f8f9fa; padding: 10px; border-radius: 5px;">' . $rol_nombre . '</h4>
        <p>Espero disfrutes de nuestros servicios.</p>
        <p>Saludos,<br>Equipo VideoClub</p>
        ';
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>