<?php
/**
 * Clase para manejar la conexión a PostgreSQL mediante PDO
 */
class Conexion {
    private static $servidor  = 'localhost';
    private static $puerto    = '5432';
    private static $bd        = 'clinica_salud';
    private static $usuario   = 'tu_usuario';
    private static $password  = 'tu_password';
    private static $conexion  = null;

    // Evita instancias directas
    private function __construct() {}

    /**
     * Abre (o devuelve) la conexión a la base de datos
     * @return PDO
     */
    public static function conectar() {
        if (self::$conexion === null) {
            try {
                $dsn = "pgsql:host=" . self::$servidor
                     . ";port=" . self::$puerto
                     . ";dbname=" . self::$bd;
                self::$conexion = new PDO($dsn, self::$usuario, self::$password);
                self::$conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                exit('Error de conexión: ' . $e->getMessage());
            }
        }
        return self::$conexion;
    }

    /**
     * Cierra la conexión (método opcional)
     */
    public static function desconectar() {
        self::$conexion = null;
    }
}
?>
