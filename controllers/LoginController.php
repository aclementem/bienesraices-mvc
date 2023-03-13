<?php

namespace Controllers;

use MVC\Router;
use Model\Admin;

class LoginController
{
    public static function login(Router $router)
    {
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $auth = new Admin($_POST);

            $errores = $auth->validar();

            if (empty($errores)) {
                // Verificar que existe el usuario

                $resultado = $auth->existeUsuario();

                if (!$resultado) {
                    $errores = Admin::getErrores();
                } else {
                    // Verificar el passwoord
                    $autenticado = $auth->comprobarPassword($resultado);

                    if ($autenticado) {
                        // Iniciar sesión
                        $auth->autenticar();
                    } else {
                        // Password Incorrecto -> Mensaje de error
                        $errores = Admin::getErrores();
                    }
                }
            }
        }

        $router->render('auth/login', [
            'errores' => $errores
        ]);
    }

    public static function logout()
    {
        session_start();

        $_SESSION = [];

        header('Location: /');
    }
}
