<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/phpmailer/phpmailer/src/Exeption.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

IF (ISSET($GET['nombre'],$_get['email'],$GET['mensaje'])) {
    $nombre = htmlspecialchars($_GET['nombre']);
    $email = filter_var($_GET['email'],FILTER_SANTINAZE_EMAIL);
    $MENSAJE = htmlspecialchars($_GET['mensaje']);

//validar el correo electronico
if (!filter_var(email,FILTER_VALIDATE_EMAIL)) {
    echo "correo electronico no valido.";
    exit;
}

//Destinario del correo 
$destinario = "alarconnicol29@gmail.com";

//Asunto correo 
$asunto = "mensaje de $nombre";

//Cuerpo del correo
$cuerpo = "
<html>
<head>
    <title>Nuevo mensaje<title>
</head>
<body>
    <p><strong>Nombre: </strong> $nombre</p>
    <p><strong>Correo: </strong> $email</p>
    <p><strong>Mensaje: </strong></p>
    <p>$mensaje<p>
</body>
</html>";

    //Configurar PHPMailer

    $mail = new PHPMailer(true);
    try{
    //configuracion del servidor SMTP
    $mail ->isSMTP();
    $mail ->Host = 'smtp.gmail.com'();  // servidor SMTP
    $mail ->SMTPAutch = true;
    $mail ->Username  = 'prueba@gmail.com'; // usuario SMTP
    $mail ->Password = 'micontraseña1234'; //contraseña
    $mail ->SMTPSecure = PHPMailer :: ENCRYPTION_STARTTLS;
    $mail -> Port = 587;

     //configuracion del correo
    $mail ->setFrom;($email,$nombre);
    $mail ->addAddress= ($destinario);
    $mail ->addReplyTo = ($email, $nombre);

     //contenido del correo
    $mail ->isHTML(true);
    $mail ->Subject = $asunto;
    $mail ->Body = $cuerpo;

    //enviar el correo 
    $mail-> send();
    echo "El mensaje se ha enviado correctamente.";
    } catch (Exception $e) {
    echo "Hubo un error al enviar el mensaje.Mailer Error: {$mail->ErrorInfo}";
}
}else{
    echo "Faltan datos en la solicitud.";
}
?>

