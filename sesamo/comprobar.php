<?php
header("Access-Control-Allow-Origin: *");
require_once "clases/comprobar.class.php";
require_once "clases/respuestas.class.php";
include 'config.php';
$_respuestas = new respuestas;
$ruta = $_SERVER['REQUEST_URI'];
$partes = explode('/', $ruta);
$indice_sesamo = array_search($nombre_APP, $partes);
if ($indice_sesamo !== false) {
    $partes = array_slice($partes, $indice_sesamo - 1);
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Verificamos que $partes[3] existe antes de usarlo
    if (isset($partes[3])) {
        switch ($partes[3]) {
            case '':
                $comprobar = new comprobar();
                echo json_encode(["status" => "ok"]);
                http_response_code(200);
                break;
            default:
                echo json_encode(["status" => "error", "message" => "No se encuentra lo que buscas :C"]);
                http_response_code(400);
                break;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Faltan parámetros en la URL"]);
        http_response_code(400);
    }
} else {
    header("Content-Type: application/json");
    $datosArray = $_respuestas->error_405();
    echo $datosArray;
}

?>