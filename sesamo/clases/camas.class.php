<?php
//controlar la autenticacion por token
//importamos el documento de conexion de bbdd y de respuestas
require_once "conexion/conexion.php";
require_once "respuestas.class.php";
require_once "registrarVisitas.php";
class camas extends conexion
{

    private $tabla = "camas";
    private $id_cama = "";
    private $bloqueada = "";
    private $token="";
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
    public function listarCamas()
    {

        //si es null se hara esto
        $query = "SELECT * FROM {$this->tabla}";
        //preparamos
        $stmt = $this->conexion->prepare($query);
        //ejecutamos
        $stmt->execute();
        //guardamos resultados
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
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
        } else {
            return null;
        }

    }
    public function listarCamasCentro($centro, $flag)
    {
        if (!$flag) {
            $query = "SELECT * FROM {$this->tabla} where id_centro=?";
            //preparamos
            $stmt = $this->conexion->prepare($query);
            $stmt->bind_param("i", $centro);
        } else {
            $query = "SELECT * FROM {$this->tabla} where  id_centro=? AND id_planta=?";
            //preparamos
            $stmt = $this->conexion->prepare($query);
            $stmt->bind_param("ii", $centro, $flag);
        }
        //si es null se hara esto
        //ejecutamos
        $stmt->execute();
        //guardamos resultados
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
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
        } else {
            return null;
        }
    }
    public function listarPlantas($centro)
    {
        //sacamos los elmentos unicos de las plantas
        $query = "SELECT DISTINCT id_planta FROM {$this->tabla} WHERE id_centro=?";
        //preparamos
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $centro);

        //si es null se hara esto
        //ejecutamos
        $stmt->execute();
        //guardamos resultados
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {

            // Retornar los datos en formato JSON, seran el numero de plantas
            return json_encode(array("plantas" => $result->num_rows));
        } else {
            return null;
        }

    }
    //put
    public function put($json) //reciben un json como parametro
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
                //aqui deben estar todos los campos obligatorios
                if (!isset($datos["id_cama"]) || !isset($datos["bloqueada"])) {
                    // Si no existen los campos requeridos, enviamos un error 400
                    return $_respuestas->error_400();
                } else {
                    //comprobamos los dtaos que vengan por json para ser actualizados,ademas comprobamso los que no vengan vacios para hacer la query
                    // Creamos un array para almacenar los pares campo = valor
                    $camposValores = array();
                    // Creamos un array para almacenar los tipos de datos de los parámetros, sera una statemen
                    $tiposDatos = "";
                    //para los parametros
                    $params = array();
                    foreach ($datos as $clave => $valor) {
                        //verifica si la clave existe como propiedad de la clase, es decir si existe el atributo
                        if (property_exists($this, $clave)) {
                            $this->{$clave} = $valor;
                            //vamos a comprobar si hay datos especiales que sena de tipo i y que los datos que vengan no sean vacios
                            //comprobamos si no es valor vacio
                            if ($valor !== "" && $valor !== null && $valor !== 0 && $clave != "token" && $clave != "id_cama") {
                                //comprobamos los tipos especiales
                                //nhc sera entero, sexo debe ser una letra, fallecido igual
                                //si esta el nhc y es numerico
                                if ($clave == "id_cama") {
                                    if (is_numeric($valor)) {
                                        $camposValores[] = "id_cama=?"; //añadimnos a la query segun si existe a dato o no
                                        $tiposDatos .= "i";   //de la msiam manera añadimos el tipo de datos
                                        $params[] = $this->{$clave}; //y añadimos el parametro que pasaremos
                                    } else {
                                        // si no es numerico error
                                        return $_respuestas->error_400();
                                    }
                                } elseif ($clave == "bloqueada") {
                                    $valor= strtoupper($valor);
                                   
                                    if (!is_numeric($valor) && strlen($valor) == 1 && ($valor == "N" || $valor == "S")) {
                                        $this->{$clave} =$valor;
                                        $camposValores[] = "bloqueada=?"; //añadimnos a la query segun si existe a dato o no
                                        $tiposDatos .= "s";   //de la msiam manera añadimos el tipo de datos
                                        $params[] = $this->{$clave}; //y añadimos el parametro que pasaremos
                                    } else {
                                        //si e snumerico o son mas de una letra
                                        return $_respuestas->error_400();
                                    }
                                } /*else {
                                 //si no es ninguno de estos seran estrings
                                 $camposValores[] = "$clave=?"; //añadimnos a la query segun si existe a dato o no
                                 $tiposDatos .= "s";   //de la msiam manera añadimos el tipo de datos
                                 $params[] = $this->$clave; //y añadimos el parametro que pasaremos
                             }*/
                            }
                        }
                    }
                    // Llamamos a la función para insertar el paciente y almacenamos el resultado
                    //   $res = $this->comprobarPaciente($this->nhc,$this->id_cama,$this->numeroSS);
                    $res = $this->modificarCama($camposValores, $tiposDatos, $params);
                    //si hay respueste devolvemos  (0 es como un false)
                    if (isset($res["result"])) {
                        //si hay un resultado, es que hay un error, enseñamo ese error
                        return $res;
                    } else if ($res) {
                        //por el contrario si ya sido exitoso
                        //creamso rsuesta
                        $respuesta = $_respuestas->response;
                        //le metemos el id del apciente a la respuesta
                        if ($this->bloqueada=="S") {
                            $respuesta["result"] = array(
                                "msg" => "Se ha bloqueado la cama de forma correcta."
                            );
                        }else{
                            $respuesta["result"] = array(
                                "msg" => "Se ha desbloqueado la cama de forma correcta."
                            );
                        }
                      
                        return $respuesta;
                    } else {
                        //si no enviamos error, interno
                        return $_respuestas->error_500();
                    }
                }
            } else {
                return $_respuestas->error_401("El token es invalido o caducado");
            }
        }
    }


    private function modificarCama($camposValores, $tiposDatos, $params)
    {
        $_respuestas = new respuestas;

        if ($this->comprobarCama($this->id_cama)) {


            $query = "UPDATE $this->tabla SET ";

            // Combinamos los pares campo = valor en la consulta
            $query .= implode(", ", $camposValores);

            // Agregamos la condición WHERE nhc
            $query .= " WHERE id_cama=?";
            $params[] = $this->id_cama;
            // Agregamos el tipo de dato para el campo PacienteId
            $tiposDatos .= "i";
            // Preparamos la sentencia
            $stmt = $this->conexion->prepare($query);
            //comprobamos si se prepare bien
            if (!$stmt) {
                return 0;
            }
            //como los numero son inciertos, usamos .. para que cada parametro de $param, pase como parametro al la fucion param y se vaya asociando
            //con su campo correspondiente
            $resultado_bind_param = $stmt->bind_param($tiposDatos, ...$params);
            //comprobamos el bindi
            if (!$resultado_bind_param) {
                return 0;
            }
            // Ejecutamos la consulta
            try {
                $resp = $stmt->execute();
            } catch (Exception $e) {
                echo "Error al ejecutar la consulta: " . $e->getMessage();
            }
            // Verificamos si la actualización fue exitosa
            if ($resp) {
                // Devolvemos el número de filas afectadas por la actualización
                if ($stmt->affected_rows > 0) {
                    return $stmt->affected_rows;
                } else {

                    return $_respuestas->error_200("No hay datos que modificar");
                }
            } else {
                // Si no se actualiza ninguna fila, devolvemos 0
                return 0;
            }
        } else {
            return $_respuestas->error_200("Cama bloqueada u no disponible");
        }
    }
    //comprobar que la cma este dipsonible
    private function comprobarCama($cama)
    {
        // comprobamos qiue la cama este disponible y que no este bloqueada, miraremos el esatdo auqnue no este ingresada
        $query = "SELECT COUNT(*) AS count FROM camas WHERE estado = 'Disponible' AND id_cama = ?;";


        // Preparamos la consulta
        $stmt = $this->conexion->prepare($query);

        // Vinculamos los parámetros
        $stmt->bind_param("i", $cama);

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

    //buscar tyoken 
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
