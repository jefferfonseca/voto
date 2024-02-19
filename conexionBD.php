<?php
function conectarse()
{
    $db_host = "localhost"; // Host BD al que conectarse, habitualmente es localhost
    $db_nombre = "educovota"; // Nombre de la Base de Datos que se desea utilizar
    $db_user = "educolibre"; // Nombre del usuario con permisos para acceder a la BD
    $db_pass = "educovota"; // Contraseña del usuario de la BD
    
    // Ahora estamos realizando una conexión y la llamamos $link
    $link = mysqli_connect($db_host, $db_user, $db_pass, $db_nombre);

    // Manejo de errores
    if (!$link) {
        die("Error conectando a la base de datos: " . mysqli_connect_error());
    }
    return $link;
}

$link = conectarse();
?>
