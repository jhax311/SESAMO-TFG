<?php
//controlar la autenticacion por token
//importamos el documento de conexion de bbdd y de respuestas
require_once "conexion/conexion.php";
require_once "respuestas.class.php";
require_once "registrarVisitas.php";
class notasEnfermeria extends conexion
{
    private $tabla = "notas_enfermeria";
    //atributos de la bbdd, lops necesarios
    // private $nhc = "";
    private $fecha_toma = null;
    private $hora_toma = null;
    private $temperatura = null;
    private $tension_arterial_sistolica = null;
    private $tension_arterial_diastolica = null;
    private $frecuencia_cardiaca = null;
    private $frecuencia_respiratoria = null;
    private $peso = null;
    private $talla = null;
    private $indice_masa_corporal = null;
    private $glucemia_capilar = null;
    private $ingesta_alimentos = null;
    private $tipo_deposicion = null;
    private $nauseas = null;
    private $prurito = null;
    private $observaciones = null;
    private $balance_hidrico_entrada_alimentos = null;
    private $balance_hidrico_entrada_liquidos = null;
    private $balance_hidrico_fluidoterapia = null;
    private $balance_hidrico_hemoderivados = null;
    private $balance_hidrico_otros_entrada = null;
    private $balance_hidrico_diuresis = null;
    private $balance_hidrico_vomitos = null;
    private $balance_hidrico_heces = null;
    private $balance_hidrico_sonda_nasogastrica = null;
    private $balance_hidrico_drenajes = null;
    private $balance_hidrico_otras_perdidas = null;
    private $total_balance_hidrico = null;
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
    public function listarNotas()
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
    public function obtenerNotasNhc($campo, $valor)
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
            return $_respuestas->error_200("No existen patologías.");
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
                    !isset($datos["fecha_toma"]) || !isset($datos["hora_toma"]) || !isset($datos["temperatura"]) ||
                    !isset($datos["tension_arterial_sistolica"]) || !isset($datos["tension_arterial_diastolica"]) ||
                    !isset($datos["frecuencia_cardiaca"]) || !isset($datos["frecuencia_respiratoria"]) ||
                    !isset($datos["peso"]) || !isset($datos["talla"]) || !isset($datos["indice_masa_corporal"]) ||
                    !isset($datos["glucemia_capilar"]) || !isset($datos["ingesta_alimentos"]) ||
                    !isset($datos["tipo_deposicion"]) || !isset($datos["nauseas"]) || !isset($datos["prurito"]) ||
                    !isset($datos["observaciones"]) || !isset($datos["balance_hidrico_entrada_alimentos"]) ||
                    !isset($datos["balance_hidrico_entrada_liquidos"]) || !isset($datos["balance_hidrico_fluidoterapia"]) ||
                    !isset($datos["balance_hidrico_hemoderivados"]) || !isset($datos["balance_hidrico_otros_entrada"]) ||
                    !isset($datos["balance_hidrico_diuresis"]) || !isset($datos["balance_hidrico_vomitos"]) ||
                    !isset($datos["balance_hidrico_heces"]) || !isset($datos["balance_hidrico_sonda_nasogastrica"]) ||
                    !isset($datos["balance_hidrico_drenajes"]) || !isset($datos["balance_hidrico_otras_perdidas"]) ||
                    !isset($datos["total_balance_hidrico"]) || !isset($datos["nhc"])
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
                    $res = $this->insertarNotas();


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
                            "msg" => "Se ha creado la nota de enfermeria."
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

    private function insertarNotas()
    {
        //comprobamos de si ya existe un usaurio con ese nif, nhc y numss

        if (!$this->comprobarPaciente($this->nhc)) {
            //si existe error
            $_respuestas = new respuestas;
            return $_respuestas->error_200("El paciente no existe.");
        }
        $ingresado = $this->comprobarIngreso($this->nhc);
        //si no es verdadero, viene el error y lo devolvemos
        if ($ingresado !== true) {
            // El paciente no está ingresado
            return $ingresado;
        }

        //si no existe lo agreagmos
        // Creamos la sentencia
        $query = "INSERT INTO notas_enfermeria (
            fecha_toma, hora_toma, temperatura, tension_arterial_sistolica,
            tension_arterial_diastolica, frecuencia_cardiaca, frecuencia_respiratoria,
            peso, talla, indice_masa_corporal, glucemia_capilar, ingesta_alimentos,
            tipo_deposicion, nauseas, prurito, observaciones, balance_hidrico_entrada_alimentos,
            balance_hidrico_entrada_liquidos, balance_hidrico_fluidoterapia, balance_hidrico_hemoderivados,
            balance_hidrico_otros_entrada, balance_hidrico_diuresis, balance_hidrico_vomitos,
            balance_hidrico_heces, balance_hidrico_sonda_nasogastrica, balance_hidrico_drenajes,
            balance_hidrico_otras_perdidas, total_balance_hidrico, nhc
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparamos la sentencia
        $stmt = $this->conexion->prepare($query);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta.");
        }

        // Vinculamos los parámetros
        $stmt->bind_param(
            'ssdiiiiddddsssssiiiiiiiiiiiii',
            $this->fecha_toma,
            $this->hora_toma,
            $this->temperatura,
            $this->tension_arterial_sistolica,
            $this->tension_arterial_diastolica,
            $this->frecuencia_cardiaca,
            $this->frecuencia_respiratoria,
            $this->peso,
            $this->talla,
            $this->indice_masa_corporal,
            $this->glucemia_capilar,
            $this->ingesta_alimentos,
            $this->tipo_deposicion,
            $this->nauseas,
            $this->prurito,
            $this->observaciones,
            $this->balance_hidrico_entrada_alimentos,
            $this->balance_hidrico_entrada_liquidos,
            $this->balance_hidrico_fluidoterapia,
            $this->balance_hidrico_hemoderivados,
            $this->balance_hidrico_otros_entrada,
            $this->balance_hidrico_diuresis,
            $this->balance_hidrico_vomitos,
            $this->balance_hidrico_heces,
            $this->balance_hidrico_sonda_nasogastrica,
            $this->balance_hidrico_drenajes,
            $this->balance_hidrico_otras_perdidas,
            $this->total_balance_hidrico,
            $this->nhc
        );
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
