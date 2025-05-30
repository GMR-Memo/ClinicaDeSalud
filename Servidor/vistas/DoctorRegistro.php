<?php
session_start();
require_once __DIR__ . '/../datos/DAOdoctores.php';
require_once __DIR__ . '/../modelos/Doctor.php';
use Modelos\Doctor;

$dao = new DoctorDAO();
$doctor = new Doctor(0, '', '', '', '', '', '', '');
$error = '';

// Si es edición, obtener datos del doctor
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $doctor = $dao->obtenerPorId((int)$_GET['id']);
    if (!$doctor) {
        $_SESSION['msg'] = 'alert-warning--Doctor no encontrado.';
        header('Location: ListaDoctores.php');
        exit;
    }
}

// Procesar POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor->id           = (int)($_POST['id'] ?? 0);
    $doctor->nombre       = trim($_POST['nombre'] ?? '');
    $doctor->correo       = trim($_POST['correo'] ?? '');
    $doctor->contrasena   = trim($_POST['contrasena'] ?? '');
    $doctor->cedula       = trim($_POST['cedula'] ?? '');
    $doctor->especialidad = trim($_POST['especialidad'] ?? '');
    $doctor->telefono     = trim($_POST['telefono'] ?? '');
    $doctor->direccion    = trim($_POST['direccion'] ?? '');

    // Validación
    if (strlen($doctor->nombre) < 2) {
        $error = 'El nombre debe tener al menos 2 caracteres.';
    } elseif (!filter_var($doctor->correo, FILTER_VALIDATE_EMAIL)) {
        $error = 'Correo no válido.';
    } elseif ($doctor->id === 0 && strlen($doctor->contrasena) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif (empty($doctor->cedula) || empty($doctor->especialidad) || empty($doctor->direccion)) {
        $error = 'Campos obligatorios no llenados.';
    } elseif (!preg_match('/^[0-9]{10}$/', $doctor->telefono)) {
        $error = 'Teléfono inválido.';
    }

    if (!$error) {
        if ($doctor->id === 0) {
            $dao->crear($doctor);
        } else {
            $dao->actualizar($doctor);
        }
        $_SESSION['msg'] = 'alert-success--Doctor guardado exitosamente.';
        header('Location: ListaDoctores.php');
        exit;
    }
}

include __DIR__ . '/header.php';
?>

<main class="container py-4">
  <h2><?= $doctor->id ? 'Editar Doctor' : 'Registrar Doctor'; ?></h2>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="post">
    <input type="hidden" name="id" value="<?= $doctor->id ?>">
    <div class="mb-3">
      <label class="form-label">Nombre</label>
      <input type="text" class="form-control" name="nombre" required minlength="2" value="<?= htmlspecialchars($doctor->nombre) ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Correo</label>
      <input type="email" class="form-control" name="correo" required value="<?= htmlspecialchars($doctor->correo) ?>">
    </div>
    <?php if ($doctor->id === 0): ?>
    <div class="mb-3">
      <label class="form-label">Contraseña</label>
      <input type="password" class="form-control" name="contrasena" required minlength="6">
    </div>
    <?php endif; ?>
    <div class="mb-3">
      <label class="form-label">Cédula</label>
      <input type="text" class="form-control" name="cedula" required value="<?= htmlspecialchars($doctor->cedula) ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Especialidad</label>
      <input type="text" class="form-control" name="especialidad" required value="<?= htmlspecialchars($doctor->especialidad) ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Teléfono</label>
      <input type="tel" class="form-control" name="telefono" required pattern="[0-9]{10}" value="<?= htmlspecialchars($doctor->telefono) ?>">
    </div>
    <div class="mb-3">
      <label class="form-label">Dirección</label>
      <textarea class="form-control" name="direccion" required><?= htmlspecialchars($doctor->direccion) ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="ListaDoctores.php" class="btn btn-secondary">Cancelar</a>
  </form>
</main>

<?php include __DIR__ . '/pie.php'; ?>
