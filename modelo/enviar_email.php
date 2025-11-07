<?php
//__DIR__ Es un constante predefinida de php, que lo que hace es devolver el directorio donde se encuentra el script
require_once __DIR__ . '/../herramientas/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../herramientas/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../herramientas/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../herramientas/key/key.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function enviarCorreoRecuperacion($email_destino, $nueva_clave) {
    global $email_remitente, $password_email;
    
    $mail = new PHPMailer(true);
    
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
        $mail->Subject = 'Recuperación de Contraseña - VideoClub';
        $mail->Body    = '
        <h2>Recuperación de Contraseña</h2>
        <p>Hola,</p>
        <p>Has solicitado recuperar tu contraseña. Tu nueva contraseña es:</p>
        <h3 style="color: #007bff; background: #f8f9fa; padding: 10px; border-radius: 5px;">' . $nueva_clave . '</h3>
        <p>Por favor, cambia esta contraseña después de iniciar sesión.</p>
        <p>Saludos,<br>Equipo VideoClub</p>
        ';
        
        $mail->send();
        return true;
        
    } catch (Exception $e) {
        return false;
    }
}
?>