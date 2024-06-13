<?php
//controlar la autenticacion por token
//importamos el documento de conexion de bbdd y de respuestas
require_once "conexion/conexion.php";
require_once "respuestas.class.php";
require_once "registrarVisitas.php";

class alertas extends conexion
{
    private $tabla = "alertas";
    private $descripcion = null;
    private $observaciones = null;
    private $fecha = null;
    private $nhc =  null;

    private $token;
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
    public function listarAlertas()
    {

        //si es null se hara esto
        $query = "SELECT * FROM $this->tabla";
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
    public function obtenerAlertaPorNhc($id, $flag = false)
    {
        $_respuestas = new respuestas;
        $existePaciente = $this->comprobarPaciente($id);
        if (isset($existePaciente["result"])) {
            return $existePaciente;
        }

        // Creamos la consulta para obtener la alerta del paciente por nhc
        $query = "SELECT * FROM $this->tabla WHERE nhc = ?";

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

        // Verificamos si hay al menos una fila de resultados
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
                if (!isset($datos["nhc"]) || !isset($datos["descripcion"]) || !isset($datos["fecha"])) {
                    // Si no existen los campos requeridos, enviamos un error 400
                    return $_respuestas->error_200($datos["nhc"]);
                } else {
                    // Si existen, los asignamos a los atributos, atributos obligatorios
                    foreach ($datos as $clave => $valor) {
                        if (property_exists($this, $clave)) {
                            $this->{$clave} = $valor;
                        }
                    }

                    // Llamamos a la función para insertar el paciente y almacenamos el resultado
                    //   $res = $this->comprobarAlerta($this->nhc,$this->nif,$this->numeroSS);

                    $res = $this->insertarAlerta();

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
                            "msg" => "Se ha creado la alerta"
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
    //insertar alerta

    private function insertarAlerta()
    {
        $_respuestas = new respuestas;
        //comprobamos de si ya existe un usaurio con ese nif, nhc y numss

        if (!$this->comprobarPaciente($this->nhc)) {
            //si existe error
            return $_respuestas->error_200("No se encuentra al paciente");
        } else if ($this->obtenerAlertaPorNhc($this->nhc, true)) {
            return $_respuestas->error_500("Ya hay una alerta asignada");
        } else {
            //si no existe lo agreagmos
            // Creamos la sentencia
            $query = "INSERT INTO $this->tabla (descripcion,observaciones,fecha,nhc) VALUES ( ?, ?, ?, ?)";

            // Preparamos la sentencia
            $stmt = $this->conexion->prepare($query);

            if (!$stmt) {
                // Ocurrió un error al preparar la consulta
                echo "Error al preparar la consulta.";
            }


            // Vinculamos los parámetros
            $stmt->bind_param(
                "sssi",
                $this->descripcion,
                $this->observaciones,
                $this->fecha,
                $this->nhc
            );
            ////////////////////////////////////////////////////////////////////////////////////PERFECIONAR LOS ERRORES
            if (!$stmt) {
                echo "Error al vincular los parámetros: " . $stmt->error;
            }

            // Ejecutamos la consulta
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
                return 0;
            }
        }
    }
    public function comprobarPaciente($id)
    {
        //query que nos devolver el nombre completo, la edad y el id de cama, sera el conjunto de planta. habitacion y letra
        $query = "SELECT * from pacientes where nhc=?";
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
            return $_respuestas->error_200("No se encuentra el paciente");
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
                if (!isset($datos["nhc"])) {
                    // Si no existen los campos requeridos, enviamos un error 400
                    return $_respuestas->error_200();
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
                            if ( $valor !== null && $valor !== 0 && $clave != "token" && $clave != "nhc") {
                                //comprobamos los tipos especiales
                                //nhc sera entero, sexo debe ser una letra, fallecido igual
                                //si esta el nhc y es numerico
                                //si no es ninguno de estos seran estrings
                                $camposValores[] = "$clave=?"; //añadimnos a la query segun si existe a dato o no
                                $tiposDatos .= "s";   //de la msiam manera añadimos el tipo de datos
                                $params[] = $this->$clave; //y añadimos el parametro que pasaremos

                            }
                        }
                    }
                    // Llamamos a la función para insertar el paciente y almacenamos el resultado
                    //   $res = $this->comprobarPaciente($this->nhc,$this->nif,$this->numeroSS);
                    $res = $this->modificarAlerta($camposValores, $tiposDatos, $params);
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
                            "msg" => "Se ha modificado la alerta de forma correcta"
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
    private function modificarAlerta($camposValores, $tiposDatos, $params)
    {
        //comprobamos is existe le paciente y tiene alguna alrta asiganda
        $_respuestas = new respuestas;
        //comprobamos de si existe el paciente y si hay na alerta asiganda
        $existePacienteAlerta = $this->obtenerAlertaPorNhc($this->nhc, true);
        if (isset($existePacienteAlerta["result"])) {
            return $existePacienteAlerta;
        } else if ($existePacienteAlerta == null) {
            return $_respuestas->error_200("No hay una alerta asociada al paciente");
        } else {
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
        }

    }

    //BORRAR
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
                if (!isset($datos["nhc"])) {
                    // Si no existen los campos requeridos, enviamos un error 400
                    return $_respuestas->error_400();
                } else {
                    //este es obligatorio
                    $this->nhc = $datos["nhc"];
                    // Llamamos a la función modificar
                    $res = $this->eliminarAlerta();
                    //si hay respueste devolvemos y si es distinto de 0 (0 es como un false)
                    if (isset($res["result"])) {
                        //si hay un resultado, es que hay un error, enseñamo ese error
                        return $res;
                    } else if (isset($res) && $res != 0) {
                        //creamso rsuesta
                        $respuesta = $_respuestas->response;
                        //le metemos el id del apciente a la respuesta
                        $respuesta["result"] = array(
                            "mensaje" => "Alerta eliminada correctamente"
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

    private function eliminarALerta()
    {
        $_respuestas = new respuestas;
        //comprobamos de que exista el paciente, la alerta 
        $existePacienteAlerta = $this->obtenerAlertaPorNhc($this->nhc, true);
        if (isset($existePacienteAlerta["result"])) {
            return $existePacienteAlerta;
        } else if ($existePacienteAlerta == null) {
            return $_respuestas->error_200("No hay una alerta asociada al paciente");
        } else {
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
            // Ejecutamos la consulta
            $resp = $stmt->execute();

            // Verificamos si la consulta fue exitosa
            if ($resp) {
                // Devolvemos el número de filas afectadas por eliminacion
                if ($stmt->affected_rows > 0) {
                    return $stmt->affected_rows;
                } else {
                    //si se hizo bn la ejcuion y no hay datos

                    return $_respuestas->error_200("La alerta no existe no existe");
                }
            } else {
                // Si la eliminación falla, devolvemos 0
                return 0;
            }
        }
    }



    //buscar token
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
