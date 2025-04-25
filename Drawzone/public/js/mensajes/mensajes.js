$(document).ready(function () {
    function scrollAlFinal() {
        const contenedor = document.getElementById("contenedorMensajes");
        if (contenedor) {
            requestAnimationFrame(() => {
                contenedor.scrollTop = contenedor.scrollHeight;
                console.log("✅ Scroll al final (requestAnimationFrame)");
            });
        }
    }

    scrollAlFinal();

    $('#formMensaje').on('submit', function (e) {
        e.preventDefault();

        const form = $(this);
        const input = form.find('input[name="contenido"]');
        const contenido = input.val().trim();
        const idConversacion = form.find('input[name="idConversacion"]').val();

        if (!contenido) return;

        console.log("🔄 Enviando mensaje:", contenido);

        $.post('app/handlers/enviar_mensaje.php', {
            idConversacion: idConversacion,
            contenido: contenido
        }, function (data) {
            console.log("✅ Respuesta recibida:", data);

            if (data && data.success) {
                const mensajeSeguro = $('<div>').text(data.contenido).html();

                const mensajeHtml = `
                    <div class="mb-3 text-end">
                        <div class="p-2 rounded" style="display: inline-block; max-width: 70%; background-color: #A8E6CF;">
                            ${mensajeSeguro}
                        </div>
                        <div class="small text-muted mt-1">${data.hora}</div>
                    </div>
                `;

                $('#contenedorMensajes').append(mensajeHtml);
                input.val('');
                scrollAlFinal();
            } else {
                alert(data.error || "❌ Ocurrió un error al enviar el mensaje.");
            }
        }, 'json').fail(function (xhr, status, error) {
            console.error("❌ Error AJAX:", status, error);
            alert("❌ Error al contactar con el servidor.");
        });
    });
});
