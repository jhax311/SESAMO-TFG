<?php

// Vamos a crear una clase para gestionar las conexiones

class conexion
{
    // Creamos los atributos privados
    private $server;
    private $user;
    private $password;
    private $database;
    private $port;
    protected $conexion;

    // La función constructora, que sacará el JSON y pondrá en los atributos
    function __construct()
    {
        // array con los datos sacados del fichero
        $listaDatos = $this->datosConexion();
        // Recorremos y asignamos
        foreach ($listaDatos as $key => $value) {
            // Atributo valor
            $this->server = $value['server'];
            $this->user = $value['user'];
            $this->password = $value['password'];
            $this->database = $value['database'];
            $this->port = $value['port'];
        }

        // Conectar al servidor MySQL sin seleccionar una base de datos
        $this->conexion = new mysqli($this->server, $this->user, $this->password, "", $this->port);

        // Verificar si la conexión fue exitosa
        if ($this->conexion->connect_errno) {
            //si no abortamos
            die();
        }

        // Comprobar si la base de datos existe y crearla si no
        $this->comprobarBD();

        // Seleccionar la base de datos
        $this->conexion->select_db($this->database);

        // Ahora que tenemos la base de datos, verificar si la instancia fue correcta
        if ($this->conexion->connect_errno) {
            die();
        }
    }

    private function comprobarBD()
    {
        // Comprobar si la base de datos existe
        $sql = "SHOW DATABASES LIKE '" . $this->database . "'";
        $result = $this->conexion->query($sql);

        if ($result->num_rows == 0) {
            // Si no existe, creamos
            $sql = "CREATE DATABASE " . $this->database;
            if ($this->conexion->query($sql) === TRUE) {
                // Ejecutamos la base de datos inicial 
                //la selecionamos
                $this->conexion->select_db($this->database); 
                //eejcutamos las tablas e inserte inciales
                $this->ejecutarSQLInicial();
            } else {
                // Si da error, abortamos
                die("Error creando la base de datos: " . $this->conexion->error);
            }
        } else {
            // Seleccionamos la base de datos existente
            $this->conexion->select_db($this->database);
        }
    }

    private function ejecutarSQLInicial()
    {
        // La cogemos de donde la tenemos guardada
        $direccionArchivo = "/etc/sesamo/bddBase.sql";
        $sql = file_get_contents($direccionArchivo);
        if ($this->conexion->multi_query($sql)) {
            do {
                // Ejecutamos todas las consultas del archivo
                if ($result = $this->conexion->store_result()) {
                    $result->free();
                }
            } while ($this->conexion->more_results() && $this->conexion->next_result());
        } else {
            // Abortamos si hay errores
            die("Error ejecutando archivo SQL inicial: " . $this->conexion->error);
        }
    }

    // Cogeremos los datos del archivo y ponerlos en los atributos
    private function datosConexion()
    {
        // Aquí guardaremos la dirección del archivo
        $direccionArchivo = "/etc/sesamo/config";
        // Como esta en JSON debemos pasarlo a texto, abrimos archivo y guardamos contenido
        $jsondata = file_get_contents($direccionArchivo);
        // Retornamos el contenido JSON en un array asociativo
        return json_decode($jsondata, true); // True para que sea asociativo
    }

    // Para comprobar las tablas
    public function verificarOCrearTabla($nombreTabla)
    {
        // Si está en la base de datos
        $sql = "SHOW TABLES LIKE '" . $nombreTabla . "'";
        $result = $this->conexion->query($sql);
        // Si no hay resultados ejecutamos el fichero de inicialización
        if ($result->num_rows == 0) {
            $this->ejecutarSQLInicial();
        }
    }
    public function ejecutarConsulta($sql) {
        // Ejecutar la consulta y manejar errores
        if ($this->conexion->query($sql) === TRUE) {
            return true;
        } else {
            return "Error: " . $sql . "<br>" . $this->conexion->error;
        }
    }
}
?>
