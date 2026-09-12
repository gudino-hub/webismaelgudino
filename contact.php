<?php
    // Configuración
    $to      = 'ismael.gudino@gmail.com';
    $subject = 'Mensaje desde ismaelgudino.com';

    function errorHandler($message) {
        die(json_encode([
            'type'     => 'error',
            'response' => $message
        ]));
    }

    function successHandler($message) {
        die(json_encode([
            'type'     => 'success',
            'response' => $message
        ]));
    }

    // Verificamos que la petición sea AJAX
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        
        // Campos recibidos
        $name    = isset($_POST['name']) ? $_POST['name'] : '';
        $email   = isset($_POST['email']) ? $_POST['email'] : '';
        $message = isset($_POST['message']) ? $_POST['message'] : '';

        // Sanitización
        $name    = filter_var($name, FILTER_SANITIZE_STRING);
        $email   = filter_var($email, FILTER_SANITIZE_EMAIL);
        $message = filter_var($message, FILTER_SANITIZE_STRING);

        // Validaciones
        if (!$name) {
            errorHandler('Por favor escribe tu nombre.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            errorHandler('Por favor escribe un email válido.');
        }
        if (!$message) {
            errorHandler('Por favor escribe tu mensaje.');
        }

        // Protección contra inyección en cabeceras
        $pattern = '/[\r\n]|Content-Type:|Bcc:|Cc:/i';
        if (preg_match($pattern, $name) || preg_match($pattern, $email)) {
            errorHandler('Header injection detectado.');
        }

        // Cabeceras
        $headers  = "MIME-Version: 1.0" . PHP_EOL;
        $headers .= "Content-type: text/html; charset=utf-8" . PHP_EOL;
        $headers .= "From: no-reply@ismaelgudino.com" . PHP_EOL; // seguro
        $headers .= "Reply-To: $email" . PHP_EOL;
        $headers .= "X-Mailer: PHP/" . phpversion() . PHP_EOL;

        // Cuerpo del mensaje
        $body  = "<strong>De:</strong> $name<br>";
        $body .= "<strong>Email:</strong> $email<br>";
        $body .= "<strong>Mensaje:</strong><br>" . nl2br($message);

        // Envío
        if (mail($to, $subject, $body, $headers)) {
            successHandler('¡Gracias! Tu mensaje fue enviado correctamente.');
        } else {
            errorHandler('Lo sentimos, hubo un error al enviar tu mensaje.');
        }

    } else {
        errorHandler('Solo se permiten solicitudes AJAX.');
    }
?>
