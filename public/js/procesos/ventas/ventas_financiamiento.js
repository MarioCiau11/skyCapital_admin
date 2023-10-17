 // selects de la vista
 const selectPromocion = $('#selectPromocion');
 const selectPeriodicidad = $('#selectPeriodicidad');

 // inputs de la vista
 const inputFechaContrato = $('#inputFechaContrato');
 const inputFechaIni = $('#inputFechaIni');
 const inputAnual = $('#inputAnual');
 const inputMeses = $('#inputMeses');
 const inputEnganche = $('#inputEnganche');
 const inputFechaFin = $('#inputFechaFin');
 const inputMonto = $('#inputMonto');
 const inputIVA = $('#inputIVA');
 const inputTotal = $('#inputTotalTabla');
 const inputMontoOld = $('#inputMontoOld');
 const inputIVAOld = $('#inputIVAOld');
 const inputTotalOld = $('#inputTotalTablaOld');
 const idventa = $('#inputIdVenta').val();
 //tds de la vista
 const tdInversion = $('#tdInversion');
 const tdMensualidades = $('#tdMensualidades');
 const tdFiniquito = $('#tdFiniquito');
 const tdInversionP = $('#tdInversionP');
 const tdMensualidadesP = $('#tdMensualidadesP');
 const tdFiniquitoP = $('#tdFiniquitoP');

 const tbodyInversion = $('#tbodyInversion');
 const tbodyInversion2 = $('#tbodyInversion2');
 const tablaPlanVenta = $('#tablePlanVenta tr');

 //botones de la vista
 const btnCorrida = $('#btnCorrida');
 const btnAnticipos = $('#btnAnticipos');

 //cargar lo requerido para la vista
jQuery(document).ready(function ($) {

    selectPromocion.add(selectPeriodicidad).select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });

    getPlanVenta();
    disabledCampos2();
    $('#btnGuardarVenta').on('click',function () {
        recuperarPlanVentaVal();
        recuperarCorrida();
    })
    $('#inputValorV').on('change',function () {
       let inversion = $('#tdInversionP').text() ;
       let mensualidad = $('#tdMensualidadesP').text();
       let finiquito = $('#tdFiniquitoP').text(); 
       if (inversion != '' || mensualidad != '' || finiquito != '') {
        calcularPorcentaje("tdInversionP", "tdInversion");
        calcularPorcentaje("tdMensualidadesP", "tdMensualidades");
        calcularPorcentaje("tdFiniquitoP", "tdFiniquito");

        if (mensualidad != '') {
            btnCorrida.click();
        }
       }
    });

});

inputMeses.on('keyup', function () {
    let inicioCorrida = inputFechaIni.val();
    if(inicioCorrida != ""){
    let meses = $(this).val();
    let fechaFin = moment(inicioCorrida).add(meses, 'months').format('YYYY-MM-DD');
    if(inputEstatus.val() == "SIN AFECTAR" && $("#inputIdVenta").val() == ""){
        inputFechaFin.val(fechaFin);
    }
    }
});


btnCorrida.click(function (e) {
    e.preventDefault();
    // console.log("click corrida");

    if($("#radioContado").is(':checked')){
        swal({
            title: "Corrida",
            text: "No hay corrida que calcular/procesar",
            icon: "info",
            button: "Aceptar",
        });
    }
    let periodicidad = selectPeriodicidad.val(); //periodicidad de la corrida
    let fechaIni = inputFechaIni.val(); //fecha de inicio de la corrida
    let fechaFin = inputFechaFin.val(); //fecha de finiquito de la corrida
    let meses = limpiarFormatoMoneyVal($("#inputMeses"));
    let valor = limpiarFormatoMoneyText($("#tdMensualidades"));

    if ($('#radioContado').is(':checked')) {
        meses = 1;
        valor = limpiarFormatoMoneyText($("#tdInversion"));
        // console.log(meses, valor);
        recuperarPlanVentaVal();
        recuperarCorrida();
        return;
    }
        // console.log(periodicidad, fechaIni, fechaFin, meses, valor);
    // console.log(periodicidad, fechaIni, fechaFin, meses, valor);
    if (periodicidad == "" || fechaIni == "" || fechaFin == "" || isNaN(Number(meses)) || valor == 0) {
        swal({
            title: "Error",
            text: "Faltan datos para generar la corrida",
            icon: "warning",
            button: "Aceptar",
        });
        return;
    }
    


    switch (periodicidad) {
        case 'Semanal':
            meses = meses * 4;
            break;
        case 'Trimestral':
            meses = meses / 3;  
            meses = parseInt(meses);  
            break;
        case 'Bimestral':
            meses = meses / 2;
            meses = parseInt(meses);
            break;
        case 'Anual':
            meses = meses / 12;
            meses = parseInt(meses);
            if(meses < 1){
                meses = 1;
            }
            break;
    
        default:
            break;
    }

    // console.log(periodicidad, fechaIni, fechaFin, meses, valor);
    $("#tableCorrida").empty();

    let montoTotal = 0;
    let ivaTotal = 0;
    let totalTotal = 0;
    let fecha = "";

    let montoTotalOld = 0;
    let ivaTotalOld = 0;
    let totalTotalOld = 0;
    for (let i = 0; i < meses; i++) {

        switch (periodicidad) {
            case 'Semanal':
                fecha = moment(fechaIni).add(i*7, 'days').format('YYYY-MM-DD');
                break;
            case 'Trimestral':
                fecha = moment(fechaIni).add(i*3, 'months').format('YYYY-MM-DD');
                break;
            case 'Bimestral':

                fecha = moment(fechaIni).add(i*2, 'months').format('YYYY-MM-DD');
                break;
            case 'Anual':
                fecha = moment(fechaIni).add(1, 'years').format('YYYY-MM-DD');
                break;
            default:
                fecha = moment(fechaIni ).add(i, 'months').format('YYYY-MM-DD');
                break;
        }

        let monto = 0;
        if ($("#radioRenta").is(":checked")) {
            monto = valor;
        } else {
            monto = valor / meses;
        }
        let iva = monto * 0.16;
        let total = monto + iva;

        let tr = `<tr>
                    <td style="display:none">${(i+1)}</td>
                    <td>${'Mensualidad '+(i+1)}</td>
                    <td>${fecha}</td>
                    <td>${formatoMoney(monto)}</td>
                    <td>${formatoMoney(iva)}</td>
                    <td>${formatoMoney(total)}</td>
                </tr>`;
        $("#tableCorrida").append(tr);

        let totalF = formatoMoney(total);
        let totalM = limpiarFormatoMoney(totalF);

        montoTotal = montoTotal + monto;
        ivaTotal = ivaTotal + iva;
        totalTotal = totalTotal + totalM;
        // console.log(totalTotal);

        montoTotalOld = montoTotalOld + monto;
        ivaTotalOld = ivaTotalOld + iva;
        totalTotalOld = totalTotalOld + total;
    }

    // console.log(montoTotal, ivaTotal, totalTotal);


    inputMonto.val(montoTotal);
    inputIVA.val(ivaTotal);
    inputTotal.val(totalTotal);

    if(inputMonto.val() == inputMontoOld.val() && inputIVA.val() == inputIVAOld.val() && inputTotal.val() == inputTotalOld.val()){
        swal({
            title: "Aviso",
            text: "Ya se generó la corrida con los datos actuales",
            icon: "info",
            button: "Aceptar",
        });
        return;
    }

    inputMontoOld.val(montoTotalOld);
    inputIVAOld.val(ivaTotalOld);
    inputTotalOld.val(totalTotalOld);

    recuperarPlanVentaVal();
    recuperarCorrida();

});

btnAnticipos.click(function (e) {
    e.preventDefault();
    $('#agregarAnticipo').hide();
});

function validarPorcentaje() {
    let total = 0;

    $(".porcent").each(function () {
        if (isNaN(parseFloat($(this).val()))) {
            total += 0;
        } else {
            total += parseFloat($(this).val());
        }


        if (total > 100) {
            swal({
                title: "Error",
                text: "El porcentaje no debe sobrepasar de los 100",
                icon: "warning",
                button: "Aceptar",
            });

            $("#tdFiniquitoP").val("");
            document.getElementById('tdFiniquito').innerHTML = "$0.00"; 
            $("#tdMensualidadesP").val("");
            document.getElementById('tdMensualidades').innerHTML = "$0.00";
            return false;
        }
    });
    // alert(total);
    // document.getElementById('tdTotalP').innerHTML = total;
}

inputAnual.on('keyup', function () {
    calcularPorcentaje("tdFiniquitoP", "tdFiniquito");
});

inputEnganche.on('change', function (e) {
    e.preventDefault();
    $("#tdInversionP").text($(this).val());
    calcularPorcentaje("tdInversionP", "tdInversion");
});

inputFechaFin.on('change', function (e) {
    e.preventDefault();
    $("#trFiniquito").find("td").eq(2).text($(this).val());
    // calcularPorcentaje("tdFiniquitoP", "tdFiniquito");
});


let tablaListaAnticipo = $('#tableAnticiposModal').DataTable({
    select:false,
    searching:false,
    paging: false,
    language: {
        "decimal": ",",
        "emptyTable": "No hay Anticipos disponibles en esta tabla",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
        "infoEmpty": "Mostrando 0 to 0 of 0 registros",
        "infoFiltered": "(Filtrado de _MAX_ total registros)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ registros",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Cuenta:",
        "zeroRecords": "Sin resultados encontrados",
        "paginate": {
        "first": "Primero",
        "last": "Ultimo",
        "next": "Siguiente",
        "previous": "Anterior"
        }
        },
        columnDefs:[
            {target:6,visible:false,}
        ]
});

function calcularPorcentaje(td, tdR) {

    // console.log(td, tdR);
    let valorModulo = limpiarFormatoMoneyVal($("#inputValorV"));
    let porcentaje = limpiarFormatoMoneyText($("#" + td));
    let valorMantenimientoAnual = limpiarFormatoMoneyVal($("#inputAnual"));

    if (td == "tdInversionP") {     
        validarFechaContrato();
        inputEnganche.val(porcentaje);
    }

    if (isNaN(valorModulo) || isNaN(porcentaje)) {
        //limpiar campos
        $("#" + tdR).text("$0.00");
        return;
    }

    let total = valorModulo * (porcentaje / 100);

    if(td == "tdInversionP"){
        insertarValoresTabla(total, "trInversionInicial", inputFechaContrato.val(), 'Inversión Inicial');
        
    }

    if (td == "tdFiniquitoP") {
        if (valorMantenimientoAnual > 0) {
            total = total + valorMantenimientoAnual;
        }
        insertarValoresTabla(total, "trFiniquito", inputFechaFin.val(), 'Finiquito');
    }
    $("#" + tdR).text(formatoMoney(total));

}

function agregarInversion(total) {
   
    let fecha = inputFechaContrato.val();
    
    if(fecha != ""){
    tbodyInversion.empty();
    let iva = total * 0.16;
    let totalFin = total + iva;
    let tr = `<tr>
                <td style="display:none">1</td>
                <td>Inversión Inicial</td>
                <td>${fecha}</td>
                <td>${formatoMoney(total)}</td>
                <td>${formatoMoney(iva)}</td>
                <td>${formatoMoney(totalFin)}</td>
            </tr>`;
    tbodyInversion.append(tr);
    }

    if(total == 0){
        emptyTable();
    }

}

function insertarValoresTabla(total,trDestino,fecha, concepto) {
    // console.log(total,trDestino,fecha);
    $('#'+trDestino).empty();

    let iva = total * 0.16;
    let totalFin = total + iva;
    let tds = `
                <td style="display:none">1</td>
                <td>${concepto}</td>
                <td>${fecha}</td>
                <td>${formatoMoney(total)}</td>
                <td>${formatoMoney(iva)}</td>
                <td>${formatoMoney(totalFin)}</td>`;

    $('#'+trDestino).append(tds);
}

function tipoContrato(tipo) {
    // Obtener el valor del botón de radio
    let valorRadio = tipo.value;

    if(valorRadio == "Renta"){
        $("#trPrecioInmueble").hide();
        $("#trInversionInmueble").hide();
        $("#trFiniquitoInmueble").hide();
        $("#radioContado").prop("disabled", true);
        $('#inputEngancheC').attr("readOnly", true);   
        emptyTable();

        inputEnganche.val(0);
        inputEnganche.attr("readonly", true);
        if ($('#radioContado').is(':checked')) {
            $('#radioContado').prop('checked', false);
        }
    }else{
        // $("#radioContado").prop("disabled", false); 
        $("#trPrecioInmueble").show();
        $("#trInversionInmueble").show();
        $("#trFiniquitoInmueble").show();
        $("#radioContado").prop("disabled", false);
        inputEnganche.attr("readonly", false);
        $('#inputEngancheC').attr("readOnly", false);  
    }
    
    // console.log(valorRadio);
}
function tipoEsquema(tipo){
    let valorRadio = tipo.value;
    // console.log(valorRadio);
    if (valorRadio == "Contado") {
        $("#trFiniquitoInmueble").hide();
        $("#trMensualidadesInmueble").hide();
        $('#inputMeses').attr("required", false);
        $('#inputMeses').attr("readOnly", true);
        // $('#inputEngancheC').attr("readOnly", true);
        
    }
    else if (valorRadio == "Mensualidad") {
        
        $("#trMensualidadesInmueble").show();
        $("#trFiniquitoInmueble").show();
        $('#inputMeses').attr("required", true);
        $('#inputMeses').attr("readOnly", false);
        if ($('#radioRenta').is(':checked')) {
            $("#trFiniquitoInmueble").hide();
        }
        // $('#inputEngancheC').attr("readOnly", false);
    }
    // else{
    //     $("#trFiniquitoInmueble").show();
    //     $("#trMensualidadesInmueble").show();
    //     $('#inputMeses').attr("required", true);
    //     $('#inputMeses').attr("readOnly", false);
    // }
}

function validarFechaContrato() {
    
    let fechaContrato = inputFechaContrato.val();

    if(fechaContrato == ""){
       

        swal({
            title: "Error",
            text: "Falta la fecha de contrato",
            icon: "warning",
            button: "Aceptar",
        });
        $("#tdInversionP").text('0%');
        $("#tdInversion").text('$0.00');
        return;
    }
}

function emptyTable() {
    let tr = "";
    tbodyInversion.empty();
    tbodyInversion2.empty();

    tr = `<tr id="trInversionInicial">
            <td style="display:none">1</td>
            <td>Inversión Inicial</td>
            <td></td>
            <td>$0.00</td>
            <td>$0.00</td>
            <td>$0.00</td>
        </tr>`;

    tr2 = `<tr id="trFiniquito">
        <td style="display:none">1</td>
        <td>Finiquito</td>
        <td></td>
        <td>$0.00</td>
        <td>$0.00</td>
        <td>$0.00</td>
        </tr>`;
    tbodyInversion.append(tr);
    tbodyInversion2.append(tr2);
}
function recuperarPlanVentaVal() {
    let datos = [];
    json = {
        'precioInmueble': ($("#tdPrecio").text()),
        'inversionInicial': ($("#tdInversion").text()),
        'inversionInicialPorcentaje': ($("#tdInversionP").text()),
        'mensualidades': ($("#tdMensualidades").text()),
        'mensualidadesPorcentaje': ($("#tdMensualidadesP").text()),
        'finiquito': ($("#tdFiniquito").text()),
        'finiquitoPorcentaje': ($("#tdFiniquitoP").text()),
    }
    datos.push(json);
    //  console.log(datos);
    $('#inputTablaVenta').val(JSON.stringify(datos));
    return datos;
}

function recuperarCorrida(){
    let datos = [];

    $('#trInversionInicial').each(function (indexColumn){
        let row = {
            'noMensualidad' : $(this).find('td').eq(0).text(),
            'mensualidad' : $(this).find('td').eq(1).text(),
            'fecha' : $(this).find('td').eq(2).text(),
            'monto' : $(this).find('td').eq(3).text(),
            'iva' : $(this).find('td').eq(4).text(),
            'total' : $(this).find('td').eq(5).text(),
            'totalMonto': 0,
            'totalIva': 0,
            'sumaTotal': 0,
        }
        datos.push(row);
    })

    
    $('#tableCorrida tr').each(function (indexColumn){
        let row = {
            'noMensualidad' : $(this).find('td').eq(0).text(),
            'mensualidad' : $(this).find('td').eq(1).text(),
            'fecha' : $(this).find('td').eq(2).text(),
            'monto' : $(this).find('td').eq(3).text(),
            'iva' : $(this).find('td').eq(4).text(),
            'total' : $(this).find('td').eq(5).text(),
            'totalMonto':$('#inputMonto').val(),
            'totalIva':$('#inputIVA').val(),
            'sumaTotal':$('#inputTotalTabla').val(),
        }
        datos.push(row);
    })

    $('#trFiniquito').each(function (indexColumn){
        let row = {
            'noMensualidad' : $(this).find('td').eq(0).text(),
            'mensualidad' : $(this).find('td').eq(1).text(),
            'fecha' : $(this).find('td').eq(2).text(),
            'monto' : $(this).find('td').eq(3).text(),
            'iva' : $(this).find('td').eq(4).text(),
            'total' : $(this).find('td').eq(5).text(),
            'totalMonto': 0,
            'totalIva': 0,
            'sumaTotal': 0,
        }
        datos.push(row);
    })
    $('#inputResultadoCorrida').val(JSON.stringify(datos));
    // console.log(datos);
    return datos;
}


function getPlanVenta() {
    ajaxRequest('/getPlanVenta', 'get', { idPlanVenta: idventa }, function (data) {
        try {
            if (JSON.stringify(data) === '{}') {
                // console.log('esta vacio el plan de venta');
                //verificar error de ajax
                if (data.hasOwnProperty('error')) {
                    window.location.reload();
                }
            }
            else {
                 console.log(data);
                const inversionInicial = parseFloat(data['inversionInicial'].replace(/[^0-9.-]+/g, ""));
                const finiquito = parseFloat(data['finiquito'].replace(/[^0-9.-]+/g, ""));
                $('#tdPrecio').text(formatoMoney(data['precioInmueble']));
                $('#tdInversion').text(formatoMoney(data['inversionInicial']));
                $('#tdInversionP').text(data['porcentajeInversion']);
                $('#tdMensualidades').text(formatoMoney(data['mensualidades']));
                $('#tdMensualidadesP').text(data['porcentajeMensualidades']);
                $('#tdFiniquito').text(formatoMoney(data['finiquito']));
                $('#tdFiniquitoP').text(data['porcentajeFiniquito']);

                insertarValoresTabla(inversionInicial, "trInversionInicial", inputFechaContrato.val(), 'Inversión Inicial');
                insertarValoresTabla(finiquito, "trFiniquito", inputFechaFin.val(), 'Finiquito');
                if ($('#selectMovimiento').val() != "Factura") {
                    // btnCorrida.click();
                }
            }
        } catch (error) {
            console.error('Error en la función getPlanVenta:', error);

            // Recargar la página en caso de error
            window.location.reload();
        }
    });
}


/**
 * The function disables certain input fields and table cells if the value of a specific input field is
 * 'CONCLUIDO'.
 */
function disabledCampos2() {
    
    let estado = inputEstatus.val();
    if(estado == 'CONCLUIDO' || estado == 'CANCELADO' || estado == 'PENDIENTE'){
    inputFechaContrato.attr('readonly', true);
    selectPromocion.attr('readonly', true);
    selectPeriodicidad.attr('readonly', true);
    inputFechaIni.attr('readonly', true);
    inputAnual.attr('readonly', true);
    inputMeses.attr('readonly', true);
    inputEnganche.attr('readonly', true);
    inputFechaFin.attr('readonly', true);
 

    //deshabilitar tablePlanVenta y tableCorrida
    $('#tablePlanVenta tr').each(function (indexColumn) {
        $(this).find('td').eq(1).attr('contenteditable', false);
        $(this).find('td').eq(2).attr('contenteditable', false);
        $(this).find('td').eq(3).attr('contenteditable', false);
        $(this).find('td').eq(4).attr('contenteditable', false);
    });

    btnCorrida.hide();


    }
}