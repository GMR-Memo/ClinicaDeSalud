<?php
namespace Modelos;
session_start();
class Cita {
    public int $id;
    public int $paciente_id;
    public int $doctor_id;
    public string $fecha;
    public string $motivo;

    public function __construct($id, $paciente_id, $doctor_id, $fecha, $motivo) {
        $this->id = $id;
        $this->paciente_id = $paciente_id;
        $this->doctor_id = $doctor_id;
        $this->fecha = $fecha;
        $this->motivo = $motivo;
    }
}