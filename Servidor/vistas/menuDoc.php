<?php
// public/panelDoctor.php
session_start();

// Si no está autenticado o no es doctor, redirigir al login
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_rol'] !== 'doctor') {
    header('Location: loginDoctor.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panel Doctor</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
  <link href="../css/MenuDoctores.css" rel="stylesheet" />
</head>
<body>
  <?php require '../vistas/menu_privado.php'; ?>
  <div class="dashboard d-flex">
    <aside class="sidebar bg-primary text-white p-3">
      <div class="brand text-center mb-4">
        <i class="fas fa-stethoscope fa-2x"></i>
        <h4 class="mt-2">Clínica Salud+</h4>
      </div>
      <nav class="nav flex-column">
        <div class="nav-section">Citas</div>
        <a href="crearCita.php" class="nav-item"><i class="fas fa-calendar-plus me-2"></i>Crear Cita</a>
        <a href="citas.php" class="nav-item"><i class="fas fa-calendar-alt me-2"></i>Ver/Editar Citas</a>
        <div class="nav-section">Pacientes</div>
        <a href="agregarPaciente.php" class="nav-item"><i class="fas fa-user-plus me-2"></i>Agregar Paciente</a>
        <a href="listaPacientes.php" class="nav-item"><i class="fas fa-users me-2"></i>Ver/Editar Pacientes</a>
        <div class="nav-section">Perfil</div>
        <a href="perfilDoctor.php" class="nav-item"><i class="fas fa-user-md me-2"></i>Mi Perfil</a>
        <a href="logout.php" class="nav-item"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a>
      </nav>
    </aside>
    <main class="content flex-fill p-4">
      <h1 class="mb-4">
        Bienvenido, Dr. 
        <span class="text-primary">
          <?php echo htmlspecialchars($_SESSION['usuario_nombre'], ENT_QUOTES, 'UTF-8'); ?>
        </span>
      </h1>
      <p>Seleccione una opción en el menú para navegar por las funciones.</p>
      <!-- Aquí puedes agregar widgets o tarjetas informativas -->
    </main>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
