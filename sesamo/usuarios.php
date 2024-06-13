<?php


header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods:POST,DELETE,PUT");
header("Access-Control-Allow-Headers: Content-Type");

//controlar la atenticacion
//importamos las clases de autenticacion y la de respuestas
require_once "clases/usuarios.class.php";
require_once "clases/respuestas.class.php";
//instancimos las clases
$_auth = new usuarios;
$_respuestas = new respuestas;
include 'config.php';
//OBTENERLA RUTA
$ruta = $_SERVER['REQUEST_URI'];
$partes = explode('/', $ruta);
$indice_sesamo = array_search($nombre_APP, $partes);
if ($indice_sesamo) {
    $partes = array_slice($partes, $indice_sesamo - 1);
}

//vamos a comprobar la autentificacion por user y pass
//los autentificaciones sera por metodo post
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Divide la URL en partes para determinar la acción específica
    $partes = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

    // Verifica si se debe listar todos los usuarios
    switch ($partes[3] ?? '') { // Utiliza el índice adecuado según tu estructura de URL
        case '':
            // Llama al método listarUsuarios de la clase usuarios
            $listaUsuarios = $_auth->listarUsuarios();

            // Configura el encabezado de la respuesta para JSON
            header("Content-Type: application/json");
            http_response_code(200);

            // Envía la lista de usuarios en formato JSON
            echo json_encode($listaUsuarios);
            break;
        default:
            header("Content-Type: application/json");
            echo json_encode($_respuestas->error_400());
            break;
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //OBTENERLA RUTA


    //SWITCH CON LOS CASOS
    switch ($partes[3]) {
        case 'login':
            //obligamos a que se haga por este metodo
            //recibimos datos
            $postBody = file_get_contents("php://input");
            //enviamos datos al controlador
            $datosArray = $_auth->login($postBody);

            //devolvemos l respuesta
            //definimos el tipo, los headers
            header("Content-Type: application/json");
            //comprobamos si hay errores, verificamos si existe el error id que los configuramsnostros
            //si hay error hacemos
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
            break;
        case 'registro':

            //obligamos a que se haga por este metodo
            //recibimos datos
            $postBody = file_get_contents("php://input");
            //enviamos datos al controlador
            $datosArray = $_auth->register($postBody);

            //devolvemos l respuesta
            //definimos el tipo, los headers
            header("Content-Type: application/json");
            //comprobamos si hay errores, verificamos si existe el error id que los configuramsnostros
            //si hay error hacemos
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

            break;
        case 'verificar':
            //obligamos a que se haga por este metodo
            //recibimos datos
            $postBody = file_get_contents("php://input");

            //enviamos datos al controlador
            $datosArray = $_auth->verificar($postBody);
            //devolvemos l respuesta
            //definimos el tipo, los headers
            header("Content-Type: application/json");
            //comprobamos si hay errores, verificamos si existe el error id que los configuramsnostros
            //si hay error hacemos
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
    $datosArray = $_auth->put($postBody);

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

    $datosArray = $_auth->delete($postBody);
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
    //en caso de que no se haga por este mtodo mandamos la respuesta correspondiente
    header("Content-Type: application/json");
    $datosArray = $_respuestas->error_405();
    echo $datosArray;

}




?>