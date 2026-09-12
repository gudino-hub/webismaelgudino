<?php
if (mail("ismael.gudino@gmail.com", "Prueba", "Hola, este es un test")) {
    echo "Correo enviado correctamente";
} else {
    echo "Error al enviar correo";
}
?>