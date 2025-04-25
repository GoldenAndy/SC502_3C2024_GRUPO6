console.log("✅ indexGaleriaFiltros.js cargado (modo búsqueda)");

let offset = window.initialOffset || 400;
const limite = 400;
let cargando = false;
let totalCargados = offset;

$(document).ready(function () {
    const searchParams = new URLSearchParams(window.location.search);
    console.log("🔍 Parámetros activos para filtros:");
    searchParams.forEach((val, key) => console.log(`• ${key}: ${val}`));

    function construirTarjeta(arte) {
        console.log(`🧱 Añadiendo tarjeta: ${arte.titulo} (${arte.nombre_estilo})`);
        return `
            <div class="col">
                <a href="perfil.php?id=${arte.idUsuario}" class="text-decoration-none">
                    <div class="card shadow-sm">
                        <img src="${arte.link_dibujo}" class="card-img-top" alt="${arte.titulo}">
                        <div class="card-body text-center">
                            <h5 class="card-title mb-1">${arte.titulo}</h5>
                            <p class="card-text"><strong>${arte.nombre_usuario}</strong> | 
                            <span class="text-muted">${arte.nombre_estilo}</span> | 
                            <span class="text-muted">${arte.nombre_ilustracion}</span> | 
                            <span class="text-muted">${arte.nombre_coloreado}</span></p>
                        </div>
                    </div>
                </a>
            </div>`;
    }

    function cargarMasFiltrados() {
        if (cargando) {
            console.log("⏸️ Ya hay una carga en proceso.");
            return;
        }

        cargando = true;
        $("#loading").show();

        const params = new URLSearchParams();
        params.set("offset", offset);
        params.set("limite", limite);


        for (const [key, value] of searchParams.entries()) {
            params.append(key, value);
        }

        console.log("📤 Enviando a cargar_mas.php con:");
        for (const [k, v] of params.entries()) {
            console.log(`  • ${k}: ${v}`);
        }

        $.get("app/handlers/cargar_mas.php?" + params.toString(), function (data) {
            if (Array.isArray(data)) {
                if (data.length > 0) {
                    console.log(`📥 ${data.length} nuevos resultados encontrados.`);
                    data.forEach((arte, i) => {
                        console.log(`🔹 ${arte.titulo} (${arte.nombre_estilo})`);
                        $("#galeria-container").append(construirTarjeta(arte));
                    });
                    offset += data.length;
                    totalCargados += data.length;
                    console.log(`📈 Offset actualizado a ${offset}, total cargados: ${totalCargados}`);
                } else {
                    console.warn("🔚 Sin más datos, pero puede que aún haya por el filtro combinado. Revisando...");

                }
            }
        }).fail((xhr, status, err) => {
            console.error("❌ Error AJAX:", err);
            console.warn("📥 Respuesta:", xhr.responseText);
        }).always(() => {
            cargando = false;
            $("#loading").hide();
        });
    }

    $(window).scroll(() => {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
            console.log("📦 Scroll detectado, activando carga...");
            cargarMasFiltrados();
        }
    });
});
