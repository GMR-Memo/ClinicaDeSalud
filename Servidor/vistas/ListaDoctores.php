<?php
// vistas/ListaDoctores.php
session_start();

// Si no hay sesión de doctor, redirigir al login
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']->rol !== 'doctor') {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/../datos/DAODoctor.php';

$dao    = new DoctorDAO();
$doctores = $dao->obtenerTodos();

// Mensajes de sesión (éxito/error en operaciones previas)
if (isset($_SESSION['msg'])) {
    list($tipo, $texto) = explode('--', $_SESSION['msg'], 2);
    echo "<div class='alert $tipo'>$texto</div>";
    unset($_SESSION['msg']);
}

include __DIR__ . '/header.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lista de Doctores</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
  <main class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Doctores Registrados</h2>
      <a href="registroDoctor.php" class="btn btn-primary">Agregar Doctor</a>
    </div>
    <table id="tblDoctores" class="table table-striped">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Correo</th>
          <th>Cédula</th>
          <th>Especialidad</th>
          <th>Teléfono</th>
          <th>Dirección</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($doctores as $doc): ?>
        <tr>
          <td><?php echo htmlspecialchars($doc->nombre); ?></td>
          <td><?php echo htmlspecialchars($doc->correo); ?></td>
          <td><?php echo htmlspecialchars($doc->cedula); ?></td>
          <td><?php echo htmlspecialchars($doc->especialidad); ?></td>
          <td><?php echo htmlspecialchars($doc->telefono); ?></td>
          <td><?php echo htmlspecialchars($doc->direccion); ?></td>
          <td>
            <a href="registroDoctor.php?id=<?php echo $doc->id; ?>" class="btn btn-sm btn-warning">
              Editar
            </a>
            <form action="eliminarDoctor.php" method="post" class="d-inline">
              <input type="hidden" name="id" value="<?php echo $doc->id; ?>">
              <button type="submit" class="btn btn-sm btn-danger"
                      onclick="return confirm('¿Eliminar al Dr. <?php echo htmlspecialchars($doc->nombre); ?>?');">
                Eliminar
              </button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </main>

  <?php include __DIR__ . '/pie.php'; ?>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#tblDoctores').DataTable();
    });
  </script>
</body>
</html>
