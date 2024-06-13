<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
//requerimos la de respeustasy la de ingresos
require_once "clases/respuestas.class.php";
require_once "clases/ingresos.class.php";
//inicializamos
$_respuestas = new respuestas;
$_ingresos = new ingresos;
include 'config.php';
    //OBTENERLA RUTA
    $ruta = $_SERVER['REQUEST_URI'];
    $partes = explode('/', $ruta);
    $indice_sesamo = array_search($nombre_APP, $partes);
    if ($indice_sesamo) {
        $partes = array_slice($partes, $indice_sesamo - 1);
    }

//metoODOS CRUD, creation read update y delete
//preguntamso el metodo recursivamentess

//GET, SOLICITAR LISTAS U RESPUESTAS 
if ($_SERVER["REQUEST_METHOD"] == "GET") {

 
    //SWITCH CON LOS CASOS
    switch ($partes[3]) {
        case '':
            //si no listamos todo
            $listaIngresos= $_ingresos->listarIngresos();
            header("Content-Type: application/json");
            http_response_code(200);
            echo $listaIngresos;
            break;
        case 'nhc':
            // si es nch
            if (isset($partes[4])) {
                //cogemos nhc
                $pacienteId  = $partes[4];
                //llamamos a la funcion
                $datosPaciente = $_ingresos->obtenerIngresoCampoValor(0, $pacienteId);
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
                $pacienteId  = $partes[4];
                //llamamos a la funcion
                $datosPaciente = $_ingresos->obtenerIngresoCampoValor(1, $pacienteId);
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
    //enviamos todo al controlador, clas ingresos, al metodo de post
    $datosArray = $_ingresos->post($postBody);
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
    //enviamos todo al controlador, clas ingresos, al metodo de post
    $datosArray = $_ingresos->put($postBody);
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
}/* else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    //DELETE, BORRAR
    //recogemos todos los datos enviados
    $postBody = file_get_contents("php://input");
    //enviamos todo al controlador, clas ingresos, al metodo de post
    $datosArray = $_ingresos->delete($postBody);
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
}*/ else {
    //si no es ninguno, errro 405
    header("Content-Type: application/json");
    $datosArray = $_respuestas->error_405();
    echo $datosArray;
}
