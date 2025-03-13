document.addEventListener("DOMContentLoaded", function () {
    const rolRadios = document.querySelectorAll("input[name='rol']");
    const redesSocialesDiv = document.getElementById("redes-sociales");

    // Mostrar/Ocultar redes sociales dependiendo del rol seleccionado
    rolRadios.forEach(radio => {
        radio.addEventListener("change", function () {
            if (this.value === "artista" || this.value === "ambos") {
                redesSocialesDiv.classList.remove("d-none");
            } else {
                redesSocialesDiv.classList.add("d-none");
            }
        });
    });

    // Validación de redes sociales
    function validarRedSocial(input, regex, errorId) {
        const valor = input.value.trim();
        const errorMsg = document.getElementById(errorId);

        if (valor === "" || regex.test(valor)) {
            errorMsg.classList.add("d-none");
            input.classList.remove("is-invalid");
        } else {
            errorMsg.classList.remove("d-none");
            input.classList.add("is-invalid");
        }
    }

    // Twitter (X) → Acepta https://x.com/usuario
    document.getElementById("twitter").addEventListener("input", function () {
        validarRedSocial(this, /^https?:\/\/(www\.)?x\.com\/[A-Za-z0-9_]+$/, "error-twitter");
    });

    // Instagram → Acepta https://www.instagram.com/usuario
    document.getElementById("instagram").addEventListener("input", function () {
        validarRedSocial(this, /^https?:\/\/(www\.)?instagram\.com\/[A-Za-z0-9_.-]+(\/\?hl=\w{2})?$/, "error-instagram");
    });

    // Facebook → Acepta ambos formatos:
    // 1. https://www.facebook.com/usuario
    // 2. https://www.facebook.com/profile.php?id=123456789
    document.getElementById("facebook").addEventListener("input", function () {
        validarRedSocial(this, /^https?:\/\/(www\.)?facebook\.com\/(profile\.php\?id=\d+|[A-Za-z0-9_.-]+)$/, "error-facebook");
    });

    // Mostrar/ocultar contraseña
    document.getElementById("toggle-password").addEventListener("click", function () {
        const passwordField = document.getElementById("password");
        passwordField.type = passwordField.type === "password" ? "text" : "password";
        this.innerHTML = passwordField.type === "password" ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
    });
});
