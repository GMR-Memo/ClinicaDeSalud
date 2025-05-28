<?php
// modelos/Paciente.php

namespace modelos;

/**
 * Representa un paciente de la clínica
 */
class Paciente {
    public int    $id         = 0;
    public string $nombre     = "";
    public string $correo     = "";
    public string $genero     = "";  // “Femenino”, “Masculino” u “Otro”
    public string $contrasena = "";
    public string $telefono   = "";
    public string $direccion  = "";
    public int    $edad       = 0;

    public function __construct(
        int    $id         = 0,
        string $nombre     = "",
        string $correo     = "",
        string $genero     = "",
        string $contrasena = "",
        string $telefono   = "",
        string $direccion  = "",
        int    $edad       = 0
    ) {
        $this->id         = $id;
        $this->nombre     = $nombre;
        $this->correo     = $correo;
        $this->genero     = $genero;
        $this->contrasena = $contrasena;
        $this->telefono   = $telefono;
        $this->direccion  = $direccion;
        $this->edad       = $edad;
    }
}
