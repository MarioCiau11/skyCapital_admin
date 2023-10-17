    //selects
    const selectMovimiento = $('#selectMovimiento');
    const selectFormaPago = $('#selectFormaPago');
    const selectMoneda = $('#selectMoneda');
     //inputs
    const inputFolio = $('#inputFolio');
    const inputCambio = $('#inputCambio');
    const inputFechaEmision = $('#inputFechaEmision');
    const inputIdCuenta = $('#inputIdCuenta');
    const inputCuenta = $('#inputCuenta');
    const inputCuentaDestino = $('#inputCuentaDestino');
    const inputSaldoCuenta = $('#inputSaldoCuenta');
    const inputImporte = $('#inputImporte');
    const inputObservaciones = $('#inputObservaciones');
    const inputReferencia = $('#inputReferencia');
    const inputNombreCuenta = $('#inputNombreCuenta');
    const inputMonedaCuenta = $('#inputMonedaCuenta');
    const inputNombreCuentaDestino = $('#inputNombreCuentaDestino');
    const inputMonedaCuentaDestino = $('#inputMonedaCuentaDestino');
    //botones
    const btnAgregarCuentas = $('#btnAddCuenta');
    const btnAgregarCuentasDestino = $('#btnAddCuentaDestino');

jQuery(document).ready(function ($) {

    $(".tableCuentas").DataTable({
        "pageLength": 5,
        "lengthMenu": [5, 10, 25],
        responsive: true,
        paging: true,
        pagingTag: 'button',
        searching: false,
        language: language,
    });

    let cuentaSeleccionada = [];
    let cuentaDestinoSeleccionada = [];


    if (inputCuenta.val() != '') {
        ajaxRequest(
            '/getSaldoCuenta',
            'get',
            {
                cuenta : inputCuenta.val(),
                idMoneda : selectMoneda.val()
            },
            function({estatus, data}){
                if(estatus == true){
                    // console.log(data);
                    inputSaldoCuenta.val(data.saldo);
                }else{
                    inputSaldoCuenta.val('$0.00');
                }
            }
          );
    }

    selectMovimiento.add(selectFormaPago).add(selectMoneda).select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });

    selectMovimiento.on('change', function (e) {
        // console.log($(this).val());

        if($(this).val() == 'Transferencia'){
            $('.cuentaDestino').show();
            $('#inputCuentaDestino').prop('required',true);
        }else{
            $('.cuentaDestino').hide();
            $('#inputCuentaDestino').prop('required',false);
        }
    });
    const tablaCuentas = $('#tablaCuentasSelect').DataTable({
        select:true,
        language: language,
        columnDefs:[
            {targets:[4,5] ,visible:false}
        ]
    });
    const tablaCuentasDestino = $('#tablaCuentasDestinoSelect').DataTable({
        select:true,
        language: language,
        columnDefs:[
            {targets:[4,5] ,visible:false}
        ]
    });
    tablaCuentas.on('select', function(e, dt, type, indexes) {
        if (type === 'row') {
          cuentaSeleccionada = tablaCuentas.rows(indexes).data().toArray()[0];
        //   console.log(cuentaSeleccionada);
          ajaxRequest(
            '/getSaldoCuenta',
            'get',
            {
                cuenta : cuentaSeleccionada[0],
                idMoneda : selectMoneda.val()
            },
            function({estatus, data}){
                if(estatus == true){
                    // console.log(data);
                    inputSaldoCuenta.val(data.saldo);
                }else{
                    inputSaldoCuenta.val('$0.00');
                }
            }
          );

        }
    });

    tablaCuentasDestino.on('select', function(e, dt, type, indexes) {
        if (type === 'row') {
            cuentaDestinoSeleccionada = tablaCuentasDestino.rows(indexes).data().toArray()[0];
        //   console.log(cuentaDestinoSeleccionada);
        }
    });

    btnAgregarCuentas.on('click', function(e){
        e.preventDefault();
        if(cuentaSeleccionada.length > 0){

            // console.log(cuentaSeleccionada);
            inputCuenta.val(cuentaSeleccionada[0]);
            inputIdCuenta.val(cuentaSeleccionada[4]);
            inputNombreCuenta.val(cuentaSeleccionada[2]);
            inputMonedaCuenta.val(cuentaSeleccionada[5]);
            $('#modalCuentas').modal('hide');
        }
    });

    btnAgregarCuentasDestino.on('click', function(e){
        e.preventDefault();
        if(cuentaDestinoSeleccionada.length > 0){
            $('#inputCuentaDestino').val(cuentaDestinoSeleccionada[0]);
            $('#inputIdCuentaDestino').val(cuentaDestinoSeleccionada[4]);
            inputNombreCuentaDestino.val(cuentaDestinoSeleccionada[2]);
            inputMonedaCuentaDestino.val(cuentaDestinoSeleccionada[5]);
            $('#modalTableCuentasDestino').modal('hide');
        }
    });

    disabledCampos();
});

selectMoneda.on('change', function (e) {
    ajaxRequest(
        "/tipoCambio",
        "post",
        {
            _token: csrfToken,
            idMoneda: $(this).val(),
        },
        function ({ estatus, data }) {
            // console.log(data);
            if (estatus == true) {
                inputCambio.val(data.tipoCambio);
            } else {
                inputCambio.val('');
            }

        }
    );
});

function afectar() {
    event.preventDefault();
    if(validarAfectar()) {
        requestAfectar();
    }
}
function validarAfectar() {
    let estado = true;
    if (selectMoneda.val() == "") {
        mensajeError("Validación", "Debe seleccionar una moneda");
        estado = false;
        return false;
    }

    if (selectFormaPago.val() == "") {
        mensajeError("Validación", "Debe seleccionar una forma de pago");
        estado = false;
        return false;
    }

    if (inputCuenta.val() == "") {
        mensajeError("Validación", "Debe seleccionar una cuenta");
        estado = false;
        return false;
    }


    if (selectMovimiento.val() == "Transferencia") {

        if (inputCuentaDestino.val() == "") {
            mensajeError("Validación", "Debe seleccionar una cuenta destino");
            estado = false;
            return false;
        }


        if (inputCuenta.val() == inputCuentaDestino.val()) {
            mensajeError(
                "Validación",
                "No puede seleccionar la misma cuenta como destino"
            );
            estado = false;
            return false;
        }
    }

    if (inputImporte.val() == "") {
        mensajeError("Validación", "Debe ingresar un importe");
        estado = false;
        return false;
    }


    if (selectMovimiento.val() != "Ingreso") {
        if (
            limpiarFormatoMoneyVal(inputImporte) >
            limpiarFormatoMoneyVal(inputSaldoCuenta) || isNaN(limpiarFormatoMoneyVal(inputSaldoCuenta))
        ) {
            mensajeError(
                "Validación",
                "El importe no puede ser mayor al saldo de la cuenta"
            );
            estado = false;
            return false;
        }
    }

    if(selectMoneda.val() != inputMonedaCuenta.val()){
        mensajeError(
            "Validación",
            "La moneda de la cuenta no coincide con la moneda del movimiento"
        );
        estado = false;
        return false;
    }

    if (selectMovimiento.val() == "Transferencia") {
        if (selectMoneda.val() != inputMonedaCuentaDestino.val()) {
            mensajeError(
                "Validación",
                "La moneda de la cuenta destino no coincide con la moneda del movimiento"
            );
            estado = false;
            return false;
        }
    }

    return estado;
}

function requestAfectar() {
    mensajeConfirmacion(
        "¿Está seguro de afectar el movimiento?",
        "Una vez afectado no podrá realizar cambios",
        "warning",
        function () {
            mostrarLoader();
            let retrasoAleatorio = Math.floor(Math.random() * 3000) + 1000; // Entre 1000 y 3000 milisegundos
            setTimeout(() => {
            $.ajax({
                url: "/tesoreria/afectar",
                type: "post",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                },
                data: $('#form-create').serialize(),
                success: function ({ status, message, id }) {

                    if (status == true) {
                        mensajeSuccess('Afectación exitosa', message);
                        setTimeout(function () {
                            window.location.href = "/tesoreria/create?tesoreria="+id;
                        }, 1000);
                    } else {
                        mensajeError('Error', message);
                    }

                    quuitarLoader();

                },
            });      }, retrasoAleatorio);

        }
    );
}
function disabledCampos() {
    let estado = $('#inputEstatus').val();
    // console.log(estado);
    if(estado == 'CONCLUIDO' || estado == 'CANCELADO'){
        $('#selectMovimiento').attr('disabled', true);
        $('#selectFormaPago').attr('disabled', true);
        $('#selectMoneda').attr('disabled', true);
        $('#inputFolio').attr('readonly', true);
        $('#inputTipoCambio').attr('readonly', true);
        $('#inputFechaEmision').attr('readonly', true);
        $('#inputIdCuenta').attr('readonly', true);
        $('#inputCuenta').attr('readonly', true);
        $('#inputSaldoCuenta').attr('readonly', true);
        $('#inputImporte').attr('readonly', true);
        $('#inputObservaciones').attr('readonly', true);
        $('#inputReferencia').attr('readonly', true);
        $('#btnGuardarVenta').hide();
        $("#AbrirModalCuentas").prop("disabled", true);
        $("#AbrirModalCuentasDestino").prop("disabled", true);
    }
}
function eliminar(){
    // console.log($('#inputFolio').val());
    if ($('#inputFolio').val() != null) {
        mensajeError('Error', 'No se puede eliminar este registro porque ya tiene folio asignado');
        estado = false;
        return false;
    }

    mensajeConfirmacion(
        "¿Está seguro de eliminar el movimiento en estatus sin afectar?",
        "Una vez eliminado no podrá recuperarse",
        "warning",
        function () {
            $.ajax({
                url:'/tesoreria/eliminar',
                method:'post',
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                },
                data: {
                    id: $('#inputId').val()
                },
                success: function ({status, message}) {
                    if (status == true) {
                        mensajeSuccess('Ha sido eliminado', message);
                        setTimeout(function () {
                            window.location.href = "/tesoreria";
                        }, 1000);
                    } else {
                        mensajeError('Error', message);
                    }
                }
            });
        }
    )
}

function cancelar() {
    event.preventDefault();
    mensajeConfirmacion(
        "¿Está seguro de cancelar el movimiento?",
        "Una vez cancelado no podrá recuperarse",
        "warning",
        function () {
      
            $.ajax({
                url: "/tesoreria/cancelar",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                },
                data: {
                    idTesoreria: $('#inputId').val(),
                },
                success: function ({ status, mensaje }) {

                    if (status == true) {
                        mensajeSuccess('Tesoreria Cancelar', mensaje);
                        setTimeout(function () {
                            window.location.href = "/tesoreria";
                        }, 1000);
                    } else {
                        mensajeError('Error', mensaje);
                    }

                },
            });

        }
    );
}

function copiar() {
    mensajeConfirmacion(
        "¿Está seguro de copiar este movimiento?",
        "",
        "warning",
        function () {
      
            $.ajax({
                url: "/tesoreria/copiar",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                },
                data: {
                    id: $('#inputId').val(),
                },
                success: function ({ status, message, id }) {
                    mensajeSuccess('Tesoreria Copiado', message);
                    setTimeout(function () {
                        window.location.href = "/tesoreria/create?tesoreria="+id
                    }, 1000);     
                }
            });

        }
    );
}