console.log("✅ indexGaleria.js cargado");

let offset = 9;
const limite = 9;
let cargando = false;
let modoBusqueda = false;

$(document).ready(function () {
    offset = window.initialOffset || 9;
    console.log("📍 Offset corregido tras DOM load:", offset);

    const searchParams = new URLSearchParams(window.location.search);
    const valorBusqueda = searchParams.get("busqueda");

    const tieneBusqueda = valorBusqueda && valorBusqueda.trim() !== "";
    let tieneFiltros = false;
    const posiblesFiltros = ["estilos", "ilustraciones", "coloreados", "tipo_usuario"];

    for (const clave of posiblesFiltros) {
        if ([...searchParams.keys()].some(k => k.startsWith(clave))) {
            console.log(`🧩 Filtro activo detectado: ${clave}`);
            tieneFiltros = true;
        }
    }

    if (searchParams.has("precio_min") || searchParams.has("precio_max")) {
        console.log("💰 Filtro de precio activo");
        tieneFiltros = true;
    }

    modoBusqueda = window.modoBusquedaDesdePHP || (tieneBusqueda || tieneFiltros);

    console.log("🔍 Parámetros actuales en URL:");
    searchParams.forEach((val, key) => {
        console.log(`  • ${key}: ${val}`);
    });
    console.log("🧠 Tiene búsqueda por texto:", tieneBusqueda);
    console.log("🎨 Tiene filtros activos:", tieneFiltros);
    console.log("🧪 modoBusquedaDesdePHP:", window.modoBusquedaDesdePHP);
    console.log("🧩 Confirmación final -> modoBusqueda =", modoBusqueda);
    console.log("🟢 Document ready!");

    function construirTarjeta(arte) {
        console.log("🧱 Construyendo tarjeta para:", arte.titulo);
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

    function cargarMas() {
        if (cargando) {
            console.log("⏸️ Scroll detenido. Ya se está cargando.");
            return;
        }

        cargando = true;
        $("#loading").show();

        const params = {
            offset: offset,
            limite: limite
        };


        for (const [key, value] of searchParams.entries()) {
            const cleanKey = key.replace(/\[\]$/, '');

            if (["estilos", "ilustraciones", "coloreados", "tipo_usuario"].includes(cleanKey)) {
                if (!params[cleanKey]) params[cleanKey] = [];
                if (!params[cleanKey].includes(value)) {
                    params[cleanKey].push(value.trim());
                }
            } else {
                params[cleanKey] = value.trim();
            }
        }

        if (modoBusqueda) {
            params.modoBusqueda = "true";
            console.log("🧭 Modo búsqueda ACTIVADO para scroll");
        } else {
            console.log("📢 Scroll sin modo búsqueda. Esto NO debería pasar si hay filtros.");
        }

        console.log("🚀 Enviando a cargar_mas.php con:");
        console.table(params);

        $.get("app/handlers/cargar_mas.php", params, function (data) {
            console.log("📨 Respuesta del backend:");
            console.log(data);

            if (Array.isArray(data) && data.length > 0) {
                console.log(`📥 Se recibieron ${data.length} resultados.`);
                data.forEach((item, i) => {
                    console.log(`✅ [${i}] ${item.titulo}`);
                    $("#galeria-container").append(construirTarjeta(item));
                });
                offset += data.length;
                console.log("📈 Offset actualizado a:", offset);
            } else {
                console.warn("🛑 No hay más resultados para esta búsqueda.");
            }
        }, "json")
        .fail((xhr, status, err) => {
            console.error("❌ Error AJAX:", err);
            console.warn("🧾 Estado:", status);
            console.warn("📥 Respuesta del servidor:", xhr.responseText);
        })
        .always(() => {
            cargando = false;
            $("#loading").hide();
            console.log("🔚 Finalizó el intento de carga.");
        });
    }

    $(window).scroll(() => {
        const scrollTop = $(window).scrollTop();
        const scrollHeight = $(document).height();
        const windowHeight = $(window).height();

        console.log(`📏 Scroll detectado: Top=${scrollTop} | Doc=${scrollHeight} | Ventana=${windowHeight}`);

        modoBusqueda = window.modoBusquedaDesdePHP;
        console.log("🔁 Reasignando modoBusqueda desde window =>", modoBusqueda);

        if (scrollTop + windowHeight >= scrollHeight - 100) {
            console.log("📣 Scroll activó carga. Iniciando cargarMas()");
            console.log("🧪 Estado -> cargando:", cargando, "| modoBusqueda:", modoBusqueda);
            cargarMas();
        }
    });
});

window.setModoBusqueda = function (valor) {
    modoBusqueda = valor;
    console.log("💡 setModoBusqueda ejecutado externamente. modoBusqueda =", valor);
};
