const regexMail = /^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/;

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formLogin");
  const correo = document.getElementById("correo");
  const contrasena = document.getElementById("contrasena");

  document.getElementById("btnMostrarOcultar").addEventListener("click", e => {
    if (contrasena.type === "password") {
      contrasena.type = "text";
      e.target.innerText = "Ocultar";
    } else {
      contrasena.type = "password";
      e.target.innerText = "Ver";
    }
  });

  correo.addEventListener("input", () => validarCorreo());
  contrasena.addEventListener("input", () => validarLongitud(contrasena, 6, 50));

  form.addEventListener("submit", e => {
    let valido = true;
    const tipoUsuario = document.querySelector('input[name="tipoUsuario"]:checked');
    if (!tipoUsuario) {
      alert("Debes seleccionar el tipo de usuario.");
      valido = false;
    }

    if (!validarCorreo()) valido = false;
    if (!validarLongitud(contrasena, 6, 50)) valido = false;

    if (!valido) e.preventDefault();
  });

  function validarCorreo() {
    correo.classList.remove("valido", "novalido");
    if (!correo.value.trim().match(regexMail)) {
      correo.classList.add("novalido");
      correo.setCustomValidity("Correo no válido");
      return false;
    } else {
      correo.classList.add("valido");
      correo.setCustomValidity("");
      return true;
    }
  }

  function validarLongitud(input, min, max) {
    input.classList.remove("valido", "novalido");
    if (input.value.trim().length < min || input.value.trim().length > max) {
      input.classList.add("novalido");
      input.setCustomValidity("Longitud inválida");
      return false;
    } else {
      input.classList.add("valido");
      input.setCustomValidity("");
      return true;
    }
  }
});
