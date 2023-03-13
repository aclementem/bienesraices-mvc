<?php

namespace Controllers;

use MVC\Router;
use Model\Propiedad;
use Model\Vendedor;
use Intervention\Image\ImageManagerStatic as Image;

class PropiedadController
{
    public static function index(Router $router) // static porque no requiere instanciar un objeto para usar la función
    {

        $propiedades = Propiedad::all();
        $vendedores = Vendedor::all();

        // RECOJE EL MENSAJE PASADO POR GET (URL)
        $resultado = $_GET['resultado'] ?? null;

        $router->render('propiedades/admin', [
            'propiedades' => $propiedades,
            'resultado' => $resultado,
            'vendedores' => $vendedores
        ]);
        //debuguear($router);
    }
    public static function crear(Router $router) // static porque no requiere instanciar un objeto para usar la función
    {
        $propiedad = new Propiedad();
        $vendedores = Vendedor::all();
        // ARRAY CON MENSAJES DE TEXTO
        $errores = Propiedad::getErrores();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $propiedad = new Propiedad($_POST['propiedad']);

            //  GENERAR NOMBRE UNICO PARA ARCHIVO
            $nombreOriginal = explode(".", $_FILES['propiedad']['full_path']['imagen']);
            $nombreOriginal = current($nombreOriginal);
            $datetime = date('dmyhis');
            $nombreImagen = $nombreOriginal . $datetime . ".jpg";


            //  REALIZA UN RESIZE A LA IMAGEN CON INTERVENTION IMAGE
            if ($_FILES['propiedad']['tmp_name']['imagen']) {
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
                $propiedad->setImagen($nombreImagen);
            }

            $errores = $propiedad->validar();

            if (empty($errores)) {

                //  CREAR CARPETA
                $carpetaImagenes = '../../imagenes/';
                if (!is_dir(CARPETA_IMAGENES)) { // COMPROBACIÓN SI LA CARPETA EXISTE - SI NO, LA CREAMOS
                    mkdir(CARPETA_IMAGENES);
                }

                // GUARDA LA IMAGEN EN EL SERVIDOR
                $image->save(CARPETA_IMAGENES . $nombreImagen);

                //  GUARDA EN LA BASE DE DATOS
                $propiedad->guardar();
            }
        }

        $router->render('propiedades/crear', [
            'propiedad' => $propiedad,
            'vendedores' => $vendedores,
            'errores' => $errores
        ]);
    }

    public static function actualizar(Router $router) // static porque no requiere instanciar un objeto para usar la función
    {

        $id = validarORedireccionar('/admin');

        $propiedad = Propiedad::find($id);
        $errores = Propiedad::getErrores();
        $vendedores = Vendedor::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //  ASIGNAR ATRIBUTOS
            $args = $_POST['propiedad'];

            $propiedad->sincronizar($args);
            //  VALIDACIÓN
            $errores = $propiedad->validar();
            //  SUBIDA DE ARCHIVOS
            //  GENERAR NOMBRE UNICO PARA ARCHIVO
            $nombreOriginal = explode(".", $_FILES['propiedad']['full_path']['imagen']);
            $nombreOriginal = current($nombreOriginal);
            $datetime = date('dmyhis');
            $nombreImagen = $nombreOriginal . $datetime . ".jpg";
            //  REALIZA UN RESIZE A LA IMAGEN CON INTERVENTION IMAGE
            if ($_FILES['propiedad']['tmp_name']['imagen']) {
                $image = Image::make($_FILES['propiedad']['tmp_name']['imagen'])->fit(800, 600);
                $propiedad->setImagen($nombreImagen);
            }

            if (empty($errores)) {
                // GUARDA LA IMAGEN EN EL SERVIDOR
                if ($_FILES['propiedad']['tmp_name']['imagen']) {
                    $image->save(CARPETA_IMAGENES . $nombreImagen);
                }

                $propiedad->guardar();
            }
        }

        $router->render('propiedades/actualizar', [
            'propiedad' => $propiedad,
            'errores' => $errores,
            'vendedores' => $vendedores
        ]);
    }

    public static function eliminar(Router $router)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'];
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if ($id) {

                $tipo = $_POST['tipo'];

                if (validarTipoContenido($tipo)) {
                    $propiedad = Propiedad::find($id);
                    $propiedad->eliminar();
                }
            }
        }
    }
}
