<?php
session_start();

require_once __DIR__ . '/../datos/DAOUsuario.php';
require_once __DIR__ . '/../datos/DAOdoctores.php';
require_once __DIR__ . '/../datos/DAOpacientes.php';
require_once __DIR__ . '/../datos/DAOadministrador.php'; // NUEVO
require_once __DIR__ . '/../modelos/Doctor.php';
require_once __DIR__ . '/../modelos/Paciente.php';
require_once __DIR__ . '/../modelos/Administrador.php'; // NUEVO

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rol        = $_POST['tipoUsuario'] ?? '';
    $correo     = trim($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    if (!in_array($rol, ['paciente', 'doctor', 'administrador'])) {
        $error = 'Seleccione un tipo de usuario válido.';
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = 'Ingrese un correo válido.';
    } elseif (strlen($contrasena) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } else {
        $daoUser = new DAOUsuario();
        $usuarioAuth = $daoUser->autenticar($correo, $contrasena, $rol);

        if ($usuarioAuth) {
            if ($rol === 'paciente') {
                $daoPac = new PacienteDAO();
                $usuario = $daoPac->obtenerPorId($usuarioAuth->id);
                $_SESSION['usuario_id'] = $usuario->id;
                $_SESSION['usuario_rol'] = 'paciente';
                $_SESSION['usuario_nombre'] = $usuario->nombre;
                header('Location: menuPacientes.php');
            } elseif ($rol === 'doctor') {
                $daoDoc = new DoctorDAO();
                $usuario = $daoDoc->obtenerPorId($usuarioAuth->id);
                $_SESSION['usuario_id'] = $usuario->id;
                $_SESSION['usuario_rol'] = 'doctor';
                $_SESSION['usuario_nombre'] = $usuario->nombre;
                header('Location: menuDoc.php');
            } elseif ($rol === 'administrador') {
                $daoAdmin = new AdministradorDAO();
                $usuario = $daoAdmin->obtenerPorId($usuarioAuth->id);
                $_SESSION['usuario_id'] = $usuario->id;
                $_SESSION['usuario_rol'] = 'administrador';
                $_SESSION['usuario_nombre'] = $usuario->nombre;
                header('Location: menuAdmin.php');
            }
            exit;
        } else {
            $error = 'Correo o contraseña incorrectos.';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Iniciar Sesión - Clínica Salud</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="card formulario-login w-100" style="max-width: 500px;">
    <div class="card-header bg-primary text-white text-center py-3">
      <h3>Iniciar Sesión</h3>
    </div>
    <div class="card-body px-4">
      <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <form method="post" id="formLogin" class="d-grid gap-3" novalidate>
  <div class="text-center mb-3">
    <label class="form-label fw-semibold">Tipo de usuario:</label>
    <div class="btn-group d-flex justify-content-center" role="group">
      <input type="radio" class="btn-check" name="tipoUsuario" id="paciente" value="paciente"
             <?php if (!isset($_POST['tipoUsuario']) || $_POST['tipoUsuario'] === 'paciente') echo 'checked'; ?>>
      <label class="btn btn-outline-primary" for="paciente">Paciente</label>

      <input type="radio" class="btn-check" name="tipoUsuario" id="doctor" value="doctor"
             <?php if (isset($_POST['tipoUsuario']) && $_POST['tipoUsuario'] === 'doctor') echo 'checked'; ?>>
      <label class="btn btn-outline-success" for="doctor">Doctor</label>

      <input type="radio" class="btn-check" name="tipoUsuario" id="administrador" value="administrador"
             <?php if (isset($_POST['tipoUsuario']) && $_POST['tipoUsuario'] === 'admin') echo 'checked'; ?>>
      <label class="btn btn-outline-warning" for="administrador">Admin</label>
    </div>
  </div>

  <div class="form-group">
    <label for="correo">Correo electrónico</label>
    <input type="email" class="form-control" id="correo" name="correo" required placeholder="ejemplo@correo.com"
           value="<?php echo htmlspecialchars($_POST['correo'] ?? ''); ?>">
  </div>

  <div class="form-group">
    <label for="contrasena">Contraseña</label>
    <div class="input-group">
      <input type="password" class="form-control" id="contrasena" name="contrasena" required placeholder="Ingresa tu contraseña">
      <button type="button" class="input-group-text" id="btnMostrarOcultar">Ver</button>
    </div>
  </div>

  <button type="submit" class="btn btn-primary btn-lg mt-3">Iniciar Sesión</button>
</form>


      <p class="text-center mt-4 text-muted">
        ¿No tienes una cuenta?
        <a href="crearCuenta.php">Crear una cuenta</a>
      </p>
      <div class="text-center mt-3">
        <a href="../../index.php" class="btn btn-secondary">← Regresar</a>
      </div>
    </div>
  </div>
</div>

<script src="js/login.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
