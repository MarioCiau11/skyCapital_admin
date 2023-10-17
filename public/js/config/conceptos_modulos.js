$(document).ready(function () {



    $('#clave').hide();

    $('#selectEstatus,#selectModulo,#selectMovimiento').select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });

    jQuery('#selectModulo').on("change", async function (event) {
        console.log('se disparo el evento');
        $('#selectMovimiento')
            .children()
            .remove()
            .end()
            .append('<option value="" selected> Cargando...</option>');
        jQuery('#selectMovimiento').val('').trigger("change");
        await jQuery.ajax({
            url: "/getMovimientos",
            type: "GET",
            dataType: "json",
            data: {
                Modulo: jQuery('#selectModulo').val()
            },
            success: function ({ datos, estatus }) {
                if (estatus === 200) {
                    $('#selectMovimiento').children().remove();
                    $('#selectMovimiento').append('<option value="" selected> seleecione un Movimiento</option>');
                    datos.forEach((element) => {
                        $("#selectMovimiento").append(
                            `<option value="${element['value']}">${element['name']}</option>`
                        );
                    });

                    $('#selectMovimiento').val('').trigger('change');

                }
            },
            error: function ({ error, status }) {
                console.log(error, status);
            }
        });
    });

    if (window.moduloBD == 'CXC') {
        $('#clave').show();
        $('#inputClave').val(window.clave).trigger('change');
    }
    jQuery('#selectModulo').on('change', function (event) {
        $('#clave').hide();
        if (event.target.value == 'CXC') {
            $('#clave').show();
        }
    });

    $("#inputClave").autocomplete({
        minLength: 3,
        source: function (request, response) {
            $.ajax({
                url: "/getClaveProd",
                type: "GET",
                data: {
                    search: jQuery('#inputClave').val()
                },
                success: function (data) {
                    console.log(data);
                    response(data);
                }
            })
        },
        messages: {
            noResults: '',
            results: function () { }
        },
    });

})
