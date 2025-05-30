<?php
require_once __DIR__ . '/Conexion.php';
require_once __DIR__ . '/../modelos/Cita.php';
session_start();
use Modelos\Cita;

class CitaDAO {
    private $con;

    public function __construct() {
        $this->con = Conexion::conectar();
    }

    public function crear(Cita $cita): ?int {
        $sql = "INSERT INTO citas (paciente_id, doctor_id, fecha, motivo)
                VALUES (:paciente_id, :doctor_id, :fecha, :motivo)";
        $stmt = $this->con->prepare($sql);
        if ($stmt->execute([
            ':paciente_id' => $cita->paciente_id,
            ':doctor_id'   => $cita->doctor_id,
            ':fecha'       => $cita->fecha,
            ':motivo'      => $cita->motivo
        ])) {
            return (int)$this->con->lastInsertId();
        }
        return null;
    }
}   