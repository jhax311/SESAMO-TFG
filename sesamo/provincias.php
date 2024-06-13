<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods:GET,POST,DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
//requerimos la de respeustasy la de comunidades
require_once "clases/respuestas.class.php";
require_once "clases/provincias.class.php";
//inicializamos
$_respuestas = new respuestas;
$_provincias = new provincias;
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
            $listaProvincias= $_provincias->listarProvincias();
            header("Content-Type: application/json");
            http_response_code(200);
            echo $listaProvincias;
            break;
            
        default:
        if (is_numeric($partes[3])) {//comprobmos que se anumero
            $idComunidad = intval($partes[3]); // pasmaoa a entero

            $provincias = $_provincias->obtenerProvinciasComunidad($idComunidad); //obtenemos las alertas por id

            header("Content-Type: application/json");
        
            if (isset($provincias["result"])) {
                //sie xiste el erro lo enviamso como respuesta http
                $respuestaCode = $provincias["result"]["error_id"];
                //se enviara el erro como respuesta
                http_response_code($respuestaCode);
                echo json_encode($provincias);
            } elseif (!empty($provincias)) {
                // alerta encontrada devolvemos l alerta
                http_response_code(200);
                echo $provincias;
            } else {
                //si no es ninguna de esas error
                http_response_code(404);
                echo json_encode($_respuestas->error_404("No hay provincias"));
            }
        } else {
            //si no es ninguna de esas error
            header("Content-Type: application/json");
            echo json_encode($_respuestas->error_400());

        }
            break;
    }
} else {
    //si no es ninguno, errro 405
    header("Content-Type: application/json");
    $datosArray = $_respuestas->error_405();
    echo $datosArray;
}
