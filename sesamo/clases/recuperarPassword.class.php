<?php
//phpmailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include ('/etc/sesamo/config.php');
require_once "conexion/conexion.php";
require_once "respuestas.class.php";
//conf de php
require '/etc/sesamo/PHPMailer-master/src/PHPMailer.php';
require '/etc/sesamo/PHPMailer-master/src/SMTP.php';
require '/etc/sesamo/PHPMailer-master/src/Exception.php';

class recuperarPassword extends conexion
{
    private $password;
    private  $tabla="recuperarContrasena";

    public function __construct()
    {
        parent::__construct();
        $config = include ('/etc/sesamo/config.php');
        $this->password = $config['passwordG'];
        
        $this->verificarOCrearTabla($this->tabla);
    }
 
    

    public function recuperarContrasena($userName)
    {
        $_respuestas = new respuestas;
        //pedimos los datos del usuarios
        $datos = $this->datosUsuario($userName);

        if ($datos) {
            //geenramos el codigo
            $token = $this->tokenId($datos['id_usuario']);

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'sesamosytes@gmail.com';
                $mail->Password = $this->password;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->CharSet = 'UTF-8';

                $mail->setFrom('sesamosytes@gmail.com', 'SESAMO');
                $mail->addAddress($datos['email'], $datos["nombre"] . " " . $datos["apellidos"]);

                $mail->isHTML(true);
                $mail->Subject = 'Recuperación de contraseña';
                $mail->Body = $this->generarCuerpoHTML($token);

                $mail->send();

                $respuesta = $_respuestas->response;
                $respuesta["result"] = array(
                    "msg" => "Su codigo de recuperación ha sido enviado."
                );
                return $respuesta;
            } catch (Exception $e) {
                return $_respuestas->error_200($mail->ErrorInfo);
            }
        } else {
            return $_respuestas->error_200("Usuario no encontrado!");
        }
    }

    private function datosUsuario($userName)
    {
        $_respuestas = new respuestas;
        try {
            $query = "SELECT * FROM usuarios WHERE userName = ?";
            $stmt = $this->conexion->prepare($query);

            $stmt->bind_param('s', $userName);
            $stmt->execute();
            $result = $stmt->get_result();
            $datos = $result->fetch_assoc();
            $stmt->close();
            return $datos;
        } catch (Exception $e) {

            return $_respuestas->error_200("Error en recuperar datos del usuario: " . $e->getMessage());

        }
    }


    //verificar codigo

    public function verificarCodigo($userName, $token)
    {
        $_respuestas = new respuestas;
        // Sacamos datos del usuario
        $datos = $this->datosUsuario($userName);

        if ($datos) {
            // Verificamos si hay un token válido para el usuario
            $tokenGuardado = $this->tokenId($datos['id_usuario'], false);

            if ($tokenGuardado && $tokenGuardado === $token) {
                $respuesta = $_respuestas->response;
                $respuesta["result"] = array(
                    "msg" => "Código verificado correctamente."
                );
                //aqui llamariamos para broorarlo

                return $respuesta;
            } else {
                return $_respuestas->error_200("El código es inválido o ha caducado.");
            }
        } else {
            return $_respuestas->error_200("Usuario no encontrado.");
        }
    }
    //reset de la contraseña
    public function resetContrasena($userName, $token, $newPassword)
    {
        $_respuestas = new respuestas;
        $datos = $this->datosUsuario($userName);

        if ($datos) {
            $tokenGuardado = $this->tokenId($datos['id_usuario']);
            //si token correcto
            if ($tokenGuardado === $token) {
                //hash de la nueva contraseña
                $nuevaPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                //actualizamos
                $this->actualizarContrasena($datos['id_usuario'], $nuevaPassword);
                //y eliminamos
                $this->eliminarToken($datos['id_usuario']);

                $respuesta = $_respuestas->response;
                $respuesta["result"] = array("msg" => "La contraseña ha sido restablecida exitosamente.");
                return $respuesta;
            } else {
                return $_respuestas->error_200("El código es inválido o ha caducado.");
            }
        } else {
            return $_respuestas->error_200("Usuario no encontrado.");
        }
    }
    private function generarToken()
    {
        $randomBytes = random_bytes(8);
        $base64Token = base64_encode($randomBytes);
        $base64Token = str_replace(['+', '/', '='], '', $base64Token);
        return substr($base64Token, 0, 8);
    }
    //guardrToken
    private function guardarToken($userId, $token)
    {
        $query = "INSERT INTO $this->tabla (id_usuario, token, expiracion) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 30 MINUTE))";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param('is', $userId, $token);
        $stmt->execute();
        $stmt->close();
    }

    //conseguir token
    private function tokenId($userId, $flag = true)
    {
        $_respuestas = new respuestas;
        try {
            // verificar si hay token
            $stmt = $this->conexion->prepare("SELECT token, expiracion FROM recuperarContrasena WHERE id_usuario = ?");
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            //si hay resultados
            if ($result->num_rows > 0) {
                // Obtenemos la fila de resultados
                $fila = $result->fetch_assoc();
                // Cerramos la consulta preparada
                $stmt->close();

                // Verificamos si el token es válido
                if (strtotime($fila['expiracion']) > time()) {
                    // Retornamos el token existente si es válido
                    return $fila['token'];
                } else {
                    // Si el token ha expirado, eliminamos el token existente
                    $this->eliminarToken($userId);
                }
            }
            if ($flag) {

                // Si no hay token válido o si ha expirado, generamos uno nuevo
                $nuevoToken = $this->generarToken();
                // Guardamos el nuevo token
                $this->guardarToken($userId, $nuevoToken);
                // Retornamos el nuevo token
                return $nuevoToken;
            } else {
                return false;
            }

        } catch (Exception $e) {
            return $_respuestas->error_200("Error en tokenId: " . $e->getMessage());
        }
    }
    private function eliminarToken($userId)
    {
        $_respuestas = new respuestas;
        try {
            // eliminamos token en caso de no ser valido
            $stmt = $this->conexion->prepare("DELETE FROM recuperarContrasena WHERE id_usuario = ?");
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            // Manejo de excepciones
            return $_respuestas->error_200("Error en eliminarToken: " . $e->getMessage());
        }
    }
    //actualizar pas
    private function actualizarContrasena($userId, $nuevaPassword)
    {
        $_respuestas = new respuestas;
        try {
            // Preparamos la consulta para actualizar la contraseña
            $stmt = $this->conexion->prepare("UPDATE usuarios SET password = ? WHERE id_usuario = ?");
            $stmt->bind_param('si', $nuevaPassword, $userId);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            return $_respuestas->error_200("Error en actualizarContrasena: " . $e->getMessage());
        }
    }



    private function generarCuerpoHTML($token)
    {
        $imagenUrl = 'http:sesamo.sytes.net/sesamo/assets/img/sesamoWhite.png';
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
            body {
                font-family: Arial, sans-serif;
            }
            .header {
                background: rgba(27, 60, 107, 1);
                color: white;
                padding: 20px;
                text-align: center;
                border-top-left-radius: 10px;
                border-top-right-radius: 10px;
                position: relative;
            }
            .header img {
                position: absolute;
                left: 20px;
                top: 20px;
                width: 100px;
                height: 100px;
            }
            .header h1, .header h2 {
                margin: 0;
            }
            .content {
                padding: 20px;
                text-align: center;
            }
            .card {
                background: rgba(27, 60, 107, 1);
                border-radius: 10px;
                padding: 20px;
                color: white;
                text-align: center;
                max-width: 500px;
                margin: 20px auto;
            }
            .card-title {
                font-size: 20px;
                margin-bottom: 10px;
                color: white;
            }
            .card-body {
                font-size: 18px;
            }
            .code {
                background-color: white;
                color:  rgba(27, 60, 107, 1);
                padding: 10px;
                border-radius: 5px;
                display: inline-block;
                font-size: 20px;
                margin-top: 10px;
            }
            .footer {
                background: rgba(27, 60, 107, 1);
                color: white;
                padding: 10px;
                text-align: center;
                border-bottom-left-radius: 10px;
                border-bottom-right-radius: 10px;
            }
            p{
                font-size: 18px;
                color:  rgba(27, 60, 107, 1);
            }

            </style>
        </head>
        <body>
            <div class='header'>
            <img src='$imagenUrl' alt='Imagen descriptiva'>
                <h2>RECUPERAR CONTRASEÑA</h2>
            </div>
            <div class='content'>
                <p>Hola,</p>
                <p>Te enviamos este correo en respuesta a tu petición de restaurar la contraseña.</p>
                <div class='card'>
                    <div class='card-title'>Usa este código para recuperar tu contraseña</div>
                    <div class='card-body'>
                        <div class='code'>$token</div>
                    </div>
                </div>
            </div>
            <div class='footer'>
                SESAMO
            </div>
        </body>
        </html>
        ";
    }



}
?>