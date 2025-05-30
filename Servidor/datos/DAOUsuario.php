<?php
// datos/DAOUsuario.php

require_once __DIR__ . '/Conexion.php';

class DAOUsuario {
    private $con;

    public function __construct() {
        $this->con = Conexion::conectar();
    }

    /**
     * Autentica al usuario por correo, contraseña y rol
     * @param string $correo
     * @param string $contrasena
     * @param string $rol ('paciente' o 'doctor')
     * @return object|null Devuelve un objeto con ID, correo y rol si es válido, sino null
     */
    public function autenticar(string $correo, string $contrasena, string $rol): ?object {
        $sql = "SELECT * FROM usuarios WHERE correo = :correo AND rol = :rol";
        $stmt = $this->con->prepare($sql);
        $stmt->execute([
            ':correo' => $correo,
            ':rol'    => $rol
        ]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            return (object)[
                'id'     => (int)$usuario['id'],
                'correo' => $usuario['correo'],
                'rol'    => $usuario['rol']
            ];
        }

        return null;
    }
}
