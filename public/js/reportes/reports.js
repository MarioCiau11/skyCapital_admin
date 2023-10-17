const selectProyecto = $('#selectProyecto');
const selectFecha = $('#selectFecha');
const selectMoneda = $('#selectMoneda');
const selectEstatus = $('#selectEstatus');
const selectCliente = $('#selectCliente');
const selectCliente2 = $('#selectCliente2');
const selectPlazo = $('#selectPlazo');
const selectCategoria = $('#selectCategoria'); 
const selectGrupo = $('#selectGrupo');
const selectTipoContrato = $('#selectTipoContrato');
const selectCuentaD = $('#selectCuentaD');
const selectMovimiento = $('#selectMovimiento');

jQuery(document).ready(function ($) {

    selectEstatus.add(selectProyecto).add(selectFecha).add(selectMoneda)
                 .add(selectPlazo).add(selectCategoria).add(selectGrupo)
                 .add(selectTipoContrato).add(selectCuentaD).add(selectMovimiento).add(selectCliente).add(selectCliente2).select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });

    const inicioFechaInput = document.getElementById('inputFechaInicio');
    const finFechaInput = document.getElementById('inputFechaFinal');

    inicioFechaInput.addEventListener('input', function() {
        const inicioFecha = new Date(this.value);
        const finFecha = new Date(finFechaInput.value);

        if (inicioFecha > finFecha) {
            finFechaInput.value = this.value;
        }
        finFechaInput.min = this.value;
    });

    finFechaInput.addEventListener('input', function() {
        const inicioFecha = new Date(inicioFechaInput.value);
        const finFecha = new Date(this.value);

        if (finFecha < inicioFecha) {
            inicioFechaInput.value = this.value;
        }
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

selectCliente2.on('change', function (e) {
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
        inputFechaFinal.required = true;
        inputFechaFinal.addEventListener("input", validateYearLength);
    } else {
        document.getElementById("fecha-rango").style.display = "none";
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