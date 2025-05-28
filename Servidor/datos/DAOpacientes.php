<?php
// datos/DAOPaciente.php

require_once __DIR__ . '/Conexion.php';
require_once __DIR__ . '/../modelos/paciente.php';

/**
 * DAO específico para operaciones CRUD de Paciente
 */
class PacienteDAO {
    private $con;

    public function __construct() {
        $this->con = Conexion::conectar();
    }

    /**
     * Inserta un nuevo Paciente en la BD
     * @param Paciente $paciente
     * @return Paciente con ID asignado
     */
    public function crear(Paciente $paciente): Paciente {
        $sql = "INSERT INTO usuarios
                (nombre, correo, genero, contrasenia, telefono, direccion, edad, rol)
                VALUES
                (:nombre, :correo, :genero, :pass, :telefono, :direccion, :edad, 'paciente')";
        $stmt = $this->con->prepare($sql);
        $hash = password_hash($paciente->contrasena, PASSWORD_DEFAULT);
        $stmt->execute([
            ':nombre'    => $paciente->nombre,
            ':correo'    => $paciente->correo,
            ':genero'    => $paciente->genero,
            ':pass'      => $hash,
            ':telefono'  => $paciente->telefono,
            ':direccion' => $paciente->direccion,
            ':edad'      => $paciente->edad,
        ]);
        $paciente->id = (int)$this->con->lastInsertId();
        return $paciente;
    }

    /**
     * Obtiene todos los pacientes
     * @return Paciente[]
     */
    public function obtenerTodos(): array {
        $sql = "SELECT * FROM usuarios WHERE rol = 'paciente' ORDER BY nombre";
        $stmt = $this->con->query($sql);
        $lista = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $lista[] = new Paciente(
                (int)$fila['id'],
                $fila['nombre'],
                $fila['correo'],
                $fila['genero'],
                '', // no exponer pasword
                $fila['telefono'],
                $fila['direccion'],
                (int)$fila['edad']
            );
        }
        return $lista;
    }

    /**
     * Obtiene un paciente por su ID
     * @param int $id
     * @return Paciente|null
     */
    public function obtenerPorId(int $id): ?Paciente {
        $sql = "SELECT * FROM usuarios WHERE id = :id AND rol = 'paciente'";
        $stmt = $this->con->prepare($sql);
        $stmt->execute([':id' => $id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$fila) return null;
        return new Paciente(
            (int)$fila['id'],
            $fila['nombre'],
            $fila['correo'],
            $fila['genero'],
            '',
            $fila['telefono'],
            $fila['direccion'],
            (int)$fila['edad']
        );
    }

    /**
     * Actualiza un paciente existente
     * @param Paciente $paciente
     * @return bool
     */
    public function actualizar(Paciente $paciente): bool {
        $sql = "UPDATE usuarios SET
                   nombre    = :nombre,
                   correo    = :correo,
                   genero    = :genero,
                   telefono  = :telefono,
                   direccion = :direccion,
                   edad      = :edad
                WHERE id = :id AND rol = 'paciente'";
        $stmt = $this->con->prepare($sql);
        return $stmt->execute([
            ':nombre'    => $paciente->nombre,
            ':correo'    => $paciente->correo,
            ':genero'    => $paciente->genero,
            ':telefono'  => $paciente->telefono,
            ':direccion' => $paciente->direccion,
            ':edad'      => $paciente->edad,
            ':id'        => $paciente->id,
        ]);
    }

    /**
     * Elimina un paciente por su ID
     * @param int $id
     * @return bool
     */
    public function eliminar(int $id): bool {
        $sql = "DELETE FROM usuarios WHERE id = :id AND rol = 'paciente'";
        $stmt = $this->con->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}

