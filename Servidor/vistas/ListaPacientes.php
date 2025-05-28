<?php
// public/listaPacientes.php
session_start();
require_once __DIR__ . '/../dao/UsuarioDAO.php';

// Solo doctores pueden ver la lista de pacientes
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'doctor') {
    header('Location: loginDoctor.php');
    exit;
}

$dao = new UsuarioDAO();

// Manejo de eliminación
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $ok = $dao->eliminar((int)$_POST['id']);
    if ($ok) {
        $_SESSION['msg'] = 'alert-success--Paciente eliminado correctamente';
    } else {
        $_SESSION['msg'] = 'alert-danger--No se pudo eliminar al paciente';
    }
    header('Location: listaPacientes.php');
    exit;
}

// Mensaje flash
$msg = $_SESSION['msg'] ?? '';
unset($_SESSION['msg']);

// Obtenemos solo pacientes
$lista = $dao->listarPorRol('paciente');

// Mapeo de géneros
$generos = [
    'Femenino'  => 'Femenino',
    'Masculino' => 'Masculino',
    'Otro'      => 'Otro'
];
?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lista de Pacientes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/2.0.7/css/dataTables.bootstrap5.css" rel="stylesheet">
</head>

<body>
  <?php require '../vistas/menu_privado.php'; ?>

  <main class="py-4">
    <div class="container">
      <?php if ($msg): ?>
        <?php list($tipo, $texto) = explode('--', $msg, 2); ?>
        <div class="alert <?php echo $tipo; ?>"><?php echo htmlspecialchars($texto); ?></div>
      <?php endif; ?>

      <div class="d-flex mb-3">
        <a href="registroPaciente.php" class="btn btn-success"><i class="fas fa-user-plus me-2"></i>Nuevo Paciente</a>
      </div>

      <table id="tblPacientes" class="table table-striped">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Género</th>
            <th>Teléfono</th>
            <th>Edad</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($lista as $paciente): ?>
            <tr>
              <td><?php echo htmlspecialchars($paciente->nombre); ?></td>
              <td><?php echo htmlspecialchars($paciente->correo); ?></td>
              <td><?php echo htmlspecialchars($generos[$paciente->genero] ?? $paciente->genero); ?></td>
              <td><?php echo htmlspecialchars($paciente->telefono); ?></td>
              <td><?php echo htmlspecialchars($paciente->edad); ?></td>
              <td class="text-end">
                <a href="actualizarPaciente.php?id=<?php echo $paciente->id; ?>" class="btn btn-sm btn-primary">
                  <i class="fas fa-edit"></i>
                </a>
                <button
                  class="btn btn-sm btn-danger"
                  data-bs-toggle="modal"
                  data-bs-target="#mdlEliminar"
                  data-id="<?php echo $paciente->id; ?>"
                  data-nombre="<?php echo htmlspecialchars($paciente->nombre); ?>">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>

  <!-- Modal Confirmar Eliminación -->
  <div class="modal fade" id="mdlEliminar" tabindex="-1" aria-labelledby="mdlEliminarLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="post" action="listaPacientes.php">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="mdlEliminarLabel">Confirmar eliminación</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            ¿Eliminar al paciente <strong id="nombreEliminar"></strong>?
            <input type="hidden" name="id" id="idEliminar">
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger">Sí, eliminar</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.7/js/dataTables.bootstrap5.js"></script>
  <script>
    // Inicializar DataTable
    $(document).ready(function() {
      $('#tblPacientes').DataTable();
    });

    // Configurar modal de eliminación
    const mdlEliminar = document.getElementById('mdlEliminar');
    mdlEliminar.addEventListener('show.bs.modal', event => {
      const button = event.relatedTarget;
      const id = button.getAttribute('data-id');
      const nombre = button.getAttribute('data-nombre');
      document.getElementById('idEliminar').value = id;
      document.getElementById('nombreEliminar').textContent = nombre;
    });
  </script>
</body>
</html>
