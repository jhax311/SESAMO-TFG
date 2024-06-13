<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class RegistroVisitas extends conexion
{

  private $tabla = "visitas";
  public function __construct()
  {
    //lamamos ak contructor padre
    parent::__construct();
    $this->verificarOCrearTabla($this->tabla);
  }

  public function registrarVisita($pagina)
  {
    // Capturar la fecha actual y la página visitada
    $fecha = date('Y-m-d');
    //evita inyecciones, quitando carcteres especiales
    $pagina = $this->conexion->real_escape_string($pagina);
    if ($pagina == "usuarios") {
      // Insertar la visita en la base de datos
      $sql = "INSERT INTO visitas (fecha, pagina) VALUES ('$fecha', '$pagina')";
      return $this->ejecutarConsulta($sql);
    }

  }
}

?>