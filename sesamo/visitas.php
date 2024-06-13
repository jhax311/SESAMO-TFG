<?php
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

// Requerir las clases necesarias
require_once "clases/respuestas.class.php";
require_once "clases/visitas.class.php";

// Inicializar clases
$_respuestas = new Respuestas();
$_visitas = new visitas();

include 'config.php';
//OBTENERLA RUTA
$ruta = $_SERVER['REQUEST_URI'];
$partes = explode('/', $ruta);
$indice_sesamo = array_search($nombre_APP, $partes);
if ($indice_sesamo) {
    $partes = array_slice($partes, $indice_sesamo - 1);
}
// Manejar solicitudes GET
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $tipo = isset($partes[3]) ? $partes[3] : '';

    switch ($tipo) {
        case 'dia':
            $datos = $_visitas->obtenerVisitasPorDia();
            http_response_code(200);
            echo json_encode($datos);
            break;
        case 'mes':
            $datos = $_visitas->obtenerVisitasPorMes();
            http_response_code(200);
            echo json_encode($datos);
            break;
        case 'ano':
            $datos = $_visitas->obtenerVisitasPorAno();
            http_response_code(200);
            echo json_encode($datos);
            break;
        case 'total':
            $datos = $_visitas->obtenerTotalVisitas();
            http_response_code(200);
            echo json_encode($datos);
            break;
        case 'paginas':
            $datos = $_visitas->obtenerVisitasPorPagina();
            http_response_code(200);
            echo json_encode($datos);
            break;
        default:
            http_response_code(400);
            echo json_encode($_respuestas->error_400());
            break;
    }
} else {
    http_response_code(405);
    echo json_encode($_respuestas->error_405());
}
?>