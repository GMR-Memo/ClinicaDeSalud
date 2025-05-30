<?php
// vistas/panelPaciente.php
session_start();

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Panel Paciente</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="../Formularios/css/MenuPacientes.css" />
</head>
<body>
  <div class="dashboard d-flex">
    <aside class="sidebar bg-success text-white p-3">
      <div class="brand text-center mb-4">
        <i class="fas fa-heartbeat fa-2x"></i>
        <h4 class="mt-2">Clínica Salud+</h4>
      </div>
      <nav class="nav flex-column">
        <div class="nav-section">Citas</div>
        <a href="MisCitas.php" class="nav-item"><i class="fas fa-calendar-alt me-2"></i>Mis Citas</a>
        <div class="nav-section">Historial</div>
        <a href="HistorialMedico.php" class="nav-item"><i class="fas fa-notes-medical me-2"></i>Historial Médico</a>
        <div class="nav-section">Perfil</div>
        <a href="PerfilPaciente.php" class="nav-item"><i class="fas fa-user me-2"></i>Mi Perfil</a>
        <a href="logout.php" class="nav-item"><i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión</a>
      </nav>
    </aside>
    <main class="content flex-fill p-4">
    <h1 class="mb-4">Bienvenido, <span class="text-success"><?php echo $_SESSION['usuario_nombre']; ?></span></h1>
 <p>Seleccione una opción en el menú para navegar por las funciones.</p>
    </main>
  </div>
</body>
</html>
