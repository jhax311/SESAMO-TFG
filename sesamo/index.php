<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SESAMO - Plataforma de Aprendizaje</title>
    <link rel="shortcut icon" href="assets\img\favicon.png" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php
    //importamos el fichero de conexion
    //  require_once "clases/conexion/conexion.php";
    //creamos la conexion
    //$conexion = new conexion();
    // print_r($conexion->obtenerDatos("select * from grados"))
    ?>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h1 class="text-center">SESAMO</h1>
                <h2 class="text-center">Sistema Educativo de Administración Sanitaria</h2>
            </div>
        </div>
    </div>
    <!-- acordeon padre-->
    <div class="container mt-5">
        <div class="accordion" id="accordionClasses">
            <!-- Clase Auth -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAuth">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseAuth" aria-expanded="true" aria-controls="collapseAuth">
                        Clase Usuarios
                    </button>
                </h2>
                <div id="collapseAuth" class="accordion-collapse collapse " aria-labelledby="headingAuth"
                    data-bs-parent="#accordionClasses">
                    <div class="accordion-body">
                        <p>La clase <strong>auth</strong> controla la autenticación por token de SESAMO.</p>
                        <div class="accordion" id="accordionMethodsAuth">
                            <!-- Método login -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingLogin">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseLogin" aria-expanded="true"
                                        aria-controls="collapseLogin">
                                        Método login($json)
                                    </button>
                                </h2>
                                <div id="collapseLogin" class="accordion-collapse collapse"
                                    aria-labelledby="headingLogin" data-bs-parent="#accordionMethodsAuth">
                                    <div class="accordion-body">
                                        <p>Este método se encarga de realizar el inicio de sesión de un usuario. Recibe
                                            un JSON con los datos de inicio de sesión (usuario y contraseña) y devuelve
                                            un token de autenticación si las credenciales son válidas.
                                            Este token se usara para realizar todas las peticiones CRUD.
                                        </p>
                                        <p>
                                            Se envia : <br>
                                            {<br>
                                            "usuario" : "NombreDeUsuario",<br>
                                            "password" : "Contraseña"<br>
                                            }
                                        </p>
                                        <p>
                                            Se recibe : <br>
                                            {<br>
                                            "status": "ok",<br>
                                            "result": {<br>
                                            "token": "SEMILLATOKEN25485s148d441d5s445"<br>
                                            } <br>
                                            } <br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingRegistro">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseRegistro" aria-expanded="true"
                                        aria-controls="collapseRegistro">
                                        Método Registro($json)
                                    </button>
                                </h2>
                                <div id="collapseRegistro" class="accordion-collapse collapse"
                                    aria-labelledby="headingRegistro" data-bs-parent="#accordionMethodsAuth">
                                    <div class="accordion-body">
                                        <p>Este método se encarga de realizar el registro de los usuarios.
                                        </p>
                                        <p>
                                            Se envia : <br>
                                            {<br>
                                            "usuario" : "NombreDeUsuario",<br>
                                            "password" : "Contraseña"<br>
                                            }
                                        </p>
                                        <p>
                                            Se recibe : <br>
                                            {<br>
                                            "status": "ok",<br>
                                            "result": {<br>
                                            "token": "SEMILLATOKEN25485s148d441d5s445"<br>
                                            } <br>
                                            } <br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Método obtenerDatosUsuario -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingObtenerDatosUsuario">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseObtenerDatosUsuario" aria-expanded="true"
                                        aria-controls="collapseObtenerDatosUsuario">
                                        Método obtenerDatosUsuario($usuarioE)
                                    </button>
                                </h2>
                                <div id="collapseObtenerDatosUsuario" class="accordion-collapse collapse "
                                    aria-labelledby="headingObtenerDatosUsuario" data-bs-parent="#accordionMethodsAuth">
                                    <div class="accordion-body">
                                        <p>Este método busca en la base de datos los datos del usuario correspondientes
                                            al nombre de usuario proporcionado. Devuelve un array asociativo con los
                                            datos del usuario si se encuentra.</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Método insertarToken -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingInsertarToken">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseInsertarToken" aria-expanded="true"
                                        aria-controls="collapseInsertarToken">
                                        Método insertarToken($usuarioId)
                                    </button>
                                </h2>
                                <div id="collapseInsertarToken" class="accordion-collapse collapse "
                                    aria-labelledby="headingInsertarToken" data-bs-parent="#accordionMethodsAuth">
                                    <div class="accordion-body">
                                        <p>Este método comprueba la existencia en la base de datos el token para el id
                                            del usuario indicado, si existe lo retorna en caso de que no exista se crea
                                            y se inserta en la base de datos.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Clase Conexion -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingConexion">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseConexion" aria-expanded="false" aria-controls="collapseConexion">
                        Clase Conexion
                    </button>
                </h2>
                <div id="collapseConexion" class="accordion-collapse collapse" aria-labelledby="headingConexion"
                    data-bs-parent="#accordionClasses">
                    <div class="accordion-body">
                        <p>La clase <strong>Conexión</strong> se encarga de la conexión con la base de datos, tiene
                            métodos para realizar consultas (creo que no se usaran al final)</p>
                    </div>
                </div>
            </div>
            <!-- Clase Respuestas -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingRespuestas">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseRespuestas" aria-expanded="false" aria-controls="collapseRespuestas">
                        Clase Respuestas
                    </button>
                </h2>
                <div id="collapseRespuestas" class="accordion-collapse collapse" aria-labelledby="headingRespuestas"
                    data-bs-parent="#accordionClasses">
                    <div class="accordion-body">
                        <p>La clase <strong>Respuestas</strong> se encarga de devolver un codigo de error con su
                            corresponiente mensaje, se usara principalmente para el manejo de errores</p>

                        <div class="accordion" id="accordionMethodsRespuestas">
                            <!-- erro_200-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingError200">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseError200" aria-expanded="true"
                                        aria-controls="collapseError200">
                                        Error 200 ($mensaje)
                                    </button>
                                </h2>
                                <div id="collapseError200" class="accordion-collapse collapse"
                                    aria-labelledby="headingError200" data-bs-parent="#accordionMethodsRespuestas">
                                    <div class="accordion-body">
                                        <p>controla las peticiones correctas, pero con los valores equivocados, se puede
                                            pasar por parámetro el mensaje deseado.</p>
                                        <p>
                                            {<br>
                                            "status": "error",<br>
                                            "result": {<br>
                                            "error_id": "200",<br>
                                            "error_msg": "Usuario o contraseña incorrecta"<br>
                                            }<br>
                                            }
                                        </p>

                                    </div>
                                </div>
                            </div>
                            <!-- erro_400-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingError400">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseError400" aria-expanded="true"
                                        aria-controls="collapseError400">
                                        Error 400
                                    </button>
                                </h2>
                                <div id="collapseError400" class="accordion-collapse collapse"
                                    aria-labelledby="headingError400" data-bs-parent="#accordionMethodsRespuestas">
                                    <div class="accordion-body">
                                        <p>controla que los datos se envien de manera correcta</p>
                                        <p>
                                            {<br>
                                            "status": "error",<br>
                                            "result": {<br>
                                            "error_id": "400",<br>
                                            "error_msg": "Datos enviados incorrectos u incompletos"<br>
                                            }<br>
                                            }
                                        </p>

                                    </div>
                                </div>
                            </div>
                            <!-- erro_401-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingError401">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseError401" aria-expanded="true"
                                        aria-controls="collapseError401">
                                        Error 401 ($mensaje)
                                    </button>
                                </h2>
                                <div id="collapseError401" class="accordion-collapse collapse"
                                    aria-labelledby="headingError401" data-bs-parent="#accordionMethodsRespuestas">
                                    <div class="accordion-body">
                                        <p>controla el acceso, segun los privilegios del usuario. Se puede pasar el
                                            mensaje deseado por parametro</p>
                                        <p>
                                            {<br>
                                            "status": "error",<br>
                                            "result": {<br>
                                            "error_id": "401",<br>
                                            "error_msg": "No autorizado"<br>
                                            }<br>
                                            }
                                        </p>

                                    </div>
                                </div>
                            </div>
                            <!-- erro_404-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingError404">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseError404" aria-expanded="true"
                                        aria-controls="collapseError404">
                                        Error 404
                                    </button>
                                </h2>
                                <div id="collapseError404" class="accordion-collapse collapse"
                                    aria-labelledby="headingError404" data-bs-parent="#accordionMethodsRespuestas">
                                    <div class="accordion-body">
                                        <ev>Se devuelve en caso de que los datos solicitados no se encuentren, se puede
                                            pasar por parametro el mensaje deseado</p>
                                            <p>
                                                { <br> "status":"error",<br>"result":
                                                {<br>"error_id":"404",<br>"error_msg":"No se encuentra la
                                                búsqueda"<br>}<br>}
                                            </p>

                                    </div>
                                </div>
                            </div>
                            <!-- erro_405-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingError405">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseError405" aria-expanded="true"
                                        aria-controls="collapseError405">
                                        Error 405
                                    </button>
                                </h2>
                                <div id="collapseError405" class="accordion-collapse collapse"
                                    aria-labelledby="headingError405" data-bs-parent="#accordionMethodsRespuestas">
                                    <div class="accordion-body">
                                        <p>Controla que se use el metodo correspondiente para cada acción.</p>
                                        <p>
                                            { <br> "status":"error",<br>"result":
                                            {<br>"error_id":"405",<br>"error_msg":"Metodo no permitido"<br>}<br>}
                                        </p>

                                    </div>
                                </div>
                            </div>
                            <!-- erro_500-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingError500">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseError500" aria-expanded="true"
                                        aria-controls="collapseError500">
                                        Error 500 ($mensaje)
                                    </button>
                                </h2>
                                <div id="collapseError500" class="accordion-collapse collapse"
                                    aria-labelledby="headingError500" data-bs-parent="#accordionMethodsRespuestas">
                                    <div class="accordion-body">
                                        <p>controla errores internos, se puede pasar por parametro el mensaje deseado
                                        </p>
                                        <p>
                                            {<br>
                                            "status": "error",<br>
                                            "result": {<br>
                                            "error_id": "500",<br>
                                            "error_msg": "Error interno en el servidor"<br>
                                            }<br>
                                            }
                                        </p>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
            <!-- Clase pacientes -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingPacientes">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapsePacientes" aria-expanded="false" aria-controls="collapsePacientes">
                        Clase Pacientes
                    </button>
                </h2>
                <div id="collapsePacientes" class="accordion-collapse collapse" aria-labelledby="headingPacientes"
                    data-bs-parent="#accordionClasses">
                    <div class="accordion-body">
                        <p>La clase <strong>Pacientes</strong> se encarga de manejar todos los metodos CRUD relacionado
                            con la tabla pacientes</p>
                        <div class="accordion" id="accordionMethodsPacientes">
                            <!-- listar pacientes-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingListar">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseListar" aria-expanded="true"
                                        aria-controls="collapseListar">
                                        Listar todos los pacientes <strong>&nbsp; GET</strong>
                                    </button>
                                </h2>
                                <div id="collapseListar" class="accordion-collapse collapse"
                                    aria-labelledby="headingListar" data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de listar todos los pacientes de la base de datos.</p>
                                        <p>consulta: <strong>http://sesamo.sytes.net/sesamo/pacientes/</strong> </p>

                                        <p>
                                            [<br>
                                            {<br>
                                            "nhc": 101010101,<br>
                                            "NIF": "10101010J",<br>
                                            "NumeroSS": "101010101010101",<br>
                                            "Nombre": "Raúl",<br>
                                            "Apellido1": "Fernández",<br>
                                            "Apellido2": "López",<br>
                                            "Sexo": "M",<br>
                                            "FechaNacimiento": "1970-02-18",<br>
                                            "Telefono1": "101010101",<br>
                                            "Telefono2": "444444444",<br>
                                            "Movil": "555555555",<br>
                                            "EstadoCivil": "Casado",<br>
                                            "Estudios": "Bachillerato",<br>
                                            "Fallecido": 0,<br>
                                            "Nacionalidad": "Española",<br>
                                            "CAutonoma": "Castilla-La Mancha",<br>
                                            "Provincia": "Cuenca",<br>
                                            "Poblacion": "Cuenca",<br>
                                            "CP": "16001",<br>
                                            "Direccion": "Calle Mayor 30",<br>
                                            "Titular": "Padre",<br>
                                            "Regimen": "General",<br>
                                            "TIS": "101010101010101",<br>
                                            "Medico": "Dr. Sánchez",<br>
                                            "CAP": "Cuenca",<br>
                                            "Zona": "Zona Centro",<br>
                                            "Area": "Traumatología",<br>
                                            "Nacimiento": "Cuenca",<br>
                                            "CAutonomaNacimiento": "Castilla-La Mancha",<br>
                                            "ProvinciaNacimiento": "Cuenca",<br>
                                            "PoblacionNacimiento": "Cuenca"<br>
                                            },...]<br>

                                        </p>

                                    </div>
                                </div>
                            </div>
                            <!-- listar pacientes paginados-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingListarP">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseListarP" aria-expanded="true"
                                        aria-controls="collapseListarP">
                                        Listar todos los pacientes por página <strong>&nbsp; GET</strong>
                                    </button>
                                </h2>
                                <div id="collapseListarP" class="accordion-collapse collapse"
                                    aria-labelledby="headingListarP" data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de listar todos los pacientes de la base de datos, se mostraran 15
                                            resultados por página.</p>
                                        <p>consulta: <strong>http://sesamo.sytes.net/sesamo/pacientes/page/{numero de
                                                pagina}</strong> </p>

                                        <p>
                                            [<br>
                                            {<br>
                                            "nhc": 101010101,<br>
                                            "NIF": "10101010J",<br>
                                            "NumeroSS": "101010101010101",<br>
                                            "Nombre": "Raúl",<br>
                                            "Apellido1": "Fernández",<br>
                                            "Apellido2": "López",<br>
                                            "Sexo": "M",<br>
                                            "FechaNacimiento": "1970-02-18",<br>
                                            "Telefono1": "101010101",<br>
                                            "Telefono2": "444444444",<br>
                                            "Movil": "555555555",<br>
                                            "EstadoCivil": "Casado",<br>
                                            "Estudios": "Bachillerato",<br>
                                            "Fallecido": 0,<br>
                                            "Nacionalidad": "Española",<br>
                                            "CAutonoma": "Castilla-La Mancha",<br>
                                            "Provincia": "Cuenca",<br>
                                            "Poblacion": "Cuenca",<br>
                                            "CP": "16001",<br>
                                            "Direccion": "Calle Mayor 30",<br>
                                            "Titular": "Padre",<br>
                                            "Regimen": "General",<br>
                                            "TIS": "101010101010101",<br>
                                            "Medico": "Dr. Sánchez",<br>
                                            "CAP": "Cuenca",<br>
                                            "Zona": "Zona Centro",<br>
                                            "Area": "Traumatología",<br>
                                            "Nacimiento": "Cuenca",<br>
                                            "CAutonomaNacimiento": "Castilla-La Mancha",<br>
                                            "ProvinciaNacimiento": "Cuenca",<br>
                                            "PoblacionNacimiento": "Cuenca"<br>
                                            },...]<br>

                                        </p>

                                    </div>
                                </div>
                            </div>
                            <!-- listar pacientes campo valor-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingCampoV">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseCampoV" aria-expanded="true"
                                        aria-controls="collapseCampoV">
                                        Listar paciente por NIF, NHC o NUMSS<strong>&nbsp; GET</strong>
                                    </button>
                                </h2>
                                <div id="collapseCampoV" class="accordion-collapse collapse"
                                    aria-labelledby="headingCampoV" data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de realizar búsquedas por los campos de NIF, Número de Historial
                                            clínico o Número de seguridad Social</p>
                                        <p>consulta:
                                        <ul>
                                            <li>NIF: <strong>http://sesamo.sytes.net/sesamo/pacientes/nif/{nif}</strong>
                                            </li>
                                            <li>NHC: <strong>http://sesamo.sytes.net/sesamo/pacientes/nhc/{nhc}</strong>
                                            </li>
                                            <li>NUMSS:
                                                <strong>http://sesamo.sytes.net/sesamo/pacientes/numss/{numss}</strong>
                                            </li>
                                        </ul>
                                        </p>

                                        <p>Como resultado obtendremos los datos del paciente: <br>
                                            [<br>
                                            {<br>
                                            "nhc": 101010101,<br>
                                            "NIF": "10101010J",<br>
                                            "NumeroSS": "101010101010101",<br>
                                            "Nombre": "Raúl",<br>
                                            "Apellido1": "Fernández",<br>
                                            "Apellido2": "López",<br>
                                            "Sexo": "M",<br>
                                            "FechaNacimiento": "1970-02-18",<br>
                                            "Telefono1": "101010101",<br>
                                            "Telefono2": "444444444",<br>
                                            "Movil": "555555555",<br>
                                            "EstadoCivil": "Casado",<br>
                                            "Estudios": "Bachillerato",<br>
                                            "Fallecido": 0,<br>
                                            "Nacionalidad": "Española",<br>
                                            "CAutonoma": "Castilla-La Mancha",<br>
                                            "Provincia": "Cuenca",<br>
                                            "Poblacion": "Cuenca",<br>
                                            "CP": "16001",<br>
                                            "Direccion": "Calle Mayor 30",<br>
                                            "Titular": "Padre",<br>
                                            "Regimen": "General",<br>
                                            "TIS": "101010101010101",<br>
                                            "Medico": "Dr. Sánchez",<br>
                                            "CAP": "Cuenca",<br>
                                            "Zona": "Zona Centro",<br>
                                            "Area": "Traumatología",<br>
                                            "Nacimiento": "Cuenca",<br>
                                            "CAutonomaNacimiento": "Castilla-La Mancha",<br>
                                            "ProvinciaNacimiento": "Cuenca",<br>
                                            "PoblacionNacimiento": "Cuenca"<br>
                                            }]<br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- Insertar paciente-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingInsertar">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseInsertar" aria-expanded="true"
                                        aria-controls="collapseInsertar">
                                        Insertar paciente <strong>&nbsp;POST</strong>
                                    </button>
                                </h2>
                                <div id="collapseInsertar" class="accordion-collapse collapse"
                                    aria-labelledby="headingInsertar" data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de insertar pacientes, se reuqiere token y metodo post</p>
                                        <p>consulta:
                                        <ul>
                                            <li><strong>http://sesamo.sytes.net/sesamo/pacientes/</strong> </li>
                                        </ul>
                                        </p>
                                        <p>Body (estos campos son obligatorio) : <br>
                                            {<br>
                                            "nhc" : "030303030",<br>
                                            "nif" : "1234Z",<br>
                                            "numeroSS" : "12789125345",<br>
                                            "fechaNacimiento" : "2000-11-03",<br>
                                            "nombre" : "jhoel",<br>
                                            "token": "253b611fa2714d00a5ee250a9c22e909"<br>
                                            }
                                        </p>
                                        <p>Resultados:</p>
                                        <ul>
                                            <li>Que se hayan metido los datos de forma erronea</li>
                                            <li>Que el token no sea valido</li>
                                            <li>Que el paciente ya exista</li>
                                            <li>Que se haya inseratdo el paciente de manera correcta</li>
                                            <li>Error interno</li>
                                        </ul>


                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!--modificar paciente-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingModificar">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseModificar" aria-expanded="true"
                                        aria-controls="collapseModificar">
                                        Modificar paciente <strong>&nbsp;PUT</strong>
                                    </button>
                                </h2>
                                <div id="collapseModificar" class="accordion-collapse collapse"
                                    aria-labelledby="headingModificar" data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de modificar datos de los pacientes, se requiere token, número de
                                            historial clínico y método put</p>
                                        <p>consulta:
                                        <ul>
                                            <li><strong>http://sesamo.sytes.net/sesamo/pacientes/</strong> </li>
                                        </ul>
                                        </p>
                                        <p>Body (nhc y token es obligatorio, el resto seran cambios a modificar) : <br>
                                            {<br>
                                            "nhc" : "030303030",<br>
                                            "nif" : "1234Z",<br>
                                            "numeroSS" : "12789125345",<br>
                                            "fechaNacimiento" : "2000-11-03",<br>
                                            "nombre" : "jhoel",<br>
                                            "token": "253b611fa2714d00a5ee250a9c22e909"<br>
                                            }
                                        </p>
                                        <p>Resultados:</p>
                                        <ul>
                                            <li>Que se hayan metido los datos de forma erronea</li>
                                            <li>Que el token no sea valido</li>
                                            <li>Que el paciente no exista</li>
                                            <li>Que se haya borrado el paciente de manera correcta</li>
                                            <li>Error interno</li>
                                        </ul>


                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--clases perfiles -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingPerfiles">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapsePerfiles" aria-expanded="false" aria-controls="collapsePerfiles">
                        Clase Perfiles
                    </button>
                </h2>
                <div id="collapsePerfiles" class="accordion-collapse collapse" aria-labelledby="headingPerfiles"
                    data-bs-parent="#accordionClasses">
                    <div class="accordion-body">
                        <p>La clase <strong>Perfiles</strong> lista todos los perfiles</p>

                        <div class="accordion" id="accordionMethodsPerfiles">
                            <!-- erro_200-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingCampoV">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseCampoV" aria-expanded="true"
                                        aria-controls="collapseCampoV">
                                        Listar perfiles<strong>&nbsp; GET</strong>
                                    </button>
                                </h2>
                                <div id="collapseCampoV" class="accordion-collapse collapse"
                                    aria-labelledby="headingCampoV" data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de listar los perfiles.</p>
                                        <p>consulta:
                                        <ul>
                                            <li> <strong>http://sesamo.sytes.net/sesamo/perfiles</strong> </li>
                                        </ul>
                                        </p>

                                        <p>Como resultado obtendremos los datos de los perfiles: <br>
                                            {<br>
                                            "id_rol": 3,<br>
                                            "nombre": "Administrativo"<br>
                                            }<br>
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
            <!--clases centros-->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingCentros">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseCentros" aria-expanded="false" aria-controls="collapseCentros">
                        Clase Centros
                    </button>
                </h2>
                <div id="collapseCentros" class="accordion-collapse collapse" aria-labelledby="headingCentros"
                    data-bs-parent="#accordionClasses">
                    <div class="accordion-body">
                        <p>La clase <strong>Centros</strong> lista todos los centros</p>

                        <div class="accordion" id="accordionMethodsPerfiles">
                            <!-- erro_200-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingCampoV">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseCampoV" aria-expanded="true"
                                        aria-controls="collapseCampoV">
                                        Listar centros<strong>&nbsp; GET</strong>
                                    </button>
                                </h2>
                                <div id="collapseCampoV" class="accordion-collapse collapse"
                                    aria-labelledby="headingCampoV" data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de listar los centros.</p>
                                        <p>consulta:
                                        <ul>
                                            <li> <strong>http://sesamo.sytes.net/sesamo/centros</strong> </li>
                                        </ul>
                                        </p>

                                        <p>Como resultado obtendremos los datos de los centros: <br>
                                            {<br>
                                            "id_centro": 1,<br>
                                            "nombre": "Complejo Hospitalario Universitario de Albacete",<br>
                                            "id_area_salud": 1<br>
                                            }<br>
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
            <!--clases ambitos-->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAmbitos">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseAmbitos" aria-expanded="false" aria-controls="collapseAmbitos">
                        Clase Ámbitos
                    </button>
                </h2>
                <div id="collapseAmbitos" class="accordion-collapse collapse" aria-labelledby="headingAmbitos"
                    data-bs-parent="#accordionClasses">
                    <div class="accordion-body">
                        <p>La clase <strong>Ámbitos</strong> lista todos los ámbitos</p>

                        <div class="accordion" id="accordionMethodsPerfiles">
                            <!-- erro_200-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingCampoV">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseCampoV" aria-expanded="true"
                                        aria-controls="collapseCampoV">
                                        Listar ámbitos<strong>&nbsp; GET</strong>
                                    </button>
                                </h2>
                                <div id="collapseCampoV" class="accordion-collapse collapse"
                                    aria-labelledby="headingCampoV" data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de listar los ámbitos.</p>
                                        <p>consulta:
                                        <ul>
                                            <li> <strong>http://sesamo.sytes.net/sesamo/ambitos</strong> </li>
                                        </ul>
                                        </p>

                                        <p>Como resultado obtendremos los datos de los ámbitos: <br>
                                            {<br>
                                            "id_ambito": 1,<br>
                                            "nombre": "Hospitalización"<br>
                                            }<br>
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <!--clases areas salud & zona basica-->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAreasSalud&ZonaBasica">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseAreasSalud&ZonaBasica" aria-expanded="false"
                        aria-controls="collapseAreasSalud&ZonaBasica">
                        Clase Áreas de salud y Zona básica
                    </button>
                </h2>
                <div id="collapseAreasSalud&ZonaBasica" class="accordion-collapse collapse"
                    aria-labelledby="headingAreasSalud&ZonaBasica" data-bs-parent="#accordionClasses">
                    <div class="accordion-body">
                        <p>La clase <strong>Áreas de salud y Zona básica</strong> lista todos las Áreas de salud y Zona
                            básica</p>

                        <div class="accordion" id="accordionMethodsPerfiles">
                            <!-- erro_200 areas salud-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingAreasSalud">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseAreasSalud" aria-expanded="true"
                                        aria-controls="collapseAreasSalud">
                                        Listar Áreas de salud<strong>&nbsp; GET</strong>
                                    </button>
                                </h2>
                                <div id="collapseAreasSalud" class="accordion-collapse collapse"
                                    aria-labelledby="headingAreasSalud" data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de listar las Áreas de salud.</p>
                                        <p>consulta:
                                        <ul>
                                            <li> <strong>http://sesamo.sytes.net/sesamo/areaSalud</strong>
                                            </li>
                                        </ul>
                                        </p>

                                        <p>Como resultado obtendremos los datos de las Áreas de salud:
                                            <br>
                                            {<br>
                                            "id_area_salud": 1,<br>
                                            "nombre": "Albacete"<br>
                                            }<br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- erro_200 zona basica-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingZona">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#callapseZona" aria-expanded="true"
                                        aria-controls="callapseZona">
                                        Listar Zonas básicas<strong>&nbsp; GET</strong>
                                    </button>
                                </h2>
                                <div id="callapseZona" class="accordion-collapse collapse" aria-labelledby="headingZona"
                                    data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de listar las Zonas básicas.</p>
                                        <p>consulta:
                                        <ul>
                                            <li> <strong>http://sesamo.sytes.net/sesamo/zonas</strong>
                                            </li>
                                        </ul>
                                        </p>

                                        <p>Como resultado obtendremos los datos de la Zonas básicas:
                                            <br>
                                            {<br>
                                            "id_zona_basica_salud": 1,<br>
                                            "nombre": "Alcadozo",<br>
                                            "cap": "Alcadozo (Albacete)",<br>
                                            "id_area_salud": 1,<br>
                                            "id_ambito": null<br>
                                            }<br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- erro_200-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingCampoV">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseCampoV" aria-expanded="true"
                                        aria-controls="collapseCampoV">
                                        Listar Zonas básicas por Área de salud<strong>&nbsp; GET</strong>
                                    </button>
                                </h2>
                                <div id="collapseCampoV" class="accordion-collapse collapse"
                                    aria-labelledby="headingCampoV" data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de listar las Zonas básicas por Área de salud.</p>
                                        <p>consulta:
                                        <ul>
                                            <li> <strong>http://sesamo.sytes.net/sesamo/zonas/{id_area_salud}</strong>
                                            </li>
                                        </ul>
                                        </p>

                                        <p>Como resultado obtendremos los datos de las Áreas de salud y Zona básica:
                                            <br>
                                            {<br>
                                            "id_zona_basica_salud": 1,<br>
                                            "nombre": "Alcadozo",<br>
                                            "cap": "Alcadozo (Albacete)",<br>
                                            "id_area_salud": 1,<br>
                                            "id_ambito": null<br>
                                            }<br>
                                        </p>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                </div>
            </div>
            <!--clases camas-->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingCamas">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseCamas" aria-expanded="false" aria-controls="collapseCamas">
                        Clase Camas
                    </button>
                </h2>
                <div id="collapseCamas" class="accordion-collapse collapse" aria-labelledby="headingCamas"
                    data-bs-parent="#accordionClasses">
                    <div class="accordion-body">
                        <p>La clase <strong>Camas</strong> se encarga de gestionar todas las camas.</p>

                        <div class="accordion" id="accordionMethodsPerfiles">
                            <!-- erro_200 camas-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingListaCamas">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseAreasSalud" aria-expanded="true"
                                        aria-controls="collapseAreasSalud">
                                        Listar camas<strong>&nbsp; GET</strong>
                                    </button>
                                </h2>
                                <div id="collapseAreasSalud" class="accordion-collapse collapse"
                                    aria-labelledby="headingListaCamas" data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de listar todas las camas.</p>
                                        <p>consulta:
                                        <ul>
                                            <li> <strong>http://sesamo.sytes.net/sesamo/camas/</strong>
                                            </li>
                                        </ul>
                                        </p>

                                        <p>Como resultado obtendremos los datos de las camas:
                                            <br>
                                            {<br>
                                            "id_cama": 1,<br>
                                            "id_planta": 1,<br>
                                            "id_habitacion": 1,<br>
                                            "letra": "A",<br>
                                            "bloqueada": "N",<br>
                                            "estado": "Ocupada",<br>
                                            "id_centro": 10<br>
                                            }<br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- erro_200 camas por centro-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingCamasPorCentro">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseCamasPorCentro" aria-expanded="true"
                                        aria-controls="collapseCamasPorCentro">
                                        Listar camas por centro<strong>&nbsp; GET</strong>
                                    </button>
                                </h2>
                                <div id="collapseCamasPorCentro" class="accordion-collapse collapse"
                                    aria-labelledby="headingCamasPorCentro" data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de listar las camas que hay en un centro.</p>
                                        <p>consulta:
                                        <ul>
                                            <li> <strong>http://sesamo.sytes.net/sesamo/camas/{id_centro}</strong>
                                            </li>
                                        </ul>
                                        </p>

                                        <p>Como resultado obtendremos los datos de las camas que hay en un centro:
                                            <br>
                                            {<br>
                                            "id_cama": 1,<br>
                                            "id_planta": 1,<br>
                                            "id_habitacion": 1,<br>
                                            "letra": "A",<br>
                                            "bloqueada": "N",<br>
                                            "estado": "Ocupada",<br>
                                            "id_centro": 10<br>
                                            }<br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- erro_200 plantas por centro-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingPlantasPorCentro">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapsePlantasPorCentro" aria-expanded="true"
                                        aria-controls="collapsePlantasPorCentro">
                                        Listar plantas por centro<strong>&nbsp; GET</strong>
                                    </button>
                                </h2>
                                <div id="collapsePlantasPorCentro" class="accordion-collapse collapse"
                                    aria-labelledby="headingPlantasPorCentro"
                                    data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de listar las plantas que hay en un centro.</p>
                                        <p>consulta:
                                        <ul>
                                            <li> <strong>http://sesamo.sytes.net/sesamo/camas/centro/{id_plantas}</strong>
                                            </li>
                                        </ul>
                                        </p>

                                        <p>Como resultado obtendremos las plantas que hay en un centro:
                                            <br>
                                            {<br>
                                            "plantas": 4<br>
                                            }<br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- erro_200 camas por centro y planta-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingCamasPorCentroYCama">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseCamasPorCentroYCama" aria-expanded="true"
                                        aria-controls="collapseCamasPorCentroYCama">
                                        Listar camas por centro y planta<strong>&nbsp; GET</strong>
                                    </button>
                                </h2>
                                <div id="collapseCamasPorCentroYCama" class="accordion-collapse collapse"
                                    aria-labelledby="headingCamasPorCentroYCama"
                                    data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de listar las camas que hay en una planta de determinado centro.
                                        </p>
                                        <p>consulta:
                                        <ul>
                                            <li> <strong>http://sesamo.sytes.net/sesamo/camas/centro/{id_centro}/{id_plantas}</strong>
                                            </li>
                                        </ul>
                                        </p>

                                        <p>Como resultado obtendremos los datos de las camas que hay en una planta de un
                                            determinado centro:
                                            <br>
                                            {<br>
                                            "id_cama": 1,<br>
                                            "id_planta": 1,<br>
                                            "id_habitacion": 1,<br>
                                            "letra": "A",<br>
                                            "bloqueada": "N",<br>
                                            "estado": "Ocupada",<br>
                                            "id_centro": 10<br>
                                            }<br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- erro_200 bloquear camas-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingModificar">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseModificar" aria-expanded="true"
                                        aria-controls="collapseModificar">
                                        Bloquear camas <strong>&nbsp;PUT</strong>
                                    </button>
                                </h2>
                                <div id="collapseModificar" class="accordion-collapse collapse"
                                    aria-labelledby="headingModificar" data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de bloquear o desbloquear camas</p>
                                        <p>consulta:
                                        <ul>
                                            <li><strong>http://sesamo.sytes.net/sesamo/camas/</strong> </li>
                                        </ul>
                                        </p>
                                        <p>Body (todos los datos son obligatorios) : <br>
                                            {<br>
                                            "id_cama": 6,<br>
                                            "bloqueada": "(Nuevo estado, obligatorio "S" o "N")",<br>
                                            "token": "348aa2bcb288db92d69a16c8be2217f6"<br>
                                            }<br>
                                        </p>
                                        <p>Resultados:</p>
                                        <ul>
                                            <li>Que se hayan metido los datos de forma erronea</li>
                                            <li>Que el token no sea valido</li>
                                            <li>Que la cama no exista</li>
                                            <li>Que la cama no esté disponible</li>
                                            <li>Que la cama esté bloqueada
                                            <li>
                                            <li>Error interno</li>
                                        </ul>


                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Clase alertas -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAlertas">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseAlertas" aria-expanded="false" aria-controls="collapseAlertas">
                        Clase Alertas
                    </button>
                </h2>
                <div id="collapseAlertas" class="accordion-collapse collapse" aria-labelledby="headingAlertas"
                    data-bs-parent="#accordionClasses">
                    <div class="accordion-body">
                        <p>La clase <strong>Alertas</strong> se encarga de listar todas las alertas</p>
                        <div class="accordion" id="accordionMethodsPacientes">
                            <!-- listar alertas-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingListarAlertas">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseListarAlertas" aria-expanded="true"
                                        aria-controls="collapseListarAlertas">
                                        Listar todos las alertas <strong>&nbsp; GET</strong>
                                    </button>
                                </h2>
                                <div id="collapseListarAlertas" class="accordion-collapse collapse"
                                    aria-labelledby="headingListarAlertas" data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de listar todos las alertas de la base de datos.</p>
                                        <p>consulta: <strong>http://sesamo.sytes.net/sesamo/alertas/</strong> </p>

                                        <p>
                                            {<br>
                                            "codigo": 7,<br>
                                            "descripcion": "alergia a ibuprofeno",<br>
                                            "observaciones": "grave",<br>
                                            "fecha": "2024-05-10",<br>
                                            "nhc": 3<br>
                                            }

                                        </p>

                                    </div>
                                </div>
                            </div>
                            <!-- listar alertas por NHC-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingListarNHC">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseListarNHC" aria-expanded="true"
                                        aria-controls="collapseListarNHC">
                                        Listar todos las alertas de un paciente indicado <strong>&nbsp; GET</strong>
                                    </button>
                                </h2>
                                <div id="collapseListarNHC" class="accordion-collapse collapse"
                                    aria-labelledby="headingListarNHC" data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de listar todos las alertas de un paciente indicado.</p>
                                        <p>consulta: <strong>http://sesamo.sytes.net/sesamo/alertas/{nhc}</strong> </p>

                                        <p>
                                            {<br>
                                            "codigo": 8,<br>
                                            "descripcion": "alergia polen 2",<br>
                                            "observaciones": "grave",<br>
                                            "fecha": "2024-05-02",<br>
                                            "nhc": 80<br>
                                            }<br>

                                        </p>

                                    </div>
                                </div>
                            </div>
                            <!-- crear alerta-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingInsertarAlertas">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseInsertarAlertas" aria-expanded="true"
                                        aria-controls="collapseInsertarAlertas">
                                        Crear alertas <strong>&nbsp;POST</strong>
                                    </button>
                                </h2>
                                <div id="collapseInsertarAlertas" class="accordion-collapse collapse"
                                    aria-labelledby="headingInsertarAlertas"
                                    data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de crear alertas</p>
                                        <p>consulta:
                                        <ul>
                                            <li><strong>http://sesamo.sytes.net/sesamo/alertas/</strong> </li>
                                        </ul>
                                        </p>
                                        <p>Body (estos campos son obligatorio) : <br>
                                            {<br>
                                            "status": "ok",<br>
                                            "result": {<br>
                                            "msg": "Se ha creado la alerta"<br>
                                            }<br>
                                            }<br>
                                        </p>
                                        <p>Resultados:</p>
                                        <ul>
                                            <li>Que se hayan metido los datos de forma erronea</li>
                                            <li>Que el token no sea valido</li>
                                            <li>Que la alerta ya exista</li>
                                            <li>Que se haya insertado la alerta de manera correcta</li>
                                            <li>Error interno</li>
                                        </ul>


                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!--modificar alerta-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingModificarAlerta">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseModificarAlerta" aria-expanded="true"
                                        aria-controls="collapseModificarAlerta">
                                        Modificar alerta <strong>&nbsp;PUT</strong>
                                    </button>
                                </h2>
                                <div id="collapseModificarAlerta" class="accordion-collapse collapse"
                                    aria-labelledby="headingModificarAlerta"
                                    data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de modificar las alertas</p>
                                        <p>consulta:
                                        <ul>
                                            <li><strong>http://sesamo.sytes.net/sesamo/alertas</strong> </li>
                                        </ul>
                                        </p>
                                        <p>Body (este campos serán obligatorios) : <br>
                                            {<br>
                                            "status": "ok",<br>
                                            "result": {<br>
                                            "msg": "Se ha modificado la alerta de forma correcta"<br>
                                            }<br>
                                            }<br>
                                        </p>
                                        <p>Resultados:</p>
                                        <ul>
                                            <li>Que se hayan metido los datos de forma erronea</li>
                                            <li>Que el token no sea valido</li>
                                            <li>Que la alerta no exista</li>
                                            <li>Que se haya modificado la alerta de manera correcta</li>
                                            <li>Error interno</li>
                                        </ul>


                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!--borrar alerta-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingBorrarAlerta">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseBorrarAlerta" aria-expanded="true"
                                        aria-controls="collapseBorrarAlerta">
                                        Borrar alertas <strong>&nbsp;DELETE</strong>
                                    </button>
                                </h2>
                                <div id="collapseBorrarAlerta" class="accordion-collapse collapse"
                                    aria-labelledby="headingBorrarAlerta" data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de eliminar las alertas</p>
                                        <p>consulta:
                                        <ul>
                                            <li><strong>http://sesamo.sytes.net/sesamo/alertas</strong> </li>
                                        </ul>
                                        </p>
                                        <p>Body (estos campos serán obligatorios) : <br>
                                            {<br>
                                            "status": "ok",<br>
                                            "result": {<br>
                                            "mensaje": "Alerta eliminada correctamente"<br>
                                            }<br>
                                            }<br>
                                        </p>
                                        <p>Resultados:</p>
                                        <ul>
                                            <li>Que se hayan metido los datos de forma erronea</li>
                                            <li>Que el token no sea valido</li>
                                            <li>Que la alerta no exista</li>
                                            <li>Que se haya borrado la alerta de manera correcta</li>
                                            <li>Error interno</li>
                                        </ul>


                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!--clase localidades-->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingLocalidades">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseLocalidades" aria-expanded="false" aria-controls="collapseLocalidades">
                        Clase Localidades
                    </button>
                </h2>
                <div id="collapseLocalidades" class="accordion-collapse collapse" aria-labelledby="headingLocalidades"
                    data-bs-parent="#accordionClasses">
                    <div class="accordion-body">
                        <p>La clase <strong>Localidades</strong> lista todos las localidades</p>

                        <div class="accordion" id="accordionMethodsPerfiles">
                            <!-- erro_200 listar provincias-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingProvincias">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseProvincias" aria-expanded="true"
                                        aria-controls="collapseProvincias">
                                        Listar Provincias<strong>&nbsp; GET</strong>
                                    </button>
                                </h2>
                                <div id="collapseProvincias" class="accordion-collapse collapse"
                                    aria-labelledby="headingProvincias" data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de listar las provincias.</p>
                                        <p>consulta:
                                        <ul>
                                            <li> <strong>http://sesamo.sytes.net/sesamo/provincias</strong>
                                            </li>
                                        </ul>
                                        </p>

                                        <p>Como resultado obtendremos los datos de las provincias:
                                            <br>
                                            {<br>
                                            "id_provincia": 1,<br>
                                            "nombre": "Almería",<br>
                                            "id_cautonoma": 1<br>
                                            }<br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!-- erro_200 listar comunidades-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingComunidad">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#callapseComunidad" aria-expanded="true"
                                        aria-controls="callapseComunidad">
                                        Listar Comunidades<strong>&nbsp; GET</strong>
                                    </button>
                                </h2>
                                <div id="callapseComunidad" class="accordion-collapse collapse"
                                    aria-labelledby="headingComunidad" data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de listar las comunidades.</p>
                                        <p>consulta:
                                        <ul>
                                            <li> <strong>http://sesamo.sytes.net/sesamo/comunidades</strong>
                                            </li>
                                        </ul>
                                        </p>

                                        <p>Como resultado obtendremos los datos de la Zonas básicas:
                                            <br>
                                            {<br>
                                            "id_cautonoma": 1,<br>
                                            "nombre": "Andalucía"<br>
                                            }<br>
                                        </p>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </div>

                </div>
            </div>
            <!-- Clase hojas preinscripción-->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingHojas">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseHojas" aria-expanded="false" aria-controls="collapseHojas">
                        Clase Hojas de Preinscripción
                    </button>
                </h2>
                <div id="collapseHojas" class="accordion-collapse collapse" aria-labelledby="headingHojas"
                    data-bs-parent="#accordionClasses">
                    <div class="accordion-body">
                        <p>La clase <strong>Hojas de preinscripción</strong> se encarga de listar todas las alertas</p>
                        <div class="accordion" id="accordionMethodsPacientes">
                            <!-- listar hojas de preinscripción-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingListarHojas">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseListarHojas" aria-expanded="true"
                                        aria-controls="collapseListarHojas">
                                        Listar todos las hojas de preinscripción <strong>&nbsp; GET</strong>
                                    </button>
                                </h2>
                                <div id="collapseListarHojas" class="accordion-collapse collapse"
                                    aria-labelledby="headingListarHojas" data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de listar todas las hojas de preinscripción de la base de datos.
                                        </p>
                                        <p>consulta:
                                            <strong>http://sesamo.sytes.net/sesamo/hojasPreinscripcion/</strong>
                                        </p>

                                        <p>
                                            {<br>
                                            "codigo": 7,<br>
                                            "descripcion": "alergia a ibuprofeno",<br>
                                            "observaciones": "grave",<br>
                                            "fecha": "2024-05-10",<br>
                                            "nhc": 3<br>
                                            }

                                        </p>

                                    </div>
                                </div>
                            </div>
                            <!-- listar hojas de preinscripción por NHC-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingHojasNHC">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseHojasNHC" aria-expanded="true"
                                        aria-controls="collapseHojasNHC">
                                        Listar todos las hojas de preinscripción de un paciente indicado <strong>&nbsp;
                                            GET</strong>
                                    </button>
                                </h2>
                                <div id="collapseHojasNHC" class="accordion-collapse collapse"
                                    aria-labelledby="headingHojasNHC" data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de listar todos las hojas de preinscripción de un paciente
                                            indicado.</p>
                                        <p>consulta:
                                            <strong>http://sesamo.sytes.net/sesamo/hojasPreinscripcion/{nhc}</strong>
                                        </p>

                                        <p>
                                            {<br>
                                            "codigo": 8,<br>
                                            "descripcion": "alergia polen 2",<br>
                                            "observaciones": "grave",<br>
                                            "fecha": "2024-05-02",<br>
                                            "nhc": 80<br>
                                            }<br>

                                        </p>

                                    </div>
                                </div>
                            </div>
                            <!-- crear hojas de preinscripción-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingInsertarHojas">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseInsertarHojas" aria-expanded="true"
                                        aria-controls="collapseInsertarHojas">
                                        Crear hojas de preinscripción <strong>&nbsp;POST</strong>
                                    </button>
                                </h2>
                                <div id="collapseInsertarHojas" class="accordion-collapse collapse"
                                    aria-labelledby="headingInsertarHojas" data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de crear hojas de preinscripción</p>
                                        <p>consulta:
                                        <ul>
                                            <li><strong>http://sesamo.sytes.net/sesamo/hojasPreinscripcion/</strong>
                                            </li>
                                        </ul>
                                        </p>
                                        <p>Body (estos campos son obligatorio) : <br>
                                            {<br>
                                            "status": "ok",<br>
                                            "result": {<br>
                                            "msg": "Se ha creado la alerta"<br>
                                            }<br>
                                            }<br>
                                        </p>
                                        <p>Resultados:</p>
                                        <ul>
                                            <li>Que se hayan metido los datos de forma erronea</li>
                                            <li>Que el token no sea valido</li>
                                            <li>Que la hoja de preinscripción ya exista</li>
                                            <li>Que se haya insertado la hojas de preinscripción de manera correcta</li>
                                            <li>Error interno</li>
                                        </ul>


                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Clase Patología-->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingPatologia">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapsePatologia" aria-expanded="false" aria-controls="collapsePatologia">
                        Clase Patología
                    </button>
                </h2>
                <div id="collapsePatologia" class="accordion-collapse collapse" aria-labelledby="headingPatologia"
                    data-bs-parent="#accordionClasses">
                    <div class="accordion-body">
                        <p>La clase <strong>Patología</strong> se encarga de listar todas las patologías</p>
                        <div class="accordion" id="accordionMethodsPacientes">
                            <!-- listar Patologia-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingListarPatologia">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseListarPatologia" aria-expanded="true"
                                        aria-controls="collapseListarPatologia">
                                        Listar todas las patologías <strong>&nbsp; GET</strong>
                                    </button>
                                </h2>
                                <div id="collapseListarPatologia" class="accordion-collapse collapse"
                                    aria-labelledby="headingListarPatologia"
                                    data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de listar todas las patologías de la base de datos.
                                        </p>
                                        <p>consulta:
                                            <strong>http://sesamo.sytes.net/sesamo/hojasPreinscripcion/</strong>
                                        </p>

                                        <p>
                                            {<br>
                                            "codigo": 7,<br>
                                            "descripcion": "alergia a ibuprofeno",<br>
                                            "observaciones": "grave",<br>
                                            "fecha": "2024-05-10",<br>
                                            "nhc": 3<br>
                                            }

                                        </p>

                                    </div>
                                </div>
                            </div>
                            <!-- listar Patologia por NHC-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingPatologiaNHC">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapsePatologiaNHC" aria-expanded="true"
                                        aria-controls="collapsePatologiaNHC">
                                        Listar todas las patologías de un paciente indicado <strong>&nbsp;
                                            GET</strong>
                                    </button>
                                </h2>
                                <div id="collapsePatologiaNHC" class="accordion-collapse collapse"
                                    aria-labelledby="headingPatologiaNHC" data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de listar todas las patologías de un paciente
                                            indicado.</p>
                                        <p>consulta:
                                            <strong>http://sesamo.sytes.net/sesamo/hojasPreinscripcion/{nhc}</strong>
                                        </p>

                                        <p>
                                            {<br>
                                            "codigo": 8,<br>
                                            "descripcion": "alergia polen 2",<br>
                                            "observaciones": "grave",<br>
                                            "fecha": "2024-05-02",<br>
                                            "nhc": 80<br>
                                            }<br>

                                        </p>

                                    </div>
                                </div>
                            </div>
                            <!-- crear hojas de preinscripción-->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingInsertarPatologia">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseInsertarPatologia" aria-expanded="true"
                                        aria-controls="collapseInsertarPatologia">
                                        Crear patologías <strong>&nbsp;POST</strong>
                                    </button>
                                </h2>
                                <div id="collapseInsertarPatologia" class="accordion-collapse collapse"
                                    aria-labelledby="headingInsertarPatologia"
                                    data-bs-parent="#accordionMethodsPacientes">
                                    <div class="accordion-body">
                                        <p>Se encarga de crear patologías</p>
                                        <p>consulta:
                                        <ul>
                                            <li><strong>http://sesamo.sytes.net/sesamo/hojasPreinscripcion/</strong>
                                            </li>
                                        </ul>
                                        </p>
                                        <p>Body (estos campos son obligatorio) : <br>
                                            {<br>
                                            "status": "ok",<br>
                                            "result": {<br>
                                            "msg": "Se ha creado la alerta"<br>
                                            }<br>
                                            }<br>
                                        </p>
                                        <p>Resultados:</p>
                                        <ul>
                                            <li>Que se hayan metido los datos de forma erronea</li>
                                            <li>Que el token no sea valido</li>
                                            <li>Que la patología ya exista</li>
                                            <li>Que se haya insertado la patología de manera correcta</li>
                                            <li>Error interno</li>
                                        </ul>


                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--antes de esto-->
        </div>
    </div>




    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>