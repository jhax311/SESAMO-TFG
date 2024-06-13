<?php
//controlar la autenticacion por token
//importamos el documento de conexion de bbdd y de respuestas
require_once "conexion/conexion.php";
require_once "respuestas.class.php";
require_once "registrarVisitas.php";
class ambitos extends conexion
{
    private $tabla = "ambitos";
    //atributos de la bbdd, lops necesarios
    public function __construct()
    {
        //lamamos ak contructor padre
        parent::__construct(); 
        $this->verificarOCrearTabla($this->tabla);
         
        $registro = new RegistroVisitas();
        $registro->registrarVisita($this->tabla);
    }
    //metodo listar pacientes
    public function listarAmbitos()
    {

        //si es null se hara esto
        $query = "SELECT * FROM {$this->tabla}";
        //preparamos
        $stmt = $this->conexion->prepare($query);
        //ejecutamos
        $stmt->execute();
        //guardamos resultados
        $result = $stmt->get_result();
        // Crear un array para almacenar los datos
        $datos = array();
        // Recorrer los resultados y almacenarlos en el array
        while ($fila = $result->fetch_assoc()) {
            $datos[] = $fila;
        }
        // Cerrar la consulta preparada
        $stmt->close();
        // Retornar los datos en formato JSON
        return json_encode($datos);

    }
}
