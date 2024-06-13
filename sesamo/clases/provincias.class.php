<?php
//controlar la autenticacion por token
//importamos el documento de conexion de bbdd y de respuestas
require_once "conexion/conexion.php";
require_once "respuestas.class.php";
require_once "registrarVisitas.php";
class provincias extends conexion
{
    private $tabla = "provincia";
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
    public function listarProvincias()
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


    public function obtenerProvinciasComunidad($id,$flag=false){
        $_respuestas = new respuestas;
        //comprobar que la comunia existe
        $existeComunidad= $this->comprobarComunidad($id);
        if (isset($existeComunidad["result"])) {
            return $existeComunidad;
        }
        //si no d aerro sacamos als provincias de la comunidad
        $query = "SELECT * FROM $this->tabla WHERE id_cautonoma= ?";

        // Preparamos la consulta
        $stmt = $this->conexion->prepare($query);
        if (!$stmt) {
            // Si hay un error en la preparación, retornamos el error
            return $_respuestas->error_500("Error al preparar la consulta: " . $this->conexion->error);
        }

        // Vinculamos los parámetros
        $resultado_bind_param = $stmt->bind_param("i", $id);
        if (!$resultado_bind_param) {
            // Si hay un error al vincular los parámetros, retornamos el error
            return $_respuestas->error_500("Error al vincular los parámetros: " . $stmt->error);
        }
         // Ejecutamos la consulta
         try {
            $stmt->execute();
        } catch (Exception $e) {
            // Si hay un error al ejecutar la consulta, retornamos el error
            return $_respuestas->error_500("Error al ejecutar la consulta: " . $e->getMessage());
        }

        // Obtenemos el resultado de la consulta
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {

            if ($flag) {
                return true;
            }
            // Creamos un array para almacenar los datos
            $datos = array();
            // Recorremos los resultados y almacenamos cada fila en el array
            while ($fila = $result->fetch_assoc()) {
                $datos[] = $fila;
            }
            // Cerramos la consulta preparada
            $stmt->close();
            // Retornamos los datos en formato JSON
            return json_encode($datos);
        } else {
            // Si no hay resultados, retornamos null
            return null;
        }

    }

    public function comprobarComunidad($id)
    {
        //query que nos devolver el nombre completo, la edad y el id de cama, sera el conjunto de planta. habitacion y letra
        $query = "SELECT * from comunidadautonoma where id_cautonoma=?";
        //preparamos
        $stmt = $this->conexion->prepare($query);
        // Vincular el parámetro de nhc
        $stmt->bind_param('i', $id);

        $stmt->execute();
        //guardamos resultados
        $result = $stmt->get_result();
        // Obtener la cantidad de filas encontradas
        $num_rows = $result->num_rows;
        // Cerrar la consulta preparada
        $stmt->close();

        // Verificar si se encontraron datos, si hay mas de 0 filas hay datos
        if ($num_rows > 0) {
            // si hay return con ellos
            $datos = array();
            while ($fila = $result->fetch_assoc()) {
                $datos[] = $fila;
            }
            return json_encode($datos);
        } else {
            // si no hay error dos con emnsaje
            $_respuestas = new respuestas;
            return $_respuestas->error_404("La comunidad no existe");
        }
    }


    /**if (!$this->comprobarPaciente($this->nhc)) {
            //si existe error
            return $_respuestas->error_404("No se encuentra al paciente");
        } */
}
