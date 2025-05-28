<?php
// datos/DAODoctor.php

require_once __DIR__ . '/Conexion.php';
require_once __DIR__ . '/../modelos/doctor.php';

class DoctorDAO {
    private $con;

    public function __construct() {
        $this->con = Conexion::conectar();
    }

    /**
     * Inserta un nuevo Doctor en la BD
     * @param Doctor $doctor
     * @return Doctor con ID asignado
     */
    public function crear(Doctor $doctor): Doctor {
        $sql = "INSERT INTO usuarios
                (nombre, correo, contrasenia, cedula, especialidad, telefono, direccion, rol)
                VALUES
                (:nombre, :correo, :pass, :cedula, :especialidad, :telefono, :direccion, 'doctor')";
        $stmt = $this->con->prepare($sql);
        $hash = password_hash($doctor->contrasena, PASSWORD_DEFAULT);
        $stmt->execute([
            ':nombre'       => $doctor->nombre,
            ':correo'       => $doctor->correo,
            ':pass'         => $hash,
            ':cedula'       => $doctor->cedula,
            ':especialidad' => $doctor->especialidad,
            ':telefono'     => $doctor->telefono,
            ':direccion'    => $doctor->direccion,
        ]);
        $doctor->id = (int)$this->con->lastInsertId();
        return $doctor;
    }

    /**
     * Obtiene todos los doctores
     * @return Doctor[]
     */
    public function obtenerTodos(): array {
        $sql = "SELECT * FROM usuarios WHERE rol = 'doctor' ORDER BY nombre";
        $stmt = $this->con->query($sql);
        $lista = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $lista[] = new Doctor(
                (int)$fila['id'],
                $fila['nombre'],
                $fila['correo'],
                '',
                $fila['cedula'],
                $fila['especialidad'],
                $fila['telefono'],
                $fila['direccion']
            );
        }
        return $lista;
    }

    /**
     * Obtiene un doctor por su ID
     * @param int $id
     * @return Doctor|null
     */
    public function obtenerPorId(int $id): ?Doctor {
        $sql = "SELECT * FROM usuarios WHERE id = :id AND rol = 'doctor'";
        $stmt = $this->con->prepare($sql);
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$fila) {
            return null;
        }
        return new Doctor(
            (int)$fila['id'],
            $fila['nombre'],
            $fila['correo'],
            '',
            $fila['cedula'],
            $fila['especialidad'],
            $fila['telefono'],
            $fila['direccion']
        );
    }

    /**
     * Actualiza un doctor existente
     * @param Doctor $doctor
     * @return bool
     */
    public function actualizar(Doctor $doctor): bool {
        $sql = "UPDATE usuarios SET
                   nombre       = :nombre,
                   correo       = :correo,
                   cedula       = :cedula,
                   especialidad = :especialidad,
                   telefono     = :telefono,
                   direccion    = :direccion
                WHERE id = :id AND rol = 'doctor'";
        $stmt = $this->con->prepare($sql);
        return $stmt->execute([
            ':nombre'       => $doctor->nombre,
            ':correo'       => $doctor->correo,
            ':cedula'       => $doctor->cedula,
            ':especialidad' => $doctor->especialidad,
            ':telefono'     => $doctor->telefono,
            ':direccion'    => $doctor->direccion,
            ':id'           => $doctor->id,
        ]);
    }

    /**
     * Elimina un doctor por su ID
     * @param int $id
     * @return bool
     */
    public function eliminar(int $id): bool {
        $sql = "DELETE FROM usuarios WHERE id = :id AND rol = 'doctor'";
        $stmt = $this->con->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>
