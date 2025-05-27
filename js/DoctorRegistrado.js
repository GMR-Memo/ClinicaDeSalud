console.log("JS cargado correctamente");

const regCorreo = /^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/;
const regTelefono = /^[0-9]{10}$/;

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formularioRegistroDoctor");

  document.getElementById("txtNombre").onkeyup = e => revisarTexto(e, 3, 50);
  document.getElementById("txtPassword").onkeyup = e => revisarTexto(e, 6, 20);
  document.getElementById("txtCedula").onkeyup = e => revisarTexto(e, 5, 20);
  document.getElementById("txtEspecialidad").onkeyup = e => revisarTexto(e, 3, 30);
  document.getElementById("txtDireccion").onkeyup = e => revisarTexto(e, 5, 100);

  document.getElementById("txtEmail").onkeyup = e => {
    const campo = e.target;
    if (campo.value.trim().match(regCorreo)) {
      campo.setCustomValidity("");
      marcarValido(campo);
    } else {
      campo.setCustomValidity("Correo no válido");
      marcarNoValido(campo);
    }
  };

  document.getElementById("txtTelefono").onkeyup = e => {
    const campo = e.target;
    if (campo.value.trim().match(regTelefono)) {
      campo.setCustomValidity("");
      marcarValido(campo);
    } else {
      campo.setCustomValidity("Teléfono no válido");
      marcarNoValido(campo);
    }
  };

  form.addEventListener("submit", e => {
    if (!form.checkValidity()) {
      e.preventDefault();
    }
  });
});

function revisarTexto(e, min, max) {
  const campo = e.target;
  campo.setCustomValidity("");
  if (campo.value.trim().length < min || campo.value.trim().length > max) {
    campo.setCustomValidity("Campo no válido");
    marcarNoValido(campo);
  } else {
    marcarValido(campo);
  }
}

function marcarValido(campo) {
  campo.classList.remove("novalido");
  campo.classList.add("valido");
}

function marcarNoValido(campo) {
  campo.classList.remove("valido");
  campo.classList.add("novalido");
}
