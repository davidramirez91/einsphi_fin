<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $nombre = $_POST["nombre"];
    $email = $_POST["email"];
    $mensaje = $_POST["mensaje"];

    // Configura el correo a enviar
    $destinatario = "dramirez91@einsphi.com, ecardenasv@einsphi.com"; // Cambia esto por tu dirección de correo
    $asunto = "Nuevo mensaje de $nombre";
    $contenido = "Nombre: $nombre\n";
    $contenido .= "Correo: $email\n\n";
    $contenido .= "Mensaje:\n$mensaje";

    $cabeceras = "From: $email";

    // Envía el correo
    if (mail($destinatario, $asunto, $contenido, $cabeceras)) {
        echo "Mensaje enviado exitosamente";
    } else {
        echo "Error al enviar el mensaje";
    }
}
?>