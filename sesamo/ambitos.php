<?php
header("Access-Control-Allow-Origin: *");
//requerimos la de respeustasy la de perfil
require_once "clases/respuestas.class.php";
require_once "clases/ambitos.class.php";
//inicializamos
$_respuestas = new respuestas;
$_ambitos = new ambitos;
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
            $listaAmbitos= $_ambitos->listarAmbitos();
            header("Content-Type: application/json");
            http_response_code(200);
            echo $listaAmbitos;
            break;
        default:
            //si no es ninguna de esas error
            header("Content-Type: application/json");
            echo json_encode($_respuestas->error_400());
            break;
    }
} else {
    //si no es ninguno, errro 405
    header("Content-Type: application/json");
    $datosArray = $_respuestas->error_405();
    echo $datosArray;
}
