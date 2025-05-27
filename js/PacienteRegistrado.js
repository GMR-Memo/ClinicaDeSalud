document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formularioRegistroPaciente");

  form.addEventListener("submit", (event) => {
    event.preventDefault(); // Evita el envío del formulario

    // Obtener los valores de los campos
    const nombre = document.getElementById("txtNombre").value.trim();
    const edad = parseInt(document.getElementById("txtEdad").value);
    const genero = document.getElementById("txtGenero")?.value || "";
    const correo = document.getElementById("txtEmail").value.trim();
    const contrasena = document.getElementById("txtPassword").value;
    const telefono = document.getElementById("txtTelefono").value.trim();
    const direccion = document.getElementById("txtDireccion").value.trim();

    // Validación básica
    if (!nombre || isNaN(edad) || edad < 0 || !genero || !correo || !contrasena || !telefono || !direccion) {
      alert("Por favor, completa todos los campos correctamente.");
      return;
    }

    // Validación del correo
    const correoRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!correoRegex.test(correo)) {
      alert("Por favor, ingresa un correo electrónico válido.");
      return;
    }

    // Validación del teléfono
    const telRegex = /^[0-9]{10}$/;
    if (!telRegex.test(telefono)) {
      alert("Ingresa un número de teléfono válido (10 dígitos).");
      return;
    }


    // Crear objeto paciente
    const paciente = {
      nombre,
      edad,
      genero,
      correo,
      contrasena,
      telefono,
      direccion
    };


    const pacientes = JSON.parse(localStorage.getItem("pacientes")) || [];

    // Verificar si el correo ya está registrado
    const correoExistente = pacientes.some(p => p.correo === correo);
    if (correoExistente) {
      alert("Este correo electrónico ya está registrado.");
      return;
    }

    // Guardar nuevo paciente
    pacientes.push(paciente);
    localStorage.setItem("pacientes", JSON.stringify(pacientes));

    // Redirigir a una página de éxito
    window.location.href = "registroExitoso.html";
  });
});
