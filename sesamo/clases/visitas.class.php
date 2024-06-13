<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";

class visitas extends conexion {

    private $tabla = "visitas";

    public function __construct() {
        parent::__construct();
    }

    public function obtenerVisitasPorDia() {
        $sql = "SELECT fecha, COUNT(*) as visitas FROM $this->tabla GROUP BY fecha";
        $result = $this->conexion->query($sql);
        return $this->procesarResultados($result);
    }

    public function obtenerVisitasPorMes() {
        $sql = "SELECT DATE_FORMAT(fecha, '%Y-%m') as mes, COUNT(*) as visitas FROM $this->tabla GROUP BY mes";
        $result = $this->conexion->query($sql);
        return $this->procesarResultados($result);
    }

    public function obtenerVisitasPorAno() {
        $sql = "SELECT DATE_FORMAT(fecha, '%Y') as ano, COUNT(*) as visitas FROM $this->tabla GROUP BY ano";
        $result = $this->conexion->query($sql);
        return $this->procesarResultados($result);
    }

    public function obtenerTotalVisitas() {
        $sql = "SELECT COUNT(*) as total_visitas FROM $this->tabla";
        $result = $this->conexion->query($sql);
        $row = $result->fetch_assoc();
        return $row ? $row : [];
    }
    public function obtenerVisitasPorPagina() {
        $sql = "SELECT pagina, COUNT(*) as visitas FROM $this->tabla GROUP BY pagina";
        $result = $this->conexion->query($sql);
        return $this->procesarResultados($result);
    }
    private function procesarResultados($result) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }
}
?>
