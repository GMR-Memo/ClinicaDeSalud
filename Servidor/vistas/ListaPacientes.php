<?php
session_start();
require_once __DIR__ . '/../datos/DAOpacientes.php';
use Modelos\Paciente;

$dao = new PacienteDAO();
$pacientes = $dao->obtenerTodos();

include __DIR__ . '/header.php';
if (isset($_SESSION['msg'])) {
    [$tipo, $texto] = explode('--', $_SESSION['msg'], 2);
    echo "<div class='alert $tipo'>$texto</div>";
    unset($_SESSION['msg']);
}
?> 
<main class="container py-4">
  <h2>Lista de Pacientes</h2>
  <a href="PacienteRegistro.php" class="btn btn-success mb-3">Registrar Nuevo</a>
  <table class="table table-bordered table-hover">
    <thead class="table-light">
      <tr>
        <th>Nombre</th><th>Correo</th><th>Género</th><th>Edad</th><th>Teléfono</th><th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($pacientes as $pac): ?>
        <tr>
          <td><?= htmlspecialchars($pac->nombre) ?></td>
          <td><?= htmlspecialchars($pac->correo) ?></td>
          <td><?= htmlspecialchars($pac->genero) ?></td>
          <td><?= htmlspecialchars($pac->edad) ?></td>
          <td><?= htmlspecialchars($pac->telefono) ?></td>
          <td>
            <a href="PacienteRegistro.php?id=<?= $pac->id ?>" class="btn btn-warning btn-sm">Editar</a>
            <form method="post" action="eliminarPaciente.php" class="d-inline">
              <input type="hidden" name="id" value="<?= $pac->id ?>">
              <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar paciente?')">Eliminar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</main>
<?php include __DIR__ . '/pie.php'; ?>
