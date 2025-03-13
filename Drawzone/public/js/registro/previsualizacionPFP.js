document.addEventListener("DOMContentLoaded", function () {
    const fileInput = document.getElementById("foto-perfil");
    const previewImage = document.getElementById("preview-image");

    fileInput.addEventListener("change", function (event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                previewImage.src = e.target.result; // Asigna la imagen cargada
                previewImage.style.display = "block"; // Asegura que la imagen se muestre
            };

            reader.readAsDataURL(file);
        } else {
            // Si no hay archivo, ocultamos la previsualización
            previewImage.src = "";
            previewImage.style.display = "none";
        }
    });
});
