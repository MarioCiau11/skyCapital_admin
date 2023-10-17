const selectCliente = $('#selectCliente');
const selectMovimiento = $('#selectMovimiento');
const selectCuentaD = $('#selectCuentaD');
const selectEstatus = $('#selectEstatus');
const selectFecha = $('#selectFecha');
const selectUsuario = $('#selectUsuario');
const selectMoneda = $('#selectMoneda');
const selectSucursal = $('#selectSucursal');

jQuery(document).ready(function ($) {

    selectEstatus.add(selectMovimiento).add(selectFecha).add(selectCuentaD).add(selectUsuario).add(selectMoneda).add(selectSucursal).select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });

    selectCliente.select2({
        width: '100%',
    });

    const inicioFechaInput = document.getElementById('inputFechaInicio');
    const finFechaInput = document.getElementById('inputFechaFinal');
    let inicioFechaEditado = false;

    // Agrega el evento de escucha al campo de fecha inicial
    inicioFechaInput.addEventListener('input', function() {
        const inicioFecha = new Date(this.value);
        const finFecha = new Date(finFechaInput.value);

        const inicioYear = inicioFecha.getFullYear();
        console.log({inicioYear});
        if (inicioYear < 2000) {
            inputFechaInicio.setCustomValidity("El año no puede ser menor a 2000.");
            return;
        }else{
            inputFechaInicio.setCustomValidity("");
        }
        // Si la fecha inicial es mayor que la fecha final, actualiza la fecha final
        if (inicioFecha > finFecha) {
            finFechaInput.value = this.value;
        }

        // Restringe las fechas anteriores en el campo de fecha final
        finFechaInput.min = this.value;
    
    });

    // Agrega el evento de escucha al campo de fecha final
    finFechaInput.addEventListener('input', function() {

        const inicioFecha = new Date(inicioFechaInput.value);
        const finFecha = new Date(this.value);

        const finYear = finFecha.getFullYear();
        if (finYear < 2000) {
            inputFechaFinal.setCustomValidity("El año no puede ser menor a 2000.");
            return;
        }else{
            inputFechaFinal.setCustomValidity("");
        }

        // Verifica si el campo de fecha inicial ya ha sido editado
        if (inicioFechaEditado && finFecha < inicioFecha) {
            inicioFechaInput.value = this.value;
        }

        inicioFechaEditado = true;
    });


    showDate();
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

function showDate() {
    var select = document.getElementById("selectFecha").value;
    var inputFechaFinal = document.getElementById("inputFechaFinal");

    if (select === "Rango de fechas") {
        document.getElementById("fecha-rango").style.display = "inherit";
        inputFechaInicio.required = true;
        inputFechaFinal.required = true;
        inputFechaFinal.addEventListener("input", validateYearLength);
    } else {
        document.getElementById("fecha-rango").style.display = "none";
        inputFechaInicio.required = false;
        inputFechaFinal.required = false;
        inputFechaFinal.removeEventListener("input", validateYearLength);
    }
}

function validateYearLength() {
    let inputFechaFinal = document.getElementById("inputFechaFinal");
    let year = inputFechaFinal.value;

    if (year.length > 10) {
        inputFechaFinal.setCustomValidity("El año no puede tener más de 4 dígitos.");
    } else {
        inputFechaFinal.setCustomValidity("");
    }
}


const shTable2 = jQuery('#shTable2').DataTable( {
    responsive: true,
    paging: true,
    pagingTag: 'button',
    searching: false,
    scroll: true,
    language: language,
    autoWidth: true,
    deferRender: true,
    scrollCollapse: true,
    fnDrawCallback: function (oSettings) {
        jQuery("#shTable2_paginate ul").addClass("pagination-active");
    },
    "order": [[ 10, "DESC" ]],
});

jQuery("#shCol2").click(function (event) {

    event.stopPropagation();
});

const shCol2_input = jQuery("#shCol2 input");

shCol2_input.on("click", function () {
    // Get the column API object
    const column = shTable2.column($(this).val());
    // Toggle the visibility
    if ($(this).is(":checked")) column.visible(true);
    else column.visible(false);
});

const resetTableColumns = function () {
    // ocultamos las columnas que en su checkbos esten deshabilitados
    for (let index = 0; index < shCol2_input.length; index++) {
        const column = shTable2.column(shCol2_input[index].value);
        if (!shCol2_input[index].checked) {
            column.visible(false);
        }
    }
};
resetTableColumns();