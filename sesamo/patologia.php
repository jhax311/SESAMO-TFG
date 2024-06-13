<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
//requerimos la de respeustasy la de hojasPre
require_once "clases/respuestas.class.php";
require_once "clases/patologia.class.php";
include 'config.php';
//inicializamos
$_respuestas = new respuestas;
$_patologia = new patologia;


//metoODOS CRUD, creation read update y delete
//preguntamso el metodo recursivamente

//GET, SOLICITAR LISTAS U RESPUESTAS 
//LAS DIVIDIMOS
$ruta = $_SERVER['REQUEST_URI'];

$partes = explode('/', $ruta);
// Buscamos la posición de "sesamo" en la ruta
$indice_sesamo = array_search($nombre_APP, $partes);

// Verificar si "sesamo" está presente en la ruta
if ($indice_sesamo) {
    // Eliminar los segmentos anteriores a "sesamo" del array
    $partes = array_slice($partes, $indice_sesamo - 1);
}
if ($_SERVER["REQUEST_METHOD"] == "GET") {

    //SWITCH CON LOS CASOS
    switch ($partes[3]) {
        case '':
            //si no listamos todo
            $listaPatologias = $_patologia->listarPatologias();
            header("Content-Type: application/json");
            http_response_code(200);
            echo $listaPatologias;
            break;
        default:
            if (is_numeric($partes[3])) {//comprobamos que sea un numero
                $nhc = intval($partes[3]); //parseamos a entero

                $hojaP = $_patologia->obtenerPatologiaNhc(0, $nhc); 

                header("Content-Type: application/json");

                if (isset($hojaP["result"])) {
                    //sie xiste el erro lo enviamso como respuesta http
                    $respuestaCode = $hojaP["result"]["error_id"];
                    //se enviara el erro como respuesta
                    http_response_code($respuestaCode);
                    echo json_encode($hojaP);
                } elseif (!empty($hojaP)) {
                    // alerta encontrada devolvemos l alerta
                    http_response_code(200);
                    echo $hojaP;
                } else {
                    //si no es ninguna de esas error
                    http_response_code(200);
                    echo json_encode($_respuestas->error_404("No hay hojas de prescripcion asociadas al paciente."));
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
    //enviamos todo al controlador, clas patologia, al metodo de post
    $datosArray = $_patologia->post($postBody);
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
} /*else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    //PUT MODIFICAR
    //PUT MODIFICAR
    //recogemos todos los datos enviados
    $postBody = file_get_contents("php://input");

    //enviamos todo al controlador, clas hojasPre, al metodo de post
    $datosArray = $_hojasPre->put($postBody);
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
    //enviamos todo al controlador, clas hojasPre, al metodo de post
    $datosArray = $_hojasPre->delete($postBody);
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
