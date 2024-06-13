<?php
// Permitir solicitudes desde localhost:4200
header("Access-Control-Allow-Origin: *");
// Permitir métodos específicos
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
// Permitir cabeceras específicas
header("Access-Control-Allow-Headers: Content-Type");

//requerimos la de respeustasy la de camas
require_once "clases/respuestas.class.php";
require_once "clases/camas.class.php";
//inicializamos
$_respuestas = new respuestas;
$_camas = new camas;
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
            $listacamas = $_camas->listarCamas();
            header("Content-Type: application/json");
            http_response_code(200);
            echo $listacamas;
            break;
        case 'centro':
            //si no listamos todo
            if (is_numeric($partes[4])) {
                $flag = false;
                if (isset($partes[5]) && is_numeric($partes[5])) {
                    $flag = $partes[5];
                }
                $listacamas = $_camas->listarCamasCentro($partes[4], $flag);
                header("Content-Type: application/json");
                if ($listacamas != null) {

                    http_response_code(200);
                    echo $listacamas;
                } else {
                    http_response_code(404);
                    echo json_encode($_respuestas->error_404());
                }
            } else {
                header("Content-Type: application/json");
                http_response_code(400);
                echo json_encode($_respuestas->error_400());
            }
            break;
        case 'plantas':
            //si no listamos todo
            if (is_numeric($partes[4])) {
                $listacamas = $_camas->listarPlantas($partes[4]);
                header("Content-Type: application/json");
                http_response_code(200);
                echo $listacamas;
            } else {
                header("Content-Type: application/json");
                http_response_code(400);
                echo json_encode($_respuestas->error_400());
            }

            break;
        default:
            //si no es ninguna de esas error
            header("Content-Type: application/json");
            echo json_encode($_respuestas->error_400());
            break;
    }
} else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    //PUT MODIFICAR
    //PUT MODIFICAR
    //recogemos todos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos todo al controlador, clas ingresos, al metodo de post
    $datosArray = $_camas->put($postBody);
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
    http_response_code(405);
    echo $datosArray;
}
