<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
//requerimos la de respeustasy la de pacientes
require_once "clases/respuestas.class.php";
require_once "clases/pacientes.class.php";
//inicializamos
$_respuestas = new respuestas;
$_pacientes = new pacientes;
include 'config.php';
    //OBTENERLA RUTA
    $ruta = $_SERVER['REQUEST_URI'];
    $partes = explode('/', $ruta);
    $indice_sesamo = array_search($nombre_APP, $partes);
    if ($indice_sesamo) {
        $partes = array_slice($partes, $indice_sesamo - 1);
    }

//metoODOS CRUD, creation read update y delete
//preguntamso el metodo recursivamente

//GET, SOLICITAR LISTAS U RESPUESTAS 
if ($_SERVER["REQUEST_METHOD"] == "GET") {


    //SWITCH CON LOS CASOS
    switch ($partes[3]) {
        case '':
            //si no listamos todo
            $listaPacientes = $_pacientes->listarPacientes(null);
            header("Content-Type: application/json");
            http_response_code(200);
            echo $listaPacientes;
            break;
        case 'page':
            // Si la acción es 'page', mostrar pacientes por página
            if (isset($partes[4])) {
                $pagina = $partes[4];
                $listaPacientes = $_pacientes->listarPacientes($pagina);
                header("Content-Type: application/json");
                http_response_code(200);
                echo $listaPacientes;
            } else {
                //error de que coja una pagina
            }
            break;
        case 'nhc':
            // si es nch
            if (isset($partes[4])) {
                //cogemos nhc
                $pacienteId = $partes[4];
                //llamamos a la funcion
                $datosPaciente = $_pacientes->obtenerPacienteCampoValor(0, $pacienteId);
                header("Content-Type: application/json");
                //comprobamos si hay unerror 
                if (isset($datosPaciente["result"]["error_id"])) {
                    //sie xiste el erro lo enviamso como respuesta http
                    $respuestaCode = $datosPaciente["result"]["error_id"];
                    //se enviara el erro como respuesta
                    http_response_code($respuestaCode);
                    $datosPaciente = json_encode($datosPaciente);
                } else {
                    //si el erro no existe, todo es correcto enviamos el codigo 200 y la respuesta
                    http_response_code(200);
                }
                echo $datosPaciente;
            } else {
                //error de que coja una pagina
            }

            break;
        case 'nif':
            // si es nch
            if (isset($partes[4])) {
                //listar por nhc////////////////////////////////////////////////////////////////
                //cogemos nhc
                $pacienteId = $partes[4];
                //llamamos a la funcion
                $datosPaciente = $_pacientes->obtenerPacienteCampoValor(1, $pacienteId);
                //headers
                header("Content-Type: application/json");
                //comprobamos si hay unerror 
                if (isset($datosPaciente["result"]["error_id"])) {
                    //sie xiste el erro lo enviamso como respuesta http
                    $respuestaCode = $datosPaciente["result"]["error_id"];
                    //se enviara el erro como respuesta
                    http_response_code($respuestaCode);
                    $datosPaciente = json_encode($datosPaciente);
                } else {
                    //si el erro no existe, todo es correcto enviamos el codigo 200 y la respuesta
                    http_response_code(200);
                }
                echo $datosPaciente;
            } else {
                //error de que coja una pagina
            }

            break;
        case 'numss':
            // si es nch
            if (isset($partes[4])) {
                //listar por nhc////////////////////////////////////////////////////////////////
                //cogemos nhc
                $pacienteId = $partes[4];
                //llamamos a la funcion
                $datosPaciente = $_pacientes->obtenerPacienteCampoValor(2, $pacienteId);
                //headers
                header("Content-Type: application/json");
                //comprobamos si hay unerror 
                if (isset($datosPaciente["result"]["error_id"])) {
                    //sie xiste el erro lo enviamso como respuesta http
                    $respuestaCode = $datosPaciente["result"]["error_id"];
                    //se enviara el erro como respuesta
                    http_response_code($respuestaCode);
                    $datosPaciente = json_encode($datosPaciente);
                } else {
                    //si el erro no existe, todo es correcto enviamos el codigo 200 y la respuesta
                    http_response_code(200);
                }
                echo $datosPaciente;
            }
            break;
        case 'zona':
            // si es nch
            if (isset($partes[4])) {
                //listar por nhc////////////////////////////////////////////////////////////////
                //cogemos nhc
                $zonaId = $partes[4];
                //llamamos a la funcion
                $datosPaciente = $_pacientes->obtenerPacienteCampoValor(3, $zonaId, true);
                //headers
                header("Content-Type: application/json");
                //comprobamos si hay unerror 
                if (isset($datosPaciente["result"]["error_id"])) {
                    //sie xiste el erro lo enviamso como respuesta http
                    $respuestaCode = $datosPaciente["result"]["error_id"];
                    //se enviara el erro como respuesta
                    http_response_code($respuestaCode);
                    $datosPaciente = json_encode($datosPaciente);
                } else {
                    //si el erro no existe, todo es correcto enviamos el codigo 200 y la respuesta
                    http_response_code(200);
                }
                echo $datosPaciente;
            }
            break;
        case 'cama':
            // si es nch
            if (isset($partes[4])) {
                //listar por nhc////////////////////////////////////////////////////////////////
                //cogemos nhc
                $camaId = $partes[4];
                //llamamos a la funcion
                $datosPaciente = $_pacientes->obtenerPacienteCama($camaId);
                //headers
                header("Content-Type: application/json");
                //comprobamos si hay unerror 
                if (isset($datosPaciente["result"]["error_id"])) {
                    //sie xiste el erro lo enviamso como respuesta http
                    $respuestaCode = $datosPaciente["result"]["error_id"];
                    //se enviara el erro como respuesta
                    http_response_code($respuestaCode);
                    $datosPaciente = json_encode($datosPaciente);
                } else {
                    //si el erro no existe, todo es correcto enviamos el codigo 200 y la respuesta
                    http_response_code(200);
                }
                echo $datosPaciente;
            }
            break;
        default:
            //si no es ninguna de esas error
            header("Content-Type: application/json");
            echo json_encode($_respuestas->error_400());
            break;
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //POST, PARA GAURDAR
    //inteamos crear un auqery dinamica
    //recogemos todos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos todo al controlador, clas pacientes, al metodo de post
    $datosArray = $_pacientes->post($postBody);
    header("Content-Type: application/json");
    if (isset($datosArray["result"]["error_id"])) {
        //sie xiste el erro lo enviamso como respuesta http
        $respuestaCode = $datosArray["result"]["error_id"];
        //se enviara el erro como respuesta
        http_response_code($respuestaCode);
    } else {
        //si el erro no existe, todo es correcto enviamos el codigo 200 y la respuesta
        http_response_code(200);
    }
    echo json_encode($datosArray);
} else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    //PUT MODIFICAR
    //PUT MODIFICAR
    //recogemos todos los datos enviados
    $postBody = file_get_contents("php://input");
   
    //enviamos todo al controlador, clas pacientes, al metodo de post
    $datosArray = $_pacientes->put($postBody);
    //devolvemos uan respuesta
    header("Content-Type: application/json");
    if (isset($datosArray["result"]["error_id"])) {
        //sie xiste el erro lo enviamso como respuesta http
        $respuestaCode = $datosArray["result"]["error_id"];
        //se enviara el erro como respuesta
        http_response_code($respuestaCode);
    } else {
        //si el erro no existe, todo es correcto enviamos el codigo 200 y la respuesta
        http_response_code(200);
    }
    echo json_encode($datosArray);
} else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    //DELETE, BORRAR
    //recogemos todos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos todo al controlador, clas pacientes, al metodo de post
    $datosArray = $_pacientes->delete($postBody);
    //devolvemos uan respuesta

    header("Content-Type: application/json");
    if (isset($datosArray["result"]["error_id"])) {
        //sie xiste el erro lo enviamso como respuesta http
        $respuestaCode = $datosArray["result"]["error_id"];
        //se enviara el erro como respuesta
        http_response_code($respuestaCode);
    } else {
        //si el erro no existe, todo es correcto enviamos el codigo 200 y la respuesta
        http_response_code(200);
    }
    echo json_encode($datosArray);
} else {
    //si no es ninguno, errro 405
    header("Content-Type: application/json");
    $datosArray = $_respuestas->error_405();
    echo $datosArray;
}
