<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

require_once "clases/respuestas.class.php";
require_once "clases/recuperarPassword.class.php";
include 'config.php';

$_respuestas = new respuestas;
$_passwordRecovery = new recuperarPassword;

$ruta = $_SERVER['REQUEST_URI'];
$partes = explode('/', $ruta);
$indice_sesamo = array_search($nombre_APP, $partes);
if ($indice_sesamo) {
    $partes = array_slice($partes, $indice_sesamo - 1);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postBody = json_decode(file_get_contents('php://input'), true);

    if (!isset($postBody['usuario']) || !isset($postBody['accion'])) {
        echo json_encode($_respuestas->error_400("Datos incompletos"));
        http_response_code(400);
        exit();
    }
    
    $accion = $postBody['accion'];
    $userName = $postBody['usuario'];

    switch ($accion) {
        case 'recuperarContrasena':
            $response = $_passwordRecovery->recuperarContrasena($userName);
            break;
        case 'verificarCodigo':
            $token = $postBody['codigo'];
            $response = $_passwordRecovery->verificarCodigo($userName, $token);
            break;
        case 'resetContrasena':
            $token = $postBody['codigo'];
            $nuevaContrasena = $postBody['nuevaContrasena'];
            $response = $_passwordRecovery->resetContrasena($userName, $token, $nuevaContrasena);
            break;
        default:
            $response = $_respuestas->error_400();
            break;
    }

    header("Content-Type: application/json");
    if (isset($response["result"]["error_id"])) {
        http_response_code($response["result"]["error_id"]);
    } else {
        http_response_code(200);
    }
    echo json_encode($response);
} else {
    header("Content-Type: application/json");
    http_response_code(405);
    echo json_encode($_respuestas->error_405());
}
?>