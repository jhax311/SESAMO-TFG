<?php
//controlar la autenticacion por token
//importamos el documento de conexion de bbdd y de respuestas
require_once "conexion/conexion.php";
require_once "respuestas.class.php";
require_once "registrarVisitas.php";
//creamos la clase con todos los metodos de la clase conexion, los metodos publicos

class usuarios extends conexion
{
  private $tabla = "usuarios";
  private $token;
  private $userName;
  private $id_usuario;
  private $nombre;
  private $apellidos;
  private $email;
  private $id_rol;
  private $password;
  private $id_centro;

  public function __construct()
  {
      //lamamos ak contructor padre
      parent::__construct(); 
      $this->verificarOCrearTabla($this->tabla);
       
  //    $registro = new RegistroVisitas();
  //    $registro->registrarVisita($this->tabla);
  }
  //metodo para crear el login, que sea un documneto json con user y pasword
  public function login($json)
  {
    //instanciamos el de respuestas
    $_respuestas = new respuestas;
    //convertimos el json en array, con true sera asociativo
    $datos = json_decode($json, true);
    //si no existe lso campos usuario y password en el array seguimos, si no error
    if (!isset($datos["usuario"]) || !isset($datos["password"])) {
      //si no existen, enviaso error de datos incorrectos u incompletos
      return $_respuestas->error_400();
    } else {
      // en caso de que venags los valores solicitados, los guardamos
      $usuario = $datos["usuario"];
      $password = $datos["password"];
      //vamos a llamamr a la fucnion obtener datos de login
      $datosUsuario = $this->obtenerDatosUsuario($usuario);
      //  return $datos;
      //si hayd atos creamso el token de autenticacion
      if (isset($datosUsuario) && $datosUsuario != 0) {
        //si existe el usuario vamos a verificar la contraseña que sea corecta
        //    if (password_verify($password,$datos[0]["Password"])) {

        if (password_verify($password, $datosUsuario["password"])) {
          //si existe y contraseña correcta
          //insertamos el token

          $verificarToken = $this->insertarToken($datosUsuario["id_usuario"], $datosUsuario["id_rol"]);
          // return $verificarToken ;
          //comprobmos si hay valor y es distitnto de 0
          if ($verificarToken !== 0) {
            //si se guarda, crearemos la respuesta con el token
            $result = $_respuestas->response;
            $result["result"] = array(
              "token" => $verificarToken,
              "rol" => $datosUsuario["id_rol"]
            );
            //regsyramos la vista 
            $registro = new RegistroVisitas();
            $registro->registrarVisita($this->tabla);
            //devolvemos la respuesta
            return $result;
          } else {
            //si no se guardo el token, enviamos error
            return $_respuestas->error_500("Error interno: no se ha podido guardar el token");
          }
        } else {
          //  contraseña incorrecta erro, igual sin detalles
          return $_respuestas->error_200("Usuario o contraseña incorrecta");
        }
      } else {
        //no existe el user, retornamos un error 200, recibimso solicitud pero el user noe xiste
        //por seguridad no se envian detalles
        return $_respuestas->error_200("Usuario o contraseña incorrecta");
      }
    }
  }
  //para obtener los datos
  private function obtenerDatosUsuario($usuarioE)
  {
    // Comprobamos la existencia del usuario, creamos la query con una consulta preparada para evitar inyecciones SQL
    $query = "SELECT userName, id_usuario, id_rol, password FROM usuarios WHERE userName= ?";

    // Preparamos la consulta
    $stmt = $this->conexion->prepare($query);

    // Verificamos si la preparación fue exitosa
    if (!$stmt) {
      // Si la preparación falla retornamos 0
      return 0;
    }

    // Enlazamos el parámetro
    $bindResult = $stmt->bind_param("s", $usuarioE);

    // Verificamos si el enlace fue exitoso
    if (!$bindResult) {
      // Si el enlace falla, mostramos el mensaje de error y salimos del método
      return 0;
    }

    // Ejecutamos la consulta
    $executeResult = $stmt->execute();

    // Verificamos si la ejecución fue exitosa
    if (!$executeResult) {
      return 0;
    }

    // Obtenemos los resultados
    $result = $stmt->get_result();

    // Comprobamos si se encontraron resultados
    if ($result->num_rows > 0) {
      // si se ecuntran los retornamos, como array asociativo
      $datos = $result->fetch_assoc();
      return $datos;
    } else {
      // si no se encuntran  0
      return 0;
    }
  }

  //registro//////////////////////////////
  public function register($json)
  {
    $_respuestas = new respuestas;
    $datos = json_decode($json, true);
    //faltand atosoligatorios
    if (!isset($datos["usuario"]) || !isset($datos["password"]) || !isset($datos["nombre"]) || !isset($datos["apellidos"]) || !isset($datos["email"])) {
      return $_respuestas->error_400();
    } else {
      // Guardamos los datos del usuario
      $usuario = $datos["usuario"];
      $password = $datos["password"];
      $nombre = $datos["nombre"];
      $apellidos = $datos["apellidos"];
      $email = $datos["email"];
      $rol = isset($datos["rol"]) ? $datos["rol"] : 3;
      $centro = isset($datos["centro"]) ? $datos["centro"] : 10;
      //vamos a comprobar de que el usuario existe
      $datosUsuario = $this->obtenerDatosUsuario($usuario);

      if (isset($datosUsuario) && $datosUsuario != 0) {
        //si existe el usuario vamos a retornar repuesta 200
        return $_respuestas->error_200("El nombre de usuario no esta disponible");
      } else {
        //si no existe lo creamos
        // Hash de las ppasword
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $resultado = $this->insertarUsuario($usuario, $hashed_password, $nombre, $apellidos, $email, $rol, $centro);

        if ($resultado > 0) {
          //si se guarda, crearemos la respuesta 
          $result = $_respuestas->response;
          $result["result"] = array(
            "mensaje" => "Se ha insertado el usuario correctamente"
          );
          //devolvemos la respuesta
          return $result;
        } else {
          // Si ocurre un error 
          return $_respuestas->error_500("Error interno: no se pudo registrar el usuario");
        }
      }

      /////////////////////////////////////////////////////////////////

    }
  }

  private function insertarUsuario($usuario, $password, $nombre, $apellidos, $email, $rol, $centro)
  {
    // Preparamos la consulta
    $query = "INSERT INTO usuarios (userName, nombre, apellidos, email, Password,id_rol, Id_Centro) VALUES (?,?,?,?,?,?,?)";
    $stmt = $this->conexion->prepare($query);

    // Verificamos si la preparación fue exitosa
    if (!$stmt) {
      return 0;
    }

    // Enlazamos los parámetros
    $bindResult = $stmt->bind_param("sssssii", $usuario, $nombre, $apellidos, $email, $password, $rol, $centro);

    // Verificamos si el enlace fue exitoso
    if (!$bindResult) {
      return 0;
    }

    // Ejecutamos la consulta
    $executeResult = $stmt->execute();

    // Verificamos si la ejecución fue exitosa
    if (!$executeResult) {
      return 0;
    }

    // Devolvemos el ID del usuario insertado
    return $this->conexion->insert_id;
  }

  //ELIMINARRRRRRRRRRRRRRRRRR

  public function delete($json)
  {
    // Instanciamos las respuestas
    $_respuestas = new respuestas;
    // Transformamos el JSON en un array; los datos provienen del formulario
    $datos = json_decode($json, true);

    //token
    if (!isset($datos["token"])) {
      // si no existe el token devolvemos un error
      return $_respuestas->error_401();
    } else {
      $this->token = $datos["token"];
      //buscar token en caso de que noe ste envia rray avcio suamso eso para comprobar 
      $arrayToken = $this->buscarToken();


      if ($arrayToken) {
        //si exite el token verificamos que sea un usuario administrador
        if ($arrayToken["id_rol"] == 7) {
          //hacemos delete, si es profesor ua dministardor
          // ene ste caso debemos requerir el username
          if (!isset($datos["usuario"])) {
            // Si no existen los campos requeridos, enviamos un error 400
            return $_respuestas->error_400();
          } else {
            //este es obligatorio
            $this->userName = $datos["usuario"];

            // Llamamos a la función modificar
            $res = $this->eliminarUsuario();

            //si hay respueste devolvemos y si es distinto de 0 (0 es como un false)
            if (isset($res["result"])) {
              //si hay un resultado, es que hay un error, enseñamo ese error
              return $res;
            } else if (isset($res) && $res != 0) {
              //creamso rsuesta
              $respuesta = $_respuestas->response;
              //le metemos el id del apciente a la respuesta
              $respuesta["result"] = array(
                "mensaje" => "Usuario eliminado correctamente"
              );
              return $respuesta;
            } else {
              //si no enviamos error, interno
              return $_respuestas->error_500();
            }
          }
        } else {
          return $_respuestas->error_401();
        }


      } else {
        return $_respuestas->error_401("El token es invalido o caducado");
      }
    }


  }

  private function eliminarUsuario()
  {
    $_respuestas = new respuestas;

    //comporbamos que el usuario existe
    $datosUsuario = $this->obtenerDatosUsuario($this->userName);
    if (!$datosUsuario) {
        return $_respuestas->error_200("El usuario no existe.");
    }


    //borramos los tokens del usarios
    $res = $this->eliminarTokensUsuario();
    if ($res === 0) {
      return $_respuestas->error_500("Error al eliminar los tokens relacionados.");
    }

    // Creamos la consulta S
    $query = "DELETE FROM $this->tabla WHERE userName=?";

    // Preparamos la sentencia
    $stmt = $this->conexion->prepare($query);

    // Verificamos si la preparación fue exitosa
    if (!$stmt) {
      // Si la preparación falla, retornamos 0
      return 0;
    }

    // Vinculamos el parámetro PacienteId
    $resultado_bind_param = $stmt->bind_param("s", $this->userName);

    // Verificamos si la vinculación de parámetros fue exitosa
    if (!$resultado_bind_param) {
      // Si la vinculación de parámetros falla, retornamos 0
      return 0;
    }
    try {
      // Ejecutamos la consulta
      $resp = $stmt->execute();
      // Verificamos si la consulta fue exitosa
      if ($resp) {
        // Devolvemos el número de filas afectadas por eliminación
        if ($stmt->affected_rows > 0) {
          return $stmt->affected_rows;
        } else {
          // si se hizo bien la ejecución y no hay datos
          return $_respuestas->error_200("El usuario no existe.");
        }
      } else {
        // Si la eliminación falla, retornamos un error
        return $_respuestas->error_500("Error al ejecutar la consulta.");
      }
    } catch (Exception $e) {
      // Capturamos cualquier excepción y retornamos un error
      return $_respuestas->error_500("Excepción capturada: " . $e->getMessage());
    }
  }
  //paar borrar los tokns del suuario

  private function eliminarTokensUsuario()
  {
      // Verificamos si existen tokens para el usuario,
      $query = "SELECT COUNT(*) AS count FROM usuarios_token WHERE UsuarioId=(SELECT id_usuario FROM usuarios WHERE userName=?)";
      $stmt = $this->conexion->prepare($query);
      if (!$stmt) {
          return 0;
      }
      $resultado_bind_param = $stmt->bind_param("s", $this->userName);
      if (!$resultado_bind_param) {
          return 0;
      }
      try {
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
  
          if ($row['count'] > 0) {
              // si existen los borramos
              $queryDelete = "DELETE FROM usuarios_token WHERE UsuarioId=(SELECT id_usuario FROM usuarios WHERE userName=?)";
              $stmtDelete = $this->conexion->prepare($queryDelete);
  
              if (!$stmtDelete) {
                  return 0;
              }
  
              $resultado_bind_param_delete = $stmtDelete->bind_param("s", $this->userName);
              if (!$resultado_bind_param_delete) {
                  return 0;
              }
  
              $resp = $stmtDelete->execute();
              if ($resp) {
                  return $stmtDelete->affected_rows;
              } else {
                  return 0;
              }
          } else {
              // si no exiten 0
              return 1;
          }
      } catch (Exception $e) {
          return 0;
      }
  }

  //FUNCIONES DE TOKEN

  private function buscarToken()
  {
    // Creamos la consulta
    $query = "SELECT TokenId, UsuarioId, Estado, id_rol FROM usuarios_token WHERE Token=? AND Estado=1";
    // Preparamos la sentencia
    $stmt = $this->conexion->prepare($query);

    // Verificamos si la preparación fue exitosa
    if (!$stmt) {
      // Si la preparación falla, retornamos array vacio para que no gaurde datos

      return [];

    }

    // Vinculamos el parámetro Token
    $resultado_bind_param = $stmt->bind_param("s", $this->token);

    // Verificamos si la vinculación de parámetros fue exitosa
    if (!$resultado_bind_param) {
      // Si la vinculación de parámetros falla, retornamos 0
      return [];
    }

    // Ejecutamos la consulta
    $resp = $stmt->execute();

    // Verificamos si la consulta fue exitosa
    if ($resp) {
      // Obtenemos el resultado de la consulta
      $result = $stmt->get_result();

      // Obtenemos la fila resultante como un array asociativo
      $row = $result->fetch_assoc();

      // Devolvemos el resultado de la consulta
      return $row ? $row : []; // Si hay una fila resultante, la devolvemos; de lo contrario, devolvemos un array vacío
    } else {
      // Si la consulta falla, retornamos 0
      return [];
    }
  }

  //insertar token
  private function insertarToken($usuarioId, $idRol)
  {
    // Variable de comprobación
    $val = true;

    // primero vamos a verificar si existe un tken ya para el usuario
    $query_verificar = "SELECT Token FROM usuarios_token WHERE UsuarioId = ?";
    //preparamos
    $stmt_verificar = $this->conexion->prepare($query_verificar);
    //bind
    $stmt_verificar->bind_param("i", $usuarioId);
    //ejecutamos
    $stmt_verificar->execute();
    //guardamos los resultados
    $result_verificar = $stmt_verificar->get_result();
    //si el numero de filas es 0 es qe no esta, si es mas esta
    if ($result_verificar->num_rows > 0) {
      // Si ya existe un token, devolvemos ese token en lugar de crear uno nuevo
      $row = $result_verificar->fetch_assoc();
      return $row['Token'];
    }

    // Si no existe un token, creamos uno nuevo
    $token = bin2hex(openssl_random_pseudo_bytes(16, $val));


    // Creamos la fecha con la zona horaria
    date_default_timezone_set('Europe/Madrid');
    $fecha = date("Y-m-d H:i"); // Le damos un formato como las fechas de MySQL

    // Estado 1 si está activo 0 si no
    $estado = 1;

    // Preparamos la consulta para insertar el nuevo token
    $query_insertar = "INSERT INTO usuarios_token (UsuarioId, Token, Estado, Fecha, id_rol) VALUES (?, ?, ?, ?,?)";
    $stmt_insertar = $this->conexion->prepare($query_insertar);

    // Verificamos si la preparación fue exitosa
    if (!$stmt_insertar) {
      return 0;
    }

    // Enlazamos los parámetros
    $bindResult = $stmt_insertar->bind_param("isssi", $usuarioId, $token, $estado, $fecha, $idRol);

    // Verificamos si el enlace fue exitoso
    if (!$bindResult) {
      return 0;
    }

    // Ejecutamos la consulta
    $executeResult = $stmt_insertar->execute();

    // Verificamos si la ejecución fue exitosa
    if (!$executeResult) {
      return 0;

    }


    // Verificamos si se ha insertado correctamente
    if ($this->conexion->insert_id > 0) {
      //si se inserto mandamos el token
      return $token;
    } else {
      return 0;
    }
  }

  public function verificar($json)
  {
    // Instanciamos las respuestas
    $_respuestas = new respuestas;
    // Transformamos el JSON en un array; los datos provienen del formulario
    $datos = json_decode($json, true);

    if (!isset($datos["token"])) {
      // si no existe el token devolvemos un error
      return $_respuestas->error_401();
    } else {
      $this->token = $datos["token"];
      //buscar token en caso de que no ste envia array avcio suamso eso para comprobar 
      $arrayToken = $this->buscarToken();

      if ($arrayToken) {
        $respuesta = $_respuestas->response;
        //le metemos el id del apciente a la respuesta
        $respuesta["result"] = array(
          "token" => $this->token,
          "rol" => $arrayToken["id_rol"]
        );
        return $respuesta;

      } else {
        return $_respuestas->error_401("El token es inválido");
      }
    }
  }

  public function listarUsuarios()
  {
    $sql = "SELECT u.id_usuario, u.userName, u.nombre, u.apellidos, u.email, u.id_rol, u.id_centro, c.nombre AS nombre_centro
            FROM $this->tabla u
            JOIN centros c ON u.id_centro = c.id_centro";

    $stmt = $this->conexion->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $usuarios = array();
      while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
      }
      return $usuarios;
    } else {
      return array();
    }
  }

  //putt
  public function put($json) //reciben un json como parametro
  {
    // Instanciamos las respuestas
    $_respuestas = new respuestas;
    // Transformamos el JSON en un array; los datos provienen del formulario
    $datos = json_decode($json, true);
    //preguntamos si no existe l token
    if (!isset($datos["token"])) {
      // si no existe el token devolvemos un error
      return $_respuestas->error_401();
    } else {
      $this->token = $datos["token"];
      //buscar token en caso de que no ste envia array avcio suamso eso para comprobar 
      $arrayToken = $this->buscarToken();
      if ($arrayToken) {
        //verificamos el token 
        if ($arrayToken["id_rol"] != 7) {
          return $_respuestas->error_200("Permisos insuficientes");
        }


        //si existe el token y es valido, hacemos la peticion post
        // Validamos los datos capturados con los campos requeridos para hacer un insert
        if (!isset($datos["id_usuario"]) || !isset($datos["userName"]) || !isset($datos["nombre"]) || !isset($datos["apellidos"]) || !isset($datos["email"]) || !isset($datos["id_rol"]) || !isset($datos["id_centro"])) {
          // Si no existen los campos requeridos, enviamos un error 400
          return $_respuestas->error_400();
        } else {
          //comprobamos los dtaos que vengan por json para ser actualizados,ademas comprobamso los que no vengan vacios para hacer la query
          // Creamos un array para almacenar los pares campo = valor
          $camposValores = array();
          // Creamos un array para almacenar los tipos de datos de los parámetros, sera una statemen
          $tiposDatos = "";
          //para los parametros
          $params = array();
          //verificmaos que el username nuevo es el mismo, sy si no que esteno eu¡ixtsa ya
          $userId = $datos["id_usuario"];
          $nuevoUsername = $datos["userName"];
          $query = "SELECT userName FROM $this->tabla WHERE id_usuario = ?";
          $stmt = $this->conexion->prepare($query);
          $stmt->bind_param("i", $userId);
          $stmt->execute();
          $result = $stmt->get_result();
          $row = $result->fetch_assoc();
          $usernameActual = $row['userName'];

          if ($usernameActual != $nuevoUsername) {
            // sinno es el mismo ve si existe
            if ($this->comprobarUsernameExistente($nuevoUsername)) {
              return $_respuestas->error_200("El nombre de usuario ya está en uso");
            }
          }



          foreach ($datos as $clave => $valor) {
            //verifica si la clave existe como propiedad de la clase, es decir si existe el atributo
            if (property_exists($this, $clave)) {
              $this->{$clave} = $valor;
              //vamos a comprobar si hay datos especiales que sena de tipo i y que los datos que vengan no sean vacios
              //comprobamos si no es valor vacio
              if ($valor !== "" && $valor !== null && $valor !== 0 && $clave != "token" && $clave != "id_usuario") {
                //comprobamos los tipos especiales
                //nhc sera entero, sexo debe ser una letra, fallecido igual
                //si esta el nhc y es numerico
                if ($clave == "id_rol") {
                  if (is_numeric($valor) && strlen($valor) < 11) {
                    $camposValores[] = "id_rol=?"; //añadimnos a la query segun si existe a dato o no
                    $tiposDatos .= "i";   //de la msiam manera añadimos el tipo de datos
                    $params[] = $this->{$clave}; //y añadimos el parametro que pasaremos
                  } else {
                    // si no es numerico error
                    return $_respuestas->error_400();
                  }
                } elseif ($clave == "id_centro") {
                  if (is_numeric($valor) && strlen($valor) < 11) {
                    $camposValores[] = "id_centro=?"; //añadimnos a la query segun si existe a dato o no
                    $tiposDatos .= "i";   //de la msiam manera añadimos el tipo de datos
                    $params[] = $this->{$clave}; //y añadimos el parametro que pasaremos
                  } else {
                    //si e snumerico o son mas de una letra
                    return $_respuestas->error_400();
                  }
                } else {
                  //si no es ninguno de estos seran estrings
                  $camposValores[] = "$clave=?"; //añadimnos a la query segun si existe a dato o no
                  $tiposDatos .= "s";   //de la msiam manera añadimos el tipo de datos
                  $params[] = $this->$clave; //y añadimos el parametro que pasaremos
                }
              }
            }
          }
          // Llamamos a la función para insertar el paciente y almacenamos el resultado
          //   $res = $this->comprobarPaciente($this->nhc,$this->nif,$this->numeroSS);
          $res = $this->modificarUsuario($camposValores, $tiposDatos, $params);
          //si hay respueste devolvemos  (0 es como un false)
          if (isset($res["result"])) {
            //si hay un resultado, es que hay un error, enseñamo ese error
            return $res;
          } else if ($res) {
            //por el contrario si ya sido exitoso
            //creamso rsuesta
            $respuesta = $_respuestas->response;
            //le metemos el id del apciente a la respuesta
            $respuesta["result"] = array(
              "msg" => "Se ha modificado el usuario."
            );

            return $respuesta;
          } else {
            //si no enviamos error, interno
            return $_respuestas->error_200();
          }
        }
      } else {
        return $_respuestas->error_401("El token es invalido o caducado");
      }
    }
  }



  private function modificarUsuario($camposValores, $tiposDatos, $params)
  {
    $_respuestas = new respuestas;
    //comprobamso quee luser existe
    $res = $this->comprobarUsuario($this->id_usuario);
    if (!$res) {
      //si hay un resultado, es que hay un error, enseñamo ese error
      return $_respuestas->error_200("El usuario no existe");
    }
    //PARA AHSH DE LA CONTRASEÑA EN CASO DE STAR
    // Hash de la contraseña si está presente en los datos
    foreach ($camposValores as $key => $value) {
      if (strpos($value, 'password=') === 0) {
        $passwordIndex = $key;
        $passwordValue = $params[$passwordIndex];
        $params[$passwordIndex] = password_hash($passwordValue, PASSWORD_DEFAULT);
      }
    }
    //vamos a crear una funciondinamica, para que s evayan crando la queri segunlos datos pasados.
    // Creamos la parte inicial de la consulta
    $query = "UPDATE $this->tabla SET ";

    // Combinamos los pares campo = valor en la consulta
    $query .= implode(", ", $camposValores);

    // Agregamos la condición WHERE nhc
    $query .= " WHERE id_usuario=?";
    $params[] = $this->id_usuario;
    // Agregamos el tipo de dato para el campo PacienteId
    $tiposDatos .= "i";
    // Preparamos la sentencia
    $stmt = $this->conexion->prepare($query);
    //comprobamos si en caso cambiamos de cama este libre

    //comprobamos si se prepare bien
    if (!$stmt) {
      return 0;
    }
    //como los numero son inciertos, usamos .. para que cada parametro de $param, pase como parametro al la fucion param y se vaya asociando
    //con su campo correspondiente
    $resultado_bind_param = $stmt->bind_param($tiposDatos, ...$params);
    //comprobamos el bindi
    if (!$resultado_bind_param) {
      return 0;
    }


    try {
      $resp = $stmt->execute();
    } catch (Exception $e) {
      return $_respuestas->error_200();
    }
    // Verificamos si la actualización fue exitosa
    if ($resp) {
      // Devolvemos el número de filas afectadas por la actualización
      if ($stmt->affected_rows > 0) {
        // Ejecutamos la consulta
        return $stmt->affected_rows;
      } else {
        return $_respuestas->error_200("No hay datos que modificar");
      }
    } else {
      // Si no se actualiza ninguna fila, devolvemos 0
      return 0;//
    }
  }


  private function comprobarUsuario($id_usuario)
  {
    // Comprobamos si el usuario existe
    $query = "SELECT COUNT(*) AS count FROM $this->tabla WHERE id_usuario = ?";
    // Preparamos la consulta
    $stmt = $this->conexion->prepare($query);
    if (!$stmt) {
      return false;
    }
    // Vinculamos los parámetros
    $stmt->bind_param("i", $id_usuario);
    // Ejecutamos la consulta
    $stmt->execute();
    // Obtenemos el resultado de la consulta
    $result = $stmt->get_result();
    // Obtenemos el número de filas encontradas
    $row = $result->fetch_assoc();
    $count = $row['count'];
    // Enviamos un false o true
    return $count > 0;
  }

  private function comprobarUsernameExistente($username)
  {
    $query = "SELECT COUNT(*) AS count FROM $this->tabla WHERE userName = ?";
    $stmt = $this->conexion->prepare($query);
    if (!$stmt) {
      return false;
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $count = $row['count'];
    return $count > 0;
  }


}
?>