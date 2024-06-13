<?php
//controlar la autenticacion por token
//importamos el documento de conexion de bbdd y de respuestas
require_once "conexion/conexion.php";
require_once "respuestas.class.php";
require_once "registrarVisitas.php";
class pacientes extends conexion
{
    private $tabla = "pacientes";
    //atributos de la bbdd, lops necesarios
    // private $nhc = "";
    private $nif = "";
    private $numeroSS = "";
    private $nombre = "";
    private $apellido1 = null;
    private $apellido2 = null;
    private $sexo = null;
    private $fechaNacimiento = null; //"0000-00-00";  //campo obligatorio o cmabiar las netencia 
    private $telefono1 = 0;
    private $telefono2 = 0;
    private $movil = 0;
    private $estadoCivil = null;
    private $estudios = null;
    private $fallecido = null;
    private $nacionalidad = null;
    private $cAutonoma = null;
    private $provincia = null;
    private $poblacion = null;
    private $cp = 0;
    private $direccion = null;
    private $titular = null;
    private $regimen = null;
    private $tis = null;
    private $medico = null;
    private $cap = null;
    private $zona = null;
    private $area = null;
    private $nacimiento = null;
    private $cAutonomaNacimiento = null;
    private $provinciaNacimiento = null;
    private $poblacionNacimiento = null;
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
    public function listarPacientes($pagina = null)
    {
        //habra dos casos listar todos los pacientes o por cantidades
        //si pagina no es null
        if ($pagina != null) {
            //en caso de que hayn muchos pacientes
            //empezms en 0
            $inicio = 0;
            //cantidad que se mostraremos
            $cantidad = 15;
            //
            if ($pagina > 1) {
                //iicio se ira calculado segun la pagina
                $inicio = ($cantidad * ($pagina - 1)) + 1;
                //modifcamos la cantidad
                $cantidad = $cantidad * $pagina;
            }
            //creamos la consulta
            $query = "SELECT * FROM $this->tabla limit $inicio,$cantidad";
            //prepramos
            $stmt = $this->conexion->prepare($query);
            //ejecutamos la consuklta
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
        } else {
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
    //listar paciente por nhc nift y numss, segun el campo que se le pase
    public function obtenerPacienteCampoValor($campo, $valor, $flag = false)
    {
        if ($campo == 0) {
            $campo = "nhc";
        } elseif ($campo == 1) {
            $campo = "nif";
        } else if ($campo == 2) {
            $campo = "numeroSS";
        } else if ($campo == 3) {
            $campo = "zona";
        } else {
            //si no es ninguno de estos
            // si no hay error dos con emnsaje
            $_respuestas = new respuestas;
            return $_respuestas->error_401();
        }
        //obtener paciente por nhc
        //creamos la consulta

        if ($flag) {
            $valor = $this->obtenerZona($valor);
        }

        $query = "SELECT * FROM $this->tabla where $campo=?";
        //prepramos
        $stmt = $this->conexion->prepare($query);
        //ejecutamos la consuklta
        // Vincular el parámetro de nhc
        $stmt->bind_param('s', $valor);
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
            if ($campo == "zona") {
                return $_respuestas->error_200("No existen pacientes en la zona");
            } else {
                return $_respuestas->error_200("No existe el paciente con el " . strtoupper($campo) . " insertado");
            }


        }
    }

    public function obtenerZona($id)
    {
        $query = "SELECT nombre FROM zonaBasicaSalud where id_zona_basica_salud=?";

        //prepramos
        $stmt = $this->conexion->prepare($query);
        //ejecutamos la consuklta
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
            $fila = $result->fetch_assoc();
            return $fila["nombre"];
        } else {
            // si no hay error dos con emnsaje
            $_respuestas = new respuestas;
            return $_respuestas->error_404("No se encontraron datos");
        }
    }

    //CAMAAAAAAAAAAAAAAA
    public function obtenerPacienteCama($id)
    {
        //query que nos devolver el nombre completo, la edad y el id de cama, sera el conjunto de planta. habitacion y letra, y dtaos de patologias
        //ordema de manera descendiente por fecha de dianostico, y cogeremos la ultima, limit 1
        $query = "
       SELECT 
        CONCAT(p.Nombre, ' ', p.Apellido1, ' ', p.Apellido2) AS Nombre,
        YEAR(CURDATE()) - YEAR(p.FechaNacimiento) - 
        (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(p.FechaNacimiento, '%m%d')) AS Edad,
        CONCAT(c.id_planta, '', c.id_habitacion, '', c.letra) AS Cama,
        p.nhc AS nhc,
        p.sexo AS sexo,
        i.ingresado AS ingresado,
        pa.fecha_inicio AS FechaInicioPatologia,
        pa.fecha_diagnostico AS FechaDiagnostico,
        pa.sintomas AS Sintomas,
        pa.diagnostico AS Diagnostico,
        pa.especialidad AS Especialidad,
        pa.codificacion AS Codificacion
    FROM 
        ingresos AS i
    INNER JOIN 
        camas AS c ON i.id_cama = c.id_cama
    INNER JOIN 
        pacientes AS p ON i.nhc = p.nhc
    LEFT JOIN 
        patologia AS pa ON p.nhc = pa.nhc
    WHERE 
        c.id_cama = ? 
        AND i.ingresado = 1
    ORDER BY 
        pa.fecha_diagnostico DESC
    LIMIT 1";
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

        // Verificar si se encontraron datos, si hay mas de 0 filas hay datos, debemso preaprar la repuesta
        if ($num_rows > 0) {
            $datos = array();
            $paciente = null;
            while ($fila = $result->fetch_assoc()) {
                if ($paciente === null) {
                    $paciente = [
                        'Nombre' => $fila['Nombre'],
                        'Edad' => $fila['Edad'],
                        'Cama' => $fila['Cama'],
                        'nhc' => $fila['nhc'],
                        'Sexo' => $fila['sexo'],
                        'ingresado' => $fila['ingresado'],
                        'Patologias' => []
                    ];
                }

                // Añadir patologías en casod e que existan
                if ($fila['FechaInicioPatologia'] !== null) {
                    $paciente['Patologias'][] = [
                        'FechaInicioPatologia' => $fila['FechaInicioPatologia'],
                        'FechaDiagnostico' => $fila['FechaDiagnostico'],
                        'Sintomas' => $fila['Sintomas'],
                        'Diagnostico' => $fila['Diagnostico'],
                        'Especialidad' => $fila['Especialidad'],
                        'Codificacion' => $fila['Codificacion']
                    ];
                }
            }
            $datos[] = $paciente;

            return json_encode($datos);
        } else {
            // si no hay error dos con emnsaje
            $_respuestas = new respuestas;
            return $_respuestas->error_404("No se encontraron datos ");
        }
    }

    /**public function obtenerPacienteCama($id)
        {
            //query que nos devolver el nombre completo, la edad y el id de cama, sera el conjunto de planta. habitacion y letra
            $query = "SELECT 
                CONCAT(p.Nombre, ' ', p.Apellido1, ' ', p.Apellido2) AS Nombre,
                YEAR(CURDATE()) - YEAR(p.FechaNacimiento) - (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(p.FechaNacimiento, '%m%d')) AS Edad,
                CONCAT(c.id_planta, '', c.id_habitacion, '', c.letra) AS Cama, p.nhc as nhc 
              FROM 
                ingresos AS i
              INNER JOIN 
                camas AS c ON i.id_cama = c.id_cama
              INNER JOIN 
                pacientes AS p ON i.nhc = p.nhc
              WHERE 
                c.id_cama = ?";
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
                return $_respuestas->error_404("No se encontraron datos ");
            }
        } */


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
                if (!isset($datos["nif"]) || !isset($datos["numeroSS"])) {
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

                    $res = $this->insertarPaciente();

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
                            "msg" => "Se ha insertado el paciente de forma correcta"
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

    //insertar paciente

    private function insertarPaciente()
    {
        //comprobamos de si ya existe un usaurio con ese nif, nhc y numss

        if ($this->comprobarPaciente($this->nif, $this->numeroSS)) {
            //si existe error
            $_respuestas = new respuestas;
            return $_respuestas->error_200("El paciente ya existe");
        } else {
            //si no existe lo agreagmos
            // Creamos la sentencia
            $query = "INSERT INTO $this->tabla (NIF, numeroSS, Nombre, Apellido1, Apellido2, Sexo, FechaNacimiento, Telefono1,";
            $query .= " Telefono2, Movil, EstadoCivil, Estudios, Fallecido, Nacionalidad, CAutonoma, Provincia, Poblacion, CP, Direccion,";
            $query .= " Titular, Regimen, TIS, Medico, CAP, Zona, Area, Nacimiento, CAutonomaNacimiento, ProvinciaNacimiento, PoblacionNacimiento) ";
            $query .= " VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            // Preparamos la sentencia
            $stmt = $this->conexion->prepare($query);

            if (!$stmt) {
                // Ocurrió un error al preparar la consulta
                echo "Error al preparar la consulta.";
            }

            // Vinculamos los parámetros
            $stmt->bind_param(
                "sisssssiiisssssssissssssssssss",
                $this->nif,
                $this->numeroSS,
                $this->nombre,
                $this->apellido1,
                $this->apellido2,
                $this->sexo,
                $this->fechaNacimiento,
                $this->telefono1,
                $this->telefono2,
                $this->movil,
                $this->estadoCivil,
                $this->estudios,
                $this->fallecido,
                $this->nacionalidad,
                $this->cAutonoma,
                $this->provincia,
                $this->poblacion,
                $this->cp,
                $this->direccion,
                $this->titular,
                $this->regimen,
                $this->tis,
                $this->medico,
                $this->cap,
                $this->zona,
                $this->area,
                $this->nacimiento,
                $this->cAutonomaNacimiento,
                $this->provinciaNacimiento,
                $this->poblacionNacimiento
            );
            ////////////////////////////////////////////////////////////////////////////////////PERFECIONAR LOS ERRORES
            if (!$stmt) {
                echo "Error al vincular los parámetros: " . $stmt->error;
            }

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
                // Si se inserta correctamente, enviamos el ID del paciente insertado
                return true;
            } else {
                // Si no se inserta, enviamos 0
                return 0;
            }
        }
    }
    //comprobar si el paciente existe
    private function comprobarPaciente($nif, $numeroSS)
    {
        // Creamos la consulta para verificar si el paciente ya existe, buscara por nhc, nif o seguridad social
        $query = "SELECT COUNT(*) AS count FROM $this->tabla WHERE NIF = ? or NumeroSS = ?";

        // Preparamos la consulta
        $stmt = $this->conexion->prepare($query);
        // Vinculamos los parámetros
        $stmt->bind_param("ss", $nif, $numeroSS);
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


    //PUTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTTT
    public function put($json) //reciben un json como parametro<
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
                if (!isset($datos["nif"]) || !isset($datos["numeroSS"])) {
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
                            if ($valor !== null && $clave != "token" && $clave != "nhc") {
                                //comprobamos los tipos especiales
                                //nhc sera entero, sexo debe ser una letra, fallecido igual
                                //si esta el nhc y es numerico
                                if ($clave == "numeroSS") {
                                    if (is_numeric($valor)) {
                                        $camposValores[] = "numeroSS=?"; //añadimnos a la query segun si existe a dato o no
                                        $tiposDatos .= "i";   //de la msiam manera añadimos el tipo de datos
                                        $params[] = $this->{$clave}; //y añadimos el parametro que pasaremos
                                    } else {
                                        // si no es numerico error
                                        return $_respuestas->error_400();
                                    }
                                } elseif ($clave == "telefono1") {
                                    if (is_numeric($valor)) {
                                        $camposValores[] = "telefono1=?"; //añadimnos a la query segun si existe a dato o no
                                        $tiposDatos .= "i";   //de la msiam manera añadimos el tipo de datos
                                        $params[] = $this->{$clave}; //y añadimos el parametro que pasaremos
                                    } else {
                                        //si e snumerico o son mas de una letra
                                        return $_respuestas->error_400();
                                    }
                                } elseif ($clave == "telefono2") {
                                    if (is_numeric($valor)) {
                                        $camposValores[] = "telefono2=?"; //añadimnos a la query segun si existe a dato o no
                                        $tiposDatos .= "i";   //de la msiam manera añadimos el tipo de datos
                                        $params[] = $this->{$clave}; //y añadimos el parametro que pasaremos
                                    } else {
                                        //si e snumerico o son mas de una letra
                                        return $_respuestas->error_400();
                                    }
                                } elseif ($clave == "movil") {
                                    if (is_numeric($valor)) {
                                        $camposValores[] = "movil=?"; //añadimnos a la query segun si existe a dato o no
                                        $tiposDatos .= "i";   //de la msiam manera añadimos el tipo de datos
                                        $params[] = $this->{$clave}; //y añadimos el parametro que pasaremos
                                    } else {
                                        //si e snumerico o son mas de una letra
                                        return $_respuestas->error_400();
                                    }
                                } elseif ($clave == "cp") {
                                    if (is_numeric($valor)) {
                                        $camposValores[] = "cp=?"; //añadimnos a la query segun si existe a dato o no
                                        $tiposDatos .= "i";   //de la msiam manera añadimos el tipo de datos
                                        $params[] = $this->{$clave}; //y añadimos el parametro que pasaremos
                                    } else {
                                        //si e snumerico o son mas de una letra
                                        return $_respuestas->error_400();
                                    }
                                } elseif ($clave == "sexo") {
                                    if (!is_numeric($valor)) {
                                        $camposValores[] = "sexo=?"; //añadimnos a la query segun si existe a dato o no
                                        $tiposDatos .= "s";   //de la msiam manera añadimos el tipo de datos
                                        $params[] = $this->{$clave}; //y añadimos el parametro que pasaremos
                                    } else {
                                        //si e snumerico o son mas de una letra
                                        return $_respuestas->error_400();
                                    }
                                } /*elseif ($clave == "fallecido") {
                                   if (is_numeric($valor) && strlen($valor) == 1) {
                                       $camposValores[] = "fallecido=?"; //añadimnos a la query segun si existe a dato o no
                                       $tiposDatos .= "i";   //de la msiam manera añadimos el tipo de datos
                                       $params[] = $this->{$clave}; //y añadimos el parametro que pasaremos
                                   } else {
                                       //si e snumerico o son mas de una letra
                                       return $_respuestas->error_400();
                                   }
                               } */ else {
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
                            "msg" => "Se ha modificado el paciente de forma correcta"
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
        $query .= " WHERE NIF=?";
        $params[] = $this->nif;
        // Agregamos el tipo de dato para el campo PacienteId
        $tiposDatos .= "s";
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
                $_respuestas = new respuestas;
                return $_respuestas->error_200("No hay datos que modificar");
            }
        } else {
            // Si no se actualiza ninguna fila, devolvemos 0
            return 0;
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
                if (!isset($datos["nif"])) {
                    // Si no existen los campos requeridos, enviamos un error 400
                    return $_respuestas->error_400();
                } else {
                    //este es obligatorio
                    $this->nif = $datos["nif"];
                    // Llamamos a la función modificar
                    $res = $this->eliminarPaciente();
                    //si hay respueste devolvemos y si es distinto de 0 (0 es como un false)
                    if (isset($res["result"])) {
                        //si hay un resultado, es que hay un error, enseñamo ese error
                        return $res;
                    } else if (isset($res) && $res != 0) {
                        //creamso rsuesta
                        $respuesta = $_respuestas->response;
                        //le metemos el id del apciente a la respuesta
                        $respuesta["result"] = array(
                            "mensaje" => "Paciente eliminado correctamente"
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

    private function eliminarPaciente()
    {
        // Creamos la consulta S
        $query = "DELETE FROM $this->tabla WHERE NIF=?";

        // Preparamos la sentencia
        $stmt = $this->conexion->prepare($query);

        // Verificamos si la preparación fue exitosa
        if (!$stmt) {
            // Si la preparación falla, retornamos 0
            return 0;
        }

        // Vinculamos el parámetro PacienteId
        $resultado_bind_param = $stmt->bind_param("s", $this->nif);

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
                $_respuestas = new respuestas;
                return $_respuestas->error_200("El paciente no existe");
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
