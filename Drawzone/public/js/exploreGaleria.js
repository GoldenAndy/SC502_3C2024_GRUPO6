$(document).ready(function () {
    let modoActivo = "recientes";

    window.exploreAleatorioIds = new Set();

    cargarMasArte(modoActivo);

    $('#exploreTabs a').on('shown.bs.tab', function (e) {
        modoActivo = $(e.target).data('modo');

        if ($('#galeria-' + modoActivo).children().length === 0) {
            window.explore[modoActivo].offset = 0;
            window.explore[modoActivo].terminado = false;

            if (modoActivo === 'aleatorio') {
                window.exploreAleatorioIds.clear();
            }

            cargarMasArte(modoActivo);
        }
    });

    $(window).on('scroll', function () {
        if (window.explore[modoActivo].cargando || window.explore[modoActivo].terminado) return;

        const distanciaFin = $(document).height() - $(window).height() - $(window).scrollTop();
        if (distanciaFin < 100) {
            cargarMasArte(modoActivo);
        }
    });

    function cargarMasArte(modo) {
        window.explore[modo].cargando = true;
        $('#loading-' + modo).show();

        const requestData = {
            modo: modo,
            offset: window.explore[modo].offset
        };

        if (modo === 'aleatorio') {
            requestData.excluir_ids = Array.from(window.exploreAleatorioIds);
        }

        $.ajax({
            url: 'app/handlers/cargar_explorar.php',
            method: 'GET',
            dataType: 'json',
            data: requestData,
            success: function (data) {
                if ($.trim(data.html) === '') {
                    window.explore[modo].terminado = true;
                } else {
                    $('#galeria-' + modo).append(data.html);

                    if (modo === 'recientes') {
                        window.explore[modo].offset += 9;
                    }

                    if (modo === 'aleatorio' && Array.isArray(data.ids)) {
                        data.ids.forEach(id => window.exploreAleatorioIds.add(id));
                        if (data.ids.length < 9) {
                            window.explore[modo].terminado = true;
                        }
                    }

                    if (data.terminado) {
                        window.explore[modo].terminado = true;
                    }
                }
            },
            complete: function () {
                $('#loading-' + modo).hide();
                window.explore[modo].cargando = false;
            },
            error: function (err) {
                console.error('Error al cargar arte:', err);
            }
        });
    }
});
