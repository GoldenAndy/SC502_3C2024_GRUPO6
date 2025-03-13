document.addEventListener("DOMContentLoaded", function () {
    const togglePassword = document.getElementById("toggle-password");
    const passwordField = document.getElementById("password");

    if (togglePassword) {
        togglePassword.addEventListener("click", function () {
            if (passwordField.type === "password") {
                passwordField.type = "text";
                this.innerHTML = '<i class="bi bi-eye-slash"></i>';
            } else {
                passwordField.type = "password";
                this.innerHTML = '<i class="bi bi-eye"></i>';
            }
        });
    }
});
