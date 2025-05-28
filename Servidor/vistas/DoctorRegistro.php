<?php
// vistas/registroDoctor.php
session_start();

require_once __DIR__ . '/../datos/DAOdoctores.php';
require_once __DIR__ . '/../modelos/doctor.php';

if (isset($_SESSION['usuario'])) {
    header('Location: menuDoc.php');
    exit;
}

$error = '';
$valores = [
    'nombre'       => '',
    'correo'       => '',
    'contrasena'   => '',
    'cedula'       => '',
    'especialidad' => '',
    'telefono'     => '',
    'direccion'    => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger y sanear datos
    foreach ($valores as $campo => &$valor) {
        $valor = trim($_POST[$campo] ?? '');
    }
    unset($valor);

    // Validaciones
    if (strlen($valores['nombre']) < 2) {
        $error = 'El nombre debe tener al menos 2 caracteres.';
    } elseif (!filter_var($valores['correo'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Formato de correo inválido.';
    } elseif (strlen($valores['contrasena']) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif (empty($valores['cedula'])) {
        $error = 'La cédula profesional es obligatoria.';
    } elseif (empty($valores['especialidad'])) {
        $error = 'La especialidad es obligatoria.';
    } elseif (!preg_match('/^[0-9]{10}$/', $valores['telefono'])) {
        $error = 'El teléfono debe tener exactamente 10 dígitos.';
    } elseif ($valores['direccion'] === '') {
        $error = 'La dirección de consulta es obligatoria.';
    }

    if (!$error) {
        // Crear y persistir Doctor
        $doctor = new Doctor(
            0,
            $valores['nombre'],
            $valores['correo'],
            $valores['contrasena'],
            $valores['cedula'],
            $valores['especialidad'],
            $valores['telefono'],
            $valores['direccion']
        );
        $dao       = new DoctorDAO();
        $resultado = $dao->crear($doctor);
        if ($resultado->id > 0) {
            $_SESSION['msg'] = 'alert-success--Doctor registrado correctamente';
            header('Location: ListaDoctores.php');
            exit;
        } else {
            $error = 'Error al registrar el doctor.';
        }
    }
}

include __DIR__ . '/header.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registro de Doctor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/estiloslogins.css" rel="stylesheet">
</head>
<body>
  <main class="container py-5">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">
        <div class="card">
          <div class="card-header bg-primary text-white text-center">
            <h3>Registro de Doctor</h3>
          </div>
          <div class="card-body">
            <?php if ($error): ?>
              <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" class="row g-3">
              <div class="col-md-6">
                <label for="nombre" class="form-label">Nombre completo</label>
                <input type="text" id="nombre" name="nombre" class="form-control" required minlength="2"
                       value="<?php echo htmlspecialchars($valores['nombre']); ?>">
              </div>

              <div class="col-md-6">
                <label for="correo" class="form-label">Correo electrónico</label>
                <input type="email" id="correo" name="correo" class="form-control" required
                       value="<?php echo htmlspecialchars($valores['correo']); ?>">
              </div>

              <div class="col-md-6">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" class="form-control" required minlength="6">
              </div>

              <div class="col-md-6">
                <label for="cedula" class="form-label">Cédula profesional</label>
                <input type="text" id="cedula" name="cedula" class="form-control" required
                       value="<?php echo htmlspecialchars($valores['cedula']); ?>">
              </div>

              <div class="col-md-6">
                <label for="especialidad" class="form-label">Especialidad</label>
                <input type="text" id="especialidad" name="especialidad" class="form-control" required
                       value="<?php echo htmlspecialchars($valores['especialidad']); ?>">
              </div>

              <div class="col-md-6">
                <label for="telefono" class="form-label">Teléfono de contacto</label>
                <input type="tel" id="telefono" name="telefono" class="form-control" required pattern="[0-9]{10}"
                       value="<?php echo htmlspecialchars($valores['telefono']); ?>">
              </div>

              <div class="col-12">
                <label for="direccion" class="form-label">Dirección de consulta</label>
                <textarea id="direccion" name="direccion" class="form-control" rows="2" required><?php
                  echo htmlspecialchars($valores['direccion']);
                ?></textarea>
              </div>

              <div class="col-12 d-grid">
                <button type="submit" class="btn btn-success btn-lg">Registrar Doctor</button>
                <a href="ListaDoctores.php" class="btn btn-secondary mt-2">Volver a lista</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
  <?php include __DIR__ . '/pie.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
