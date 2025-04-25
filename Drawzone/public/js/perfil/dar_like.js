function darLike(idPublicacion, event) {
    if (event) event.stopPropagation();

    fetch("app/handlers/like_toggle.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ idPublicacion: idPublicacion })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const spans = document.querySelectorAll(`#likeCount${idPublicacion}`);
            spans.forEach(span => {
                span.textContent = data.nuevosLikes;
            });
        } else {
            console.error("❌ Error al dar like:", data.error);
        }
    })
    .catch(error => {
        console.error("❌ Error en la solicitud:", error);
    });
}
