<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
//requerimos la de respeustasy la de perfil
require_once "clases/respuestas.class.php";
require_once "clases/alertas.class.php";
include 'config.php';

//inicializamos
$_respuestas = new respuestas;
$_alertas = new alertas;

    //OBTENERLA RUTA
    $ruta = $_SERVER['REQUEST_URI'];
    $partes = explode('/', $ruta);
    $indice_sesamo = array_search($nombre_APP, $partes);
    if ($indice_sesamo) {
        $partes = array_slice($partes, $indice_sesamo - 1);
    }

//GET, SOLICITAR LISTAS U RESPUESTAS 
if ($_SERVER["REQUEST_METHOD"] == "GET") {

  
    //SWITCH CON LOS CASOS
    switch ($partes[3]) {
        case '':
            //si no listamos todo
            $listaZonas = $_alertas->listaralertas();
            header("Content-Type: application/json");
            http_response_code(200);
            echo $listaZonas;
            break;

        default:
            if (is_numeric($partes[3])) {//comprobmos que se anumero
                $alertaNhc = intval($partes[3]); // pasmaoa a entero

                $alerta = $_alertas->obtenerAlertaPorNhc($alertaNhc); //obtenemos las alertas por id

                header("Content-Type: application/json");
            
                if (isset($alerta["result"])) {
                    //sie xiste el erro lo enviamso como respuesta http
                    $respuestaCode = $alerta["result"]["error_id"];
                    //se enviara el erro como respuesta
                    http_response_code($respuestaCode);
                    echo json_encode($alerta);
                } elseif (!empty($alerta)) {
                    // alerta encontrada devolvemos l alerta
                    http_response_code(200);
                    echo $alerta;
                } else {
                    //si no es ninguna de esas error
                    http_response_code(200);
                    echo json_encode($_respuestas->error_404("No hay alertas asociadas al paciente"));
                }
            } else {
                //si no es ninguna de esas error
                header("Content-Type: application/json");
                echo json_encode($_respuestas->error_400());

            }
            break;

    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //POST, PARA GAURDAR
    //inteamos crear un auqery dinamica
    //recogemos todos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos todo al controlador, clas pacientes, al metodo de post
    $datosArray = $_alertas->post($postBody);
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
    $datosArray = $_alertas->put($postBody);
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
    $datosArray = $_alertas->delete($postBody);
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
    http_response_code(405);
    $datosArray = $_respuestas->error_405();
    echo $datosArray;
}
