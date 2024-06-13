<?php
//controlar la autenticacion por token
//importamos el documento de conexion de bbdd y de respuestas
require_once "conexion/conexion.php";
require_once "respuestas.class.php";
require_once "registrarVisitas.php";
class alta extends conexion
{
    private $tabla = "alta";
    //atributos de la bbdd, lops necesarios
    // private $nhc = "";
    private $fecha = null;
    private $hora = null;
    private $motivo = null;
    private $tipo = null;
    private $nhc = null;

    private $token = ""; // para el token 
    public function __construct()
    {
        //lamamos ak contructor padre
        parent::__construct(); 
        $this->verificarOCrearTabla($this->tabla);
    
        $registro = new RegistroVisitas();
        $registro->registrarVisita($this->tabla);
    }
    //metodo listar pacientes
    public function listarAltas()
    {
        // Definimos la consulta
        $query = "SELECT * FROM {$this->tabla}";

        // Preparamos la consulta
        $stmt = $this->conexion->prepare($query);

        // Ejecutamos 
        $stmt->execute();

        // Obtenemos los resultados 
        $result = $stmt->get_result();

        // almacenamos los datos
        $datos = array();

        // los guardamos
        while ($fila = $result->fetch_assoc()) {
            $datos[] = $fila;
        }

        // cerramso consulta
        $stmt->close();

        // Retornamos 
        return json_encode($datos);
    }

    //listar paciente por nhc nift y numss, segun el campo que se le pase
    public function obtenerAltaNhc($campo, $valor)
    {
        if ($campo == 0) {
            $campo = "nhc";
        } else {
            // Si no es "nhc", devolvemos un error
            $_respuestas = new respuestas;
            return $_respuestas->error_401();
        }

        $query = "SELECT * FROM $this->tabla WHERE $campo = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param('i', $valor);
        $stmt->execute();
        $result = $stmt->get_result();
        $datos = array();
        while ($fila = $result->fetch_assoc()) {
            $datos[] = $fila;
        }
        $stmt->close();
        if (count($datos) > 0) {
            return json_encode($datos);
        } else {
            // Si no hay datos, devolvemos un error
            $_respuestas = new respuestas;
            return $_respuestas->error_200("No existen altas asociadas al paciente.");
        }
    }

    //POSTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTT
    public function post($json) //reciben un json como parametro
    {
        // Instanciamos las respuestas
        $_respuestas = new respuestas;
        // Transformamos el JSON en un array; los datos provienen del formulario
        $datos = json_decode($json, true);


        //preguntamos si no existe l token
        if (!isset($datos["token"])) {
            // si no existe el token devolvemos un error
            return $_respuestas->error_401();
        } else {
            $this->token = $datos["token"];
            //buscar token en caso de que no ste envia array avcio suamso eso para comprobar 
            $arrayToken = $this->buscarToken();
            if ($arrayToken) {
                //si existe el token y es valido, hacemos la peticion post
                // Validamos los datos capturados con los campos requeridos para hacer un insert

                if (
                    !isset($datos["fecha"]) || !isset($datos["hora"]) || !isset($datos["motivo"]) ||
                    !isset($datos["tipo"]) || !isset($datos["nhc"])
                ) {
                    // Si no existen los campos requeridos, enviamos un error 400
                    return $_respuestas->error_400();
                } else {
                    // Si existen, los asignamos a los atributos, atributos obligatorios
                    foreach ($datos as $clave => $valor) {
                        if (property_exists($this, $clave)) {
                            $this->{$clave} = $valor;
                        }
                    }


                    $res = $this->insertarAlta();

                    //si hay respueste devolvemos  (0 es como un false)
                    if (isset($res["result"])) {
                        //si hay un resultado, es que hay un error, enseñamo ese error
                        return $res;
                    } else if ($res) {
                        //por el contrario si ya sido exitoso
                        //creamso rsuesta
                        $respuesta = $_respuestas->response;
                        //le metemos el id del apciente a la respuesta
                        $respuesta["result"] = array(
                            "msg" => "Paciente dado de alta correctamente."
                        );
                        return $respuesta;
                    } else {
                        //si no enviamos error, interno
                        return $_respuestas->error_500();
                    }
                }
            } else {
                return $_respuestas->error_401("El token es invalido o ha caducado.");
            }
        }
    }

    //insertar paciente

    private function insertarAlta()
    {
        $_respuestas = new respuestas();

        //comprobamos de si ya existe un usaurio con ese nif, nhc y numss

        if (!$this->comprobarPaciente($this->nhc)) {
            //si existe error
            $_respuestas = new respuestas;
            return $_respuestas->error_200("El paciente no existe.");
        }
        //comprobamos que este ingresado
        $ingresado = $this->comprobarIngreso($this->nhc);
        //si no es verdadero, viene el error y lo devolvemos
        if ($ingresado !== true) {
            // El paciente no está ingresado
            return $ingresado;
        }
        //debemos guardar los datos de la cama y el id ingreso de donde esta actualmente el paciente
        $query = "SELECT id_ingreso, id_cama FROM ingresos WHERE nhc = ? AND ingresado = 1";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param('i', $this->nhc);
        $stmt->execute();
        $result = $stmt->get_result();
        $ingreso = $result->fetch_assoc();
        //datos
        $id_ingreso = $ingreso['id_ingreso'];
        $id_cama = $ingreso['id_cama'];
    
        //si no existe lo agreagmos
        // Creamos la sentencia
        $query = "INSERT INTO alta (fecha, hora, motivo, tipo, nhc) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($query);
        if (!$stmt) {
            die("Error al preparar la consulta: " . $this->conexion->error);
        }
        //vinculamos
        $stmt->bind_param("ssssi", $this->fecha, $this->hora, $this->motivo, $this->tipo, $this->nhc);
    

        // Ejecutamos la consulta
        try {
            $resp = $stmt->execute();
        } catch (Exception $e) {
            $_respuestas = new respuestas;
            if ($errorCode == 1062) {
                return $_respuestas->error_400("Error: Entrada duplicada.");
            } else {
                return $_respuestas->error_500("Error al ejecutar la consulta: " . $e->getMessage());
            }
        }

        if ($resp) {
            if ($this->actualizarEstadoIngresoYCama($id_ingreso, $id_cama)) {
                return true;
            } else {
                return $_respuestas->error_500("Error al actualizar el estado del ingreso y la cama");
            }
        } else {
            // Si no se inserta, enviamos 0
            return 0;
        }

    }

    private function comprobarIngreso($nhc)
    {
        $_respuestas = new respuestas();

        // Verificar si el paciente está ingresado actualmente
        $query = "SELECT id_ingreso, id_cama FROM ingresos WHERE nhc = ? AND ingresado = 1";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param('i', $nhc);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // No se encontró el ingreso activo
            return $_respuestas->error_200("El paciente no está ingresado actualmente.");
        } else {
            // El paciente está ingresado
            return true;
        }
    }

    private function actualizarEstadoIngresoYCama($id_ingreso, $id_cama) {
        // Actualizamos el eatdo de ingreso a inactivo
        $query = "UPDATE ingresos SET ingresado = 0 WHERE id_ingreso = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param('i', $id_ingreso);
        if ($stmt->execute()) {
            // Liberar la cama
            $query = "UPDATE camas SET estado = 'Disponible' WHERE id_cama = ?";
            $stmt = $this->conexion->prepare($query);
            $stmt->bind_param('i', $id_cama);
            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    



    //comprobar si el paciente existe
    private function comprobarPaciente($nhc)
    {
        // Creamos la consulta para verificar si el paciente ya existe, buscara por nhc
        $query = "SELECT COUNT(*) AS count FROM pacientes WHERE nhc = ?";
        // Preparamos la consulta
        $stmt = $this->conexion->prepare($query);

        // Vinculamos los parámetros
        $stmt->bind_param("i", $nhc);

        // Ejecutamos la consulta
        $stmt->execute();

        // Obtenemos el resultado de la consulta
        $result = $stmt->get_result();

        // Obtenemos el número de filas encontradas
        $row = $result->fetch_assoc();
        $count = $row['count'];

        // Cerramos la consulta preparada
        $stmt->close();

        // si encuntra algun resultado true, si no false
        return $count > 0;
    }



    //FUNCIONES DE TOKEN

    private function buscarToken()
    {
        // Creamos la consulta
        $query = "SELECT TokenId, UsuarioId, Estado FROM usuarios_token WHERE Token=? AND Estado=1";
        // Preparamos la sentencia
        $stmt = $this->conexion->prepare($query);

        // Verificamos si la preparación fue exitosa
        if (!$stmt) {
            // Si la preparación falla, retornamos array vacio para que no gaurde datos
            return [];
        }

        // Vinculamos el parámetro Token
        $resultado_bind_param = $stmt->bind_param("s", $this->token);

        // Verificamos si la vinculación de parámetros fue exitosa
        if (!$resultado_bind_param) {
            // Si la vinculación de parámetros falla, retornamos 0
            return [];
        }

        // Ejecutamos la consulta
        $resp = $stmt->execute();

        // Verificamos si la consulta fue exitosa
        if ($resp) {
            // Obtenemos el resultado de la consulta
            $result = $stmt->get_result();

            // Obtenemos la fila resultante como un array asociativo
            $row = $result->fetch_assoc();

            // Devolvemos el resultado de la consulta
            return $row ? $row : []; // Si hay una fila resultante, la devolvemos; de lo contrario, devolvemos un array vacío
        } else {
            // Si la consulta falla, retornamos 0
            return [];
        }
    }
}
