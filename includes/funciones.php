<?php

define('TEMPLATES_URL', __DIR__ . '/templates');  // DIR nos traerá la ruta absoluta hasta el archivo y añadimos el resto.
define('FUNCIONES_URL', __DIR__ . 'funciones.php');
define('CARPETA_IMAGENES', $_SERVER['DOCUMENT_ROOT'] . '/imagenes/');

function incluirTemplate(string $nombre, bool $inicio = false)
{
    include TEMPLATES_URL . "/${nombre}.php";
}

function estaAutenticado(): bool
{
    session_start();

    if (!$_SESSION['login']) {
        header('Location: /');
        return false;
    }
    return true;
}

function debuguear($variable)
{
    echo '<pre>';
    var_dump($variable);
    echo '</pre>';
    exit;
}

//  ESCAPA/SANITIZAR EL HTML
function s($html): string
{
    $s = htmlspecialchars($html);
    return $s;
}

// Validar tipo de Contenido
function validarTipoContenido($tipo)
{
    $tipos = ['vendedor', 'propiedad'];

    return in_array($tipo, $tipos);
}

// Muestra los mensajes
function mostrarNotificaciones($codigo)
{
    $mensaje = '';

    switch ($codigo) {
        case 1:
            $mensaje = 'Creado Correctamente';
            break;
        case 2:
            $mensaje = 'Actualizado Correctamente';
            break;
        case 3:
            $mensaje = 'Eliminado Correctamente';
            break;
        default:
            $mensaje = false;
            break;
    }

    return $mensaje;
}

function validarORedireccionar(string $url)
{
    // RECOJE EL MENSAJE PASADO POR GET (URL)
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT); // VALIDAMOS QUE ES UN INT

    if (!$id) { // SI NO PASA LA VALIDACION (FALSE) NOS DEVUELVE A ADMIN/INDEX.PHP
        header("Location: ${url}");
    }

    return $id;
}
