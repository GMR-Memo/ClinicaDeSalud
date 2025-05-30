<?php
session_start();
require_once __DIR__ . '/../modelos/Doctor.php';
require_once __DIR__ . '/../modelos/Cita.php';
require_once __DIR__ . '/../datos/DAOcitas.php';
require_once __DIR__ . '/../datos/Conexion.php';

use Modelos\Cita;

$error = '';
$exito = '';

$con = Conexion::conectar();
$pacientes = $con->query("SELECT p.id, p.nombre FROM pacientes p JOIN usuarios u ON p.id = u.id ORDER BY p.nombre")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pacienteId = $_POST['paciente'] ?? '';
    $fecha      = $_POST['fecha'] ?? '';
    $hora       = $_POST['hora'] ?? '';
    $motivo     = trim($_POST['motivo'] ?? '');

    if (!$pacienteId || !$fecha || !$hora || !$motivo) {
        $error = 'Todos los campos son obligatorios.';
    } else {
        $doctorId = $_SESSION['usuario']->id ?? null;
        if (!$doctorId) {
            die('Error: No hay sesión activa para el doctor.');
        }

        $cita = new Cita(
            0,
            (int)$pacienteId,
            (int)$doctorId,
            "$fecha $hora",
            $motivo
        );

        $dao = new CitaDAO();
        $resultado = $dao->crear($cita);

        if ($resultado) {
            $exito = 'Cita creada correctamente.';
        } else {
            $error = 'Ocurrió un error al guardar la cita.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Crear Cita - Clínica</title>
  <link rel="stylesheet" href="../Formularios/css/crearcita.css" />
</head>
<body>
  <div class="form-wrapper">
    <h2>Agendar Nueva Cita</h2>

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($exito): ?>
      <div class="alert alert-success"><?= htmlspecialchars($exito) ?></div>
    <?php endif; ?>

    <form method="post" novalidate>
      <label for="paciente">Paciente</label>
      <select id="paciente" name="paciente" required>
        <option value="">Selecciona paciente</option>
        <?php foreach ($pacientes as $p): ?>
          <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
        <?php endforeach; ?>
      </select>

      <label for="fecha">Fecha</label>
      <input type="date" id="fecha" name="fecha" required />

      <label for="hora">Hora</label>
      <input type="time" id="hora" name="hora" required />

      <label for="motivo">Motivo de Consulta</label>
      <textarea id="motivo" name="motivo" rows="3" placeholder="Describa brevemente..." required></textarea>

      <input type="submit" value="Guardar Cita" />
    </form>

    <p class="back-link"><a href="menuDoc.php">← Volver al Panel</a></p>
  </div>
</body>
</html>
