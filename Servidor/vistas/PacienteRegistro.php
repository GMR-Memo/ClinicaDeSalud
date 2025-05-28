<?php
// vistas/registroPaciente.php
session_start();

require_once __DIR__ . '/../datos/DAOpacientes.php';
require_once __DIR__ . '/../modelos/paciente.php';

if (isset($_SESSION['usuario'])) {
    header('Location: menuPacientes.php');
    exit;
}

$error   = '';
$valores = [
    'nombre'     => '',
    'correo'     => '',
    'genero'     => '',
    'contrasena' => '',
    'telefono'   => '',
    'direccion'  => '',
    'edad'       => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($valores as $campo => &$valor) {
        $valor = trim($_POST[$campo] ?? '');
    }
    unset($valor);

    if (strlen($valores['nombre']) < 2) {
        $error = 'El nombre debe tener al menos 2 caracteres.';
    } elseif (!filter_var($valores['correo'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Ingrese un correo válido.';
    } elseif (!in_array($valores['genero'], ['Femenino','Masculino','Otro'])) {
        $error = 'Seleccione un género.';
    } elseif (strlen($valores['contrasena']) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif (!preg_match('/^[0-9]{10}$/', $valores['telefono'])) {
        $error = 'El teléfono debe tener 10 dígitos.';
    } elseif ($valores['direccion'] === '') {
        $error = 'La dirección es obligatoria.';
    } elseif (!is_numeric($valores['edad']) || $valores['edad'] < 0 || $valores['edad'] > 120) {
        $error = 'La edad debe estar entre 0 y 120.';
    }

    if (!$error) {
        $paciente = new Paciente(
            0,
            $valores['nombre'],
            $valores['correo'],
            $valores['genero'],
            $valores['contrasena'],
            $valores['telefono'],
            $valores['direccion'],
            (int)$valores['edad']
        );
        $dao       = new PacienteDAO();
        $resultado = $dao->crear($paciente);
        if ($resultado->id > 0) {
            $_SESSION['msg'] = 'alert-success--Paciente registrado correctamente';
            header('Location: ListaPacientes.php');
            exit;
        } else {
            $error = 'Ocurrió un error al registrar el paciente.';
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
  <title>Registro de Paciente</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="../css/estiloslogins.css" rel="stylesheet">
</head>
<body>
  <main class="container py-5">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">
        <div class="card">
          <div class="card-header bg-primary text-white text-center">
            <h3>Registro de Paciente</h3>
          </div>
          <div class="card-body">
            <?php if ($error): ?>
              <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" novalidate>
              <div class="mb-3">
                <label for="nombre" class="form-label">Nombre completo</label>
                <input type="text" id="nombre" name="nombre" class="form-control"
                       required minlength="2"
                       value="<?php echo htmlspecialchars($valores['nombre']); ?>">
              </div>
              <div class="mb-3">
                <label for="correo" class="form-label">Correo electrónico</label>
                <input type="email" id="correo" name="correo" class="form-control" required
                       value="<?php echo htmlspecialchars($valores['correo']); ?>">
              </div>
              <div class="mb-3">
                <label for="genero" class="form-label">Género</label>
                <select id="genero" name="genero" class="form-select" required>
                  <option value="">Seleccione...</option>
                  <option value="Femenino"  <?php if ($valores['genero']=='Femenino') echo 'selected'; ?>>Femenino</option>
                  <option value="Masculino" <?php if ($valores['genero']=='Masculino') echo 'selected'; ?>>Masculino</option>
                  <option value="Otro"      <?php if ($valores['genero']=='Otro') echo 'selected'; ?>>Otro</option>
                </select>
              </div>
              <div class="mb-3">
                <label for="contrasena" class="form-label">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" class="form-control"
                       required minlength="6">
              </div>
              <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" class="form-control" required pattern="[0-9]{10}"
                       value="<?php echo htmlspecialchars($valores['telefono']); ?>">
              </div>
              <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <textarea id="direccion" name="direccion" class="form-control" rows="2" required><?php
                  echo htmlspecialchars($valores['direccion']);
                ?></textarea>
              </div>
              <div class="mb-3">
                <label for="edad" class="form-label">Edad</label>
                <input type="number" id="edad" name="edad" class="form-control"
                       required min="0" max="120"
                       value="<?php echo htmlspecialchars($valores['edad']); ?>">
              </div>
              <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg">Crear cuenta</button>
              </div>
            </form>

            <p class="mt-3 text-center">
              <a href="crearCuenta.php">&larr; Volver a selección de rol</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </main>
  <?php include __DIR__ . '/pie.php'; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
