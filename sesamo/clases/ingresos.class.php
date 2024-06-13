<?php
//controlar la autenticacion por token
//importamos el documento de conexion de bbdd y de respuestas
require_once "conexion/conexion.php";
require_once "respuestas.class.php";
require_once "registrarVisitas.php";
class ingresos extends conexion
{
    private $tabla = "ingresos";
    //atributos de la bbdd, lops necesarios
    private $id_ingreso;
    private $estado_nhc = "";
    private $id_ambito = "";
    private $fecha = "";
    private $hora = "";
    private $id_cama = "";
    private $nhc = "";
    private $token = ""; // para el token 
    private $aux = "";
    public function __construct()
    {
        //lamamos ak contructor padre
        parent::__construct(); 
        $this->verificarOCrearTabla($this->tabla);
         
        $registro = new RegistroVisitas();
        $registro->registrarVisita($this->tabla);
    }
    //metodo listar pacientes
    public function listarIngresos()
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
    //listar paciente por nhc nift y numss, segun el campo que se le pase
    public function obtenerIngresoCampoValor($campo, $valor)
    {
        if ($campo == 0) {
            $campo = "nhc";
        } elseif ($campo == 1) {
            $campo = "nif";
        } else {
            //si no es ninguno de estos
            // si no hay error dos con emnsaje
            $_respuestas = new respuestas;
            return $_respuestas->error_401();
        }
        //obtener paciente por nhc
        //creamos la consulta
        $query = "SELECT * FROM $this->tabla where $campo=?";

        //prepramos
        $stmt = $this->conexion->prepare($query);
        //ejecutamos la consuklta
        // Vincular el parámetro de nhc
        if ($campo == "nhc") {
            $stmt->bind_param('i', $valor);
        } elseif ($campo == "nif") {
            $stmt->bind_param('s', $valor);
        }

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
            return $_respuestas->error_200("No se encontraron datos");
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
                //aqui deben estar todos los campos obligatorios
                if (!isset($datos["estado_nhc"]) || !isset($datos["id_ambito"]) || !isset($datos["nhc"]) || !isset($datos["id_cama"]) || !isset($datos["fecha"]) || !isset($datos["hora"])) {
                    // Si no existen los campos requeridos, enviamos un error 400
                    return $_respuestas->error_400();
                } else {
                    // Si existen, los asignamos a los atributos, atributos obligatorios
                    foreach ($datos as $clave => $valor) {
                        if (property_exists($this, $clave)) {
                            $this->{$clave} = $valor;
                        }
                    }
                    // Llamamos a la función para insertar el paciente y almacenamos el resultado
                    //   $res = $this->comprobarPaciente($this->nhc,$this->nif,$this->numeroSS);
                    $res = $this->ingresarPaciente();

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
                            "msg" => "Se ha ingresado el paciente de forma correcta"
                        );
                        return $respuesta;
                    } else {
                        //si no enviamos error, interno
                        return $_respuestas->error_500();
                    }
                }
            } else {
                return $_respuestas->error_401("El token es invalido o caducado.");
            }
        }
    }

    //insertar paciente

    private function ingresarPaciente()
    {
        $_respuestas = new respuestas;
        //comprobamos de si ya existe un usaurio con ese nif, nhc y numss
        $res = $this->comprobarIngreso($this->nhc);

        if (isset($res["result"])) {
            //si hay un resultado, es que hay un error, enseñamo ese error
            return $res;
        } else {
            //si no existe lo agreagmos
            // Creamos la sentencia
            $query = "INSERT INTO $this->tabla (estado_nhc,id_ambito,fecha,hora,id_cama,nhc,ingresado) ";
            $query .= " VALUES ( ?, ?, ?, ?, ?, ?,?)";

            // Preparamos la sentencia
            $stmt = $this->conexion->prepare($query);

            if (!$stmt) {
                // Ocurrió un error al preparar la consulta
                return $_respuestas->error_500("Error prepare 177");
            }
            $ingresado=1;

            // Vinculamos los parámetros
            $stmt->bind_param(
                "ssssiii",
                $this->estado_nhc,
                $this->id_ambito,
                $this->fecha,
                $this->hora,
                $this->id_cama,
                $this->nhc,
                $ingresado
            );
            ////////////////////////////////////////////////////////////////////////////////////PERFECIONAR LOS ERRORES
            if (!$stmt) {
                return $_respuestas->error_500("Error bind 193");
            }

            // Ejecutamos la consulta
            try {
                $resp = $stmt->execute();
            } catch (Exception $e) {
                return $_respuestas->error_200("Error al ejecutar la consulta: " . $e->getMessage());
            }

            if ($resp) {
                // Si se inserta correctamente, enviamos el ID del paciente insertado
                $this->reservaCama($this->id_cama, "Ocupada");
                return true;

            } else {
                // Si no se inserta, enviamos 0
                return 0;
            }
        }
    }
    //comprobar si el paciente existe
    private function comprobarIngreso($nhc, $flag = true)
    {
        $_respuestas = new respuestas;
        //comprueba de si existe el paciente
        $existe = $this->comprobarPaciente($nhc);
        if (!$existe) {
            return $_respuestas->error_200("El paciente no existe");
        }
        //comprueba si la cama esta disponible
        $disponible = $this->comprobarCama($this->id_cama);
        if (!$disponible) {
            return $_respuestas->error_200("Cama no disponible");
        }
        //comprobamos si el pacienete sta ingresado, activamente
        $query = "SELECT COUNT(*) AS count FROM $this->tabla WHERE nhc = ? AND ingresado = 1";

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
        if ($flag) {
            if ($count > 0) {
                return $_respuestas->error_200("Paciente ya se encuentra ingresado");
            } else {
                return [];
            }
        } else {
            return $count > 0;
        }

    }

    

    private function comprobarPaciente($nhc)
    {
        // Creamos la consulta para verificar si el paciente ya existe, buscara por nhc, nif o seguridad social
        $query = "SELECT COUNT(*) AS count FROM pacientes WHERE nhc = ? ";

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
    private function comprobarCama($cama)
    {
        // comprobamos qiue la cama este disponible y que no este bloqueada, miraremos el esatdo auqnue no este ingresada
        $query = "SELECT COUNT(*) AS count FROM camas WHERE estado = 'Disponible' AND bloqueada = 'N' AND id_cama = ?;";


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

    private function reservaCama($cama, $estado)
    {
        $_respuestas = new respuestas;
        $query = "UPDATE camas SET estado = ? WHERE id_cama = ?";
        // Preparamos la sentencia
        $stmt = $this->conexion->prepare($query);
        if (!$stmt) {
            // Ocurrió un error al preparar la consulta
            return $_respuestas->error_500("Error prepare 324");
        }
        // Vinculamos los parámetros
        $stmt->bind_param("si", $estado, $cama);
        ////////////////////////////////////////////////////////////////////////////////////PERFECIONAR LOS ERRORES
        if (!$stmt) {
            return $_respuestas->error_500("Error bind 330");
        }
        // Ejecutamos la consulta
        try {
            $stmt->execute();
        } catch (Exception $e) {
            $_respuestas = new respuestas;
            return $_respuestas->error_200("Error al ejecutar la consulta: " . $e->getMessage());
        }

    }
    private function cambioCama($nhc, $flag = false)
    {
        $_respuestas = new respuestas;

        $query = "SELECT id_cama from $this->tabla where nhc=?";
        // Preparamos la sentencia
        $stmt = $this->conexion->prepare($query);
        if (!$stmt) {
            // Ocurrió un error al preparar la consulta
            return $_respuestas->error_500("Error prepare 350");
        }
        // Vinculamos los parámetros
        $stmt->bind_param("i", $nhc);
        ////////////////////////////////////////////////////////////////////////////////////PERFECIONAR LOS ERRORES
        if (!$stmt) {
            return $_respuestas->error_500("Error bind 356");
        }
        // Ejecutamos la consulta
        try {
            $stmt->execute();
        } catch (Exception $e) {

            return $_respuestas->error_200("Error al ejecutar la consulta: " . $e->getMessage());
        }
        //cogemos los resultados
        $resul = $stmt->get_result()->fetch_assoc();
        $id = $resul["id_cama"];
        if ($flag) {
            $this->aux = $id;
        }

        $estadoA = $this->obtenerEstadoCama($resul["id_cama"]);

        if ($estadoA == "disponible") {
            $estado = "Ocupada";
        } elseif ($estadoA == "ocupada") {
            $estado = "Disponible";
        }
        $this->reservaCama($id, $estado);
    }
    private function obtenerEstadoCama($idCama)
    {
        $query = "SELECT estado FROM camas WHERE id_cama=?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $idCama);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return strtolower($row['estado']);
    }



    //PUTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTT
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
                //aqui deben estar todos los campos obligatorios || !isset($datos["id_cama"])
                if (!isset($datos["estado_nhc"]) || !isset($datos["nhc"])) {
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
                            if ($valor !== "" && $valor !== null && $valor !== 0 && $clave != "token" && $clave != "id_ingreso" && $clave != "nhc") {
                                //comprobamos los tipos especiales
                                //nhc sera entero, sexo debe ser una letra, fallecido igual
                                //si esta el nhc y es numerico
                                if ($clave == "id_cama") {
                                    if (is_numeric($valor) && strlen($valor) < 11) {
                                        $camposValores[] = "id_cama=?"; //añadimnos a la query segun si existe a dato o no
                                        $tiposDatos .= "i";   //de la msiam manera añadimos el tipo de datos
                                        $params[] = $this->{$clave}; //y añadimos el parametro que pasaremos
                                    } else {
                                        // si no es numerico error
                                        return $_respuestas->error_400();
                                    }
                                } elseif ($clave == "nhc") {
                                    if (is_numeric($valor) && strlen($valor) < 11) {
                                        $camposValores[] = "nhc=?"; //añadimnos a la query segun si existe a dato o no
                                        $tiposDatos .= "i";   //de la msiam manera añadimos el tipo de datos
                                        $params[] = $this->{$clave}; //y añadimos el parametro que pasaremos
                                    } else {
                                        //si e snumerico o son mas de una letra
                                        return $_respuestas->error_400();
                                    }
                                } elseif ($clave == "id_ambito") {
                                    if (is_numeric($valor) && ($valor == 1 || $valor == 2 || $valor == 3)) {
                                        $camposValores[] = "id_ambito=?"; //añadimnos a la query segun si existe a dato o no
                                        $tiposDatos .= "i";   //de la msiam manera añadimos el tipo de datos
                                        $params[] = $this->{$clave}; //y añadimos el parametro que pasaremos
                                    } else {
                                        //si e snumerico o son mas de una letra
                                        return $_respuestas->error_400();
                                    }
                                } else {
                                    //si no es ninguno de estos seran estrings
                                    $camposValores[] = "$clave=?"; //añadimnos a la query segun si existe a dato o no
                                    $tiposDatos .= "s";   //de la msiam manera añadimos el tipo de datos
                                    $params[] = $this->$clave; //y añadimos el parametro que pasaremos
                                }
                            }
                        }
                    }
                    // Llamamos a la función para insertar el paciente y almacenamos el resultado
                    //   $res = $this->comprobarPaciente($this->nhc,$this->nif,$this->numeroSS);
                    $res = $this->modificarPaciente($camposValores, $tiposDatos, $params);
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
                            "msg" => "Se ha modificado el ingreso del paciente de forma correcta"
                        );

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


    //modifcarPAciente

    private function modificarPaciente($camposValores, $tiposDatos, $params)
    {
        //vamos a crear una funciondinamica, para que s evayan crando la queri segunlos datos pasados.
        // Creamos la parte inicial de la consulta
        $query = "UPDATE $this->tabla SET ";

        // Combinamos los pares campo = valor en la consulta
        $query .= implode(", ", $camposValores);

        // Agregamos la condición WHERE nhc
        $query .= " WHERE nhc=?";
        $params[] = $this->nhc;
        // Agregamos el tipo de dato para el campo PacienteId
        $tiposDatos .= "i";
        // Preparamos la sentencia
        $stmt = $this->conexion->prepare($query);
        //comprobamos si en caso cambiamos de cama este libre
        $res = $this->comprobarIngreso($this->nhc, false);
        if (isset($res["result"])) {
            //si hay un resultado, es que hay un error, enseñamo ese error
            return $res;
        }

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

        //cambiamos la cama anterior
        $this->cambioCama($this->nhc);
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
                // Ejecutamos la consulta
                //cambiamos la cmaa ctual
                $this->cambioCama($this->nhc);
                return $stmt->affected_rows;
            } else {
                $_respuestas = new respuestas;
                return $_respuestas->error_200("No hay datos que modificar");
            }
        } else {
            // Si no se actualiza ninguna fila, devolvemos 0
            return 0;//
        }
    }



    //DELETEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE

    public function delete($json)
    {
        // Instanciamos las respuestas
        $_respuestas = new respuestas;
        // Transformamos el JSON en un array; los datos provienen del formulario
        $datos = json_decode($json, true);
        //token
        if (!isset($datos["token"])) {
            // si no existe el token devolvemos un error
            return $_respuestas->error_401();
        } else {
            $this->token = $datos["token"];
            //buscar token en caso de que noe ste envia rray avcio suamso eso para comprobar 
            $arrayToken = $this->buscarToken();

            if ($arrayToken) {
                //hacemos delete
                // ene ste caso debemos requerir el id
                if (!isset($datos["nhc"]) || !isset($datos["cama"]) || !isset($datos["motivo"]) || !isset($datos["tipo"]) || !isset($datos["fecha"]) || !isset($datos["hora"])) {
                    // Si no existen los campos requeridos, enviamos un error 400
                    return $_respuestas->error_400();
                } else {
                    //este es obligatorio
                    $this->nhc = $datos["nhc"];
                    // Llamamos a la función modificar

                    $res = $this->eliminarIngreso();
                    //si hay respueste devolvemos y si es distinto de 0 (0 es como un false)
                    if (isset($res["result"])) {
                        //si hay un resultado, es que hay un error, enseñamo ese error
                        return $res;
                    } else if (isset($res) && $res != 0) {
                        //creamso rsuesta
                        //una vez borrado el ingreso, isnertaremos un valor en alta
                        $respuesta = $_respuestas->response;
                        if ($this->darAlta($datos["fecha"], $datos["hora"], $datos["motivo"], $datos["tipo"], $datos["nhc"])) {
                            //le metemos el id del apciente a la respuesta
                            $respuesta["result"] = array(
                                "mensaje" => "Alta Correcta"
                            );
                        } else {
                            //le metemos el id del apciente a la respuesta
                            $respuesta["result"] = array(
                                "mensaje" => "Ingreso eliminado correctamente"
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
    private function darAlta($fecha, $hora, $motivo, $tipo, $nhc)
    {
        $_respuestas = new respuestas;
        $query = "INSERT INTO alta (fecha, hora, motivo, tipo, nhc) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conexion->prepare($query);
        if (!$stmt) {
            die("Error al preparar la consulta: " . $this->conexion->error);
        }
        //vinculamos
        $stmt->bind_param("ssssi", $fecha, $hora, $motivo, $tipo, $nhc);
        //ejecutamos
        try {
            $resp = $stmt->execute();
        } catch (Exception $e) {
            return $_respuestas->error_200("Error al ejecutar la consulta: " . $e->getMessage());
        }
        if ($resp) {
            // Si se inserta correctamente, enviamos el ID del paciente insertado
            return true;
        } else {
            // Si no se inserta, enviamos 0
            return false;
        }

    }

    private function eliminarIngreso()
    {
        // Creamos la consulta S
        $query = "DELETE FROM $this->tabla WHERE nhc=?";

        // Preparamos la sentencia
        $stmt = $this->conexion->prepare($query);

        // Verificamos si la preparación fue exitosa
        if (!$stmt) {
            // Si la preparación falla, retornamos 0
            return 0;
        }

        // Vinculamos el parámetro PacienteId
        $resultado_bind_param = $stmt->bind_param("i", $this->nhc);

        // Verificamos si la vinculación de parámetros fue exitosa
        if (!$resultado_bind_param) {
            // Si la vinculación de parámetros falla, retornamos 0
            return 0;
        }
        $this->cambioCama($this->nhc, true);
        // Ejecutamos la consulta
        $resp = $stmt->execute();

        // Verificamos si la consulta fue exitosa
        if ($resp) {
            // Devolvemos el número de filas afectadas por eliminacion
            if ($stmt->affected_rows > 0) {
                $this->reservaCama($this->aux, "Disponible");
                return $stmt->affected_rows;
            } else {
                //si se hizo bn la ejcuion y no hay datos
                $_respuestas = new respuestas;
                return $_respuestas->error_200("El paciente no se encuentra ingresado.");
            }
        } else {
            // Si la eliminación falla, devolvemos 0
            return 0;
        }
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
