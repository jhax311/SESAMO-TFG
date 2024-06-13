<?php
$configContent = file_get_contents('config');

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
$result = $conn->query($existe);

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

$conn->begin_transaction();

try {
    $queries = explode(";", $sqlContent);
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            if ($conn->query($query) === false) {
                throw new Exception("Error al ejecutar la consulta: " . $conn->error);
            }
        }
    }
    $conn->commit();
    echo "Script SQL ejecutado exitosamente.";
} catch (Exception $e) {
    $conn->rollback();
    die($e->getMessage());
}

$conn->close();
?>
