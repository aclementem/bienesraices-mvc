<?php

namespace Model;

class ActiveRecord
{


    //  BASES DE DATOS
    protected static $db; // Protected para que solo se llame desde la clase y static porque si creamos 1000 objetos todos requieren la misma conexión no hay que crear 1000 conexiones.
    protected static $columnasDB = [];
    protected static $tabla = '';
    //  ERRORES
    protected static $errores = [];

    //  FUNCION PARA DEFINIR LA BASE DE DATOS
    public static function setDB($database)
    {
        self::$db = $database;
    }

    public function guardar()
    {
        if (isset($this->id)) {
            $this->actualizar();
        } else {
            $this->crear();
        }
    }

    public function crear()
    {
        //  SANITIZAR LOS DATOS
        $atributos = $this->sanitizarAtributos();

        //  INSERT A LA BASE DE DATOS CON LOS VALORES RECOGIDOS DEL FORMULARIO
        $query = " INSERT INTO " . static::$tabla . " ( ";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES (' ";
        $query .= join("', '", array_values($atributos));
        $query .= " ') ";

        $resultado = self::$db->query($query);
        if ($resultado) {
            // REDIRECCIONAR AL USUARIO PARA QUE NO DUPLIQUE 
            header('Location: /admin?resultado=1'); //PASAMOS VALORES POR LA URL 
        }
    }

    public function actualizar()
    {
        $atributos = $this->sanitizarAtributos();

        $valores = [];
        foreach ($atributos as $key => $value) {
            $valores[] = "{$key} = '{$value}'";
        }

        // INSERT A LA BASE DE DATOS CON LOS VALORES RECOGIDOS DEL FORMULARIO
        $query = "UPDATE " . static::$tabla . " SET ";
        $query .= join(', ', $valores);
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1";

        $resultado = self::$db->query($query);

        if ($resultado) {
            // REDIRECCIONAR AL USUARIO PARA QUE NO DUPLIQUE 
            header('Location: /admin?resultado=2'); //PASAMOS VALORES POR LA URL 
        }
    }

    public function eliminar()
    {
        // ELIMINA PROPIEDAD DE LA BD
        $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);

        if ($resultado) {
            $this->borrarImagen();
            header('Location: /admin?resultado=3');
        }
    }

    //  ESTA FUNCION LOCALIZA Y JUNTA TODOS LOS ATRIBUTOS/COLUMNAS QUE QUEREMOS SANITIZAR
    public function atributos()
    {
        $atributos = [];

        foreach (static::$columnasDB as $columna) {
            if ($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    public function sanitizarAtributos()
    {
        $atributos = $this->atributos();
        $sanitizado = [];

        foreach ($atributos as $key => $value) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }

        return $sanitizado;
    }

    public function setImagen($imagen)
    {
        //  ELIMINA IMAGEN PREVIA 
        if (isset($this->id)) {
            $this->borrarImagen();
        }
        //  ASIGNAR AL ATRIBUTO EL NOMBRE DE LA IMAGEN
        if ($imagen) {
            $this->imagen = $imagen;
        }
    }

    public function borrarImagen()
    {
        //  COMPROBAR SI EXISTE EL ARCHIVO
        $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);
        if ($existeArchivo) {
            unlink(CARPETA_IMAGENES . $this->imagen);
        }
    }

    public static function getErrores()
    {
        return static::$errores;
    }


    public function validar()
    {
        static::$errores;
        return static::$errores;
    }

    //  LISTAR TODAS LAS PROPIEDADES
    public static function all()
    {
        $query = "SELECT * FROM " . static::$tabla;

        $resultados = self::consultarSQL($query);
        return $resultados;
    }

    // OBTENER NUMERO DETERMINADO DE REGISTROS
    public static function get($cantitdad)
    {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT " . $cantitdad;

        $resultados = self::consultarSQL($query);
        return $resultados;
    }

    //  BUSCA UNA PROPIEDAD POR SU ID
    public static function find($id)
    {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id=${id}";
        $resultado = self::consultarSQL($query);
        return array_shift($resultado);
    }

    public static function getVendedores()
    {
        $query = "SELECT * FROM vendedores";
        $resultados = self::consultarSQL($query);
        return $resultados;
    }

    public static function consultarSQL($query)
    {
        //  CONSULTAR LA BASE DE DATOS
        $resultado = self::$db->query($query);
        //  ITERAR LOS RESULTADOS
        $array = [];

        while ($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }
        //  LIBERAR MEMORIA BASE DE DATOS
        $resultado->free();

        return $array;
    }

    protected static function crearObjeto($registro)
    {
        $objeto = new static;

        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    //  SINCRONIZA EL OBJETO EN MEMORIA CON LOS CAMBIOS REALIZADOS
    public function sincronizar($args = [])
    {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }
}
