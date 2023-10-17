const selectCliente = $('#selectCliente');
const selectMovimiento = $('#selectMovimiento');
const selectEstatus = $('#selectEstatus');
const selectFecha = $('#selectFecha');
const selectUsuario = $('#selectUsuario');
const selectMoneda = $('#selectMoneda');
const selectSucursal = $('#selectSucursal');
const marcarTodos = $('#marcarTodos');


jQuery(document).ready(function ($) {

    selectEstatus.add(selectMovimiento).add(selectFecha).add(selectUsuario).add(selectMoneda).add(selectSucursal).select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });

    selectCliente.select2({
        width: '100%',
        
    });

    const shTable2 = jQuery('#shTable2').DataTable({
        responsive: true,
        paging: false,
        pagingTag: 'button',
        pagingType: 'numbers',
        lengthChange: false,
        searching: false,
        scroll: true,
        language: language,
        autoWidth: true,
        deferRender: true,
        scrollCollapse: true,
        fnDrawCallback: function (oSettings) {
            jQuery("#shTable2_paginate ul").addClass("pagination-active");
        },
        "order": [[2, "desc"]],
    });

    //poner el input de fecha en rango de fechas


    
    

    const inicioFechaInput = document.getElementById('inputFechaInicio');
    const finFechaInput = document.getElementById('inputFechaFinal');

    finFechaInput.min = new Date().toISOString().split('T')[0];
    // Agrega el evento de escucha al campo de fecha inicial
    inicioFechaInput.addEventListener('input', function() {
        // Obtiene las fechas como objetos Date
        const inicioFecha = new Date(this.value);
        const finFecha = new Date(finFechaInput.value);

        // Si la fecha inicial es mayor que la fecha final, actualiza la fecha final
        if (inicioFecha > finFecha) {
            finFechaInput.value = this.value;
        }

        // Restringe las fechas anteriores en el campo de fecha final
        finFechaInput.min = this.value;
    });

    // Agrega el evento de escucha al campo de fecha final
    finFechaInput.addEventListener('input', function() {
        // Obtiene las fechas como objetos Date
        const inicioFecha = new Date(inicioFechaInput.value);
        const finFecha = new Date(this.value);

        // Si la fecha final es menor que la fecha inicial, actualiza la fecha inicial
        if (finFecha < inicioFecha) {
            inicioFechaInput.value = this.value;
        }
    });
});

selectCliente.on('change', function (e) {
    ajaxRequest(
        "/getCliente",
        "post",
        {
            _token: csrfToken,
            idCliente: $(this).val(),
        },
        function ({ estatus, data }) {

        }
    );
});

marcarTodos.on('change', function (e) {
    let checkboxes = $('input[type="checkbox"][name="movimiento[]"]');

    console.log('marcarTodos');
    if ($(this).is(':checked')) {
        checkboxes.prop('checked', true);
    } else {
        checkboxes.prop('checked', false);
    }
});


function facturar(){
    event.preventDefault();
    console.log('facturar');

    if (validarFacturar()) {

        mensajeConfirmacion(
            "¿Está seguro de facturar los movimientos seleccionados?",
            "Confirmar facturación",
            "warning",
            function () {
          
                mostrarLoader();
    
                $.ajax({
                    url: "/facturacion/generar",
                    type: "post",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken
                    },
                    data: {
                        _token: csrfToken,
                        movimiento: getMovimientos(),
                    },
                    success: function ({ status, message }) {
    
                        if (status == true) {
                            mensajeSuccess('Facturación', message);
                            setTimeout(function () {
                                window.location.href = "/facturacion";
                            }, 1000);
                        } else {
                            mensajeError('Error', message);
                        }
    
                        quuitarLoader();
    
                    },
                });
    
            }
        );
        
    }
}

function validarFacturar() {

    let estado = true;

    //validar que se haya seleccionado una factura
    let checkboxes = $('input[type="checkbox"][name="movimiento[]"]:checked');
    if (checkboxes.length == 0) {
        mensajeError('Error', 'Seleccione al menos un movimiento');
        estado = false;
    }



    return estado;
}

function getMovimientos() {
    const movimientosSeleccionados = $('input[type="checkbox"][name="movimiento[]"]:checked').map(function() {
        return parseInt($(this).val()); // Parseamos el valor a número entero
    }).get();

    movimientosSeleccionados.sort(function(a, b) {
        return a - b; // Ordenamos de menor a mayor
    });

    return movimientosSeleccionados;
}
