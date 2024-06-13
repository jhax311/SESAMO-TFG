<?php
$configContent = file_get_contents('config.json');

if ($configContent === false) {
    die("No se pudo leer el archivo de configuración.");
}

$config = json_decode($configContent, true);

if ($config === null) {
    die("Error al decodificar el archivo de configuración.");
}

$dbConfig = $config['conexion'];

$conn = new mysqli($dbConfig['server'], $dbConfig['user'], $dbConfig['password'], '', $dbConfig['port']);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$dbNombre = $dbConfig['database'];
$existe= "SHOW DATABASES LIKE '$dbNombre'";
$result = $conn->query($dbCheckQuery);

if ($result->num_rows == 0) {
    $createDbQuery = "CREATE DATABASE $dbNombre";
    if ($conn->query($createDbQuery) === TRUE) {
        echo "Base de datos '$dbNombre' creada exitosamente.";
    } else {
        die("Error al crear la base de datos: " . $conn->error);
    }
}

$conn->select_db($dbNombre);

$sqlFile = 'bddBase.sql';
$sqlContent = file_get_contents($sqlFile);

if ($sqlContent === false) {
    die("No se pudo leer el archivo SQL.");
}

if ($conn->multi_query($sqlContent)) {
    do {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->next_result());
    
    echo "Script SQL ejecutado exitosamente.";
} else {
    echo "Error al ejecutar el script SQL: " . $conn->error;
}

$conn->close();
?>
