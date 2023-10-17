//selects
const selectEtiqueta = $('#selectEtiqueta');
const selectVendedor = $('#selectVendedor');
//checkbox
// const checkboxes = $('#checkGroup input[type = "checkbox"]');
//inputs
const inputAsesor = $('#inputAsesor');
const enganche = $('#inputEngancheC');
const porcentajeComisionable = $('#inputComisionable');

const porcentajeAsesor = $('#inputPorcentajeAsesor');
const importeFacturaAsesor = $('#importeFacAsesor');
const netoAsesor = $('#inputNetoAsesor');


const inputReferido = $('#inputReferido');
const porcentajeReferido = $('#inputPorcentajeReferido');
const importeFacturaReferido = $('#inputFacReferido');
const netoReferido = $('#inputNetoReferido');
const formasPago = $('.formasPago');

const inputBroker = $('#inputBroker');
const porcentajeBroker = $('#inputPorcentajeBroker');
const importeFacturaBroker = $('#inputFacBroker');
const netobroker = $('#inputNetoBroker');
const sumaNeto = $('#inputSumaNeto');

const inputsNeto = $('input[type="text"].neto');
// const inputsNeto = $('input[type="text"].neto');
// console.log(inputsNeto);

jQuery(document).ready(function ($){
    $('.comision-mobile').hide();
    if (screen.width < 992 || screen.width == 992) {
        $('.comision-mobile').show();
    }
    $(window).resize(function(){
        if (screen.width <= 992 || screen.width == 992) {
            $('.comision-mobile').show();
        }else{
            $('.comision-mobile').hide();
        }
    });
    enganche.on('change',function(){
        calcularPorcentajeComisiones('inputEngancheC','inputValOperacionC','inputImporteEnganche');
    });

    porcentajeComisionable.on('change',function(){
        calcularPorcentajeComisiones('inputComisionable','inputValOperacionC','inputMontoComisionable');    
    });

    $('#inputValOperacionC').on('change',function () {
        if (enganche.val() != '0' && enganche.val() != '') {
            calcularPorcentajeComisiones('inputEngancheC','inputValOperacionC','inputImporteEnganche');
        }
        if (porcentajeComisionable.val() != '0' && porcentajeComisionable.val() != '') {
            calcularPorcentajeComisiones('inputComisionable','inputValOperacionC','inputMontoComisionable');            
        }
        if (porcentajeAsesor.val() != '0' && porcentajeAsesor.val() != '') {
            calculosPorcentaje('#inputPorcentajeAsesor', '#inputNetoAsesor', '#importeFacAsesor');
        }
        if (porcentajeBroker.val() != '0' && porcentajeBroker.val() != '') {
            calculosPorcentaje('#inputPorcentajeBroker', '#inputNetoBroker', '#inputFacBroker');
        }
        if (porcentajeBroker.val() != '0' && porcentajeBroker.val() != '') {
            calculosPorcentaje('#inputPorcentajeReferido', '#inputNetoReferido', '#inputFacReferido');    
        }
     });

  
    selectEtiqueta.add(selectVendedor).select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });

    selectVendedor.on('change',function(){
        let agenteSeleccionado = $(this).find(':selected');
        // console.log(agenteSeleccionado.text());

        if (agenteSeleccionado.text() == 'Seleccione un Vendedor') {
            inputAsesor.val('');
        }
        else{
            inputAsesor.val(agenteSeleccionado.text()); 
        }
    })

    disabledCampos3();
   
})

function calcularPorcentajeComisiones(inputFactura,inputValorOperacion,inputImporte) {
    let porcentaje = limpiarFormatoMoneyVal('#' + inputFactura);
    let valor = limpiarFormatoMoneyVal('#'+ inputValorOperacion);
    let total = 0;

    if (isNaN(valor) || isNaN(porcentaje)) {
        //limpiar campos
        $("#" + inputImporte).val("$0.00");
        return;
    }

    inputImporte === 'inputImporteEnganche' ? total = ((valor * (porcentaje/100)) * 1.16) : total = valor * (porcentaje/100);
    $('#' + inputImporte).val(total);
}


function calculosPorcentaje(lporcentaje, lneto, limporteFactura) {
    let valorOperacion = limpiarFormatoMoneyVal('#inputValOperacionC');
    let porcentaje = limpiarFormatoMoneyVal(lporcentaje);
    
    let neto = valorOperacion * (porcentaje/100);
    let importeFactura = neto * 1.16;

    $(lneto).val(neto);
    $(limporteFactura).val(importeFactura);
    calculoNetoTotal();
    calculoFacturaTotal();
    
}

function calculosNeto(lporcentaje, lneto, limporteFactura) {
    let valorOperacion = limpiarFormatoMoneyVal('#inputValOperacionC');
    let neto = limpiarFormatoMoneyVal(lneto);

    let porcentaje = (neto / valorOperacion) * 100;
    let importeFactura = neto * 1.16;

    $(lporcentaje).val(porcentaje);
    $(limporteFactura).val(importeFactura);
    calculoNetoTotal();
    calculoFacturaTotal();
}

function calculosFactura(lporcentaje, lneto, limporteFactura) {
    let valorOperacion = limpiarFormatoMoneyVal('#inputValOperacionC');
    let importeFactura = limpiarFormatoMoneyVal(limporteFactura);

    let neto = importeFactura / 1.16;
    let porcentaje = (neto / valorOperacion) * 100;

    $(lporcentaje).val(porcentaje);
    $(lneto).val(neto);
    calculoNetoTotal();
    calculoFacturaTotal();
}

function calculoNetoTotal() {
    let netoAsesor = limpiarFormatoMoneyVal('#inputNetoAsesor');
    let netoReferido = limpiarFormatoMoneyVal('#inputNetoReferido');
    let netoBroker = limpiarFormatoMoneyVal('#inputNetoBroker');

    if(isNaN(netoAsesor)){
        netoAsesor = 0;
    }
    if(isNaN(netoReferido)){
        netoReferido = 0;
    }
    if(isNaN(netoBroker)){
        netoBroker = 0;
    }

    let suma = netoAsesor + netoReferido + netoBroker;

    $('#inputSumaNeto').val(suma);
    calculoTotalComision();
}   


function calculoFacturaTotal() {
    let facturaAsesor = limpiarFormatoMoneyVal('#importeFacAsesor');
    let facturaReferido = limpiarFormatoMoneyVal('#inputFacReferido');
    let facturaBroker = limpiarFormatoMoneyVal('#inputFacBroker');

    if(isNaN(facturaAsesor)){
        facturaAsesor = 0;
    }
    if(isNaN(facturaReferido)){
        facturaReferido = 0;
    }
    if(isNaN(facturaBroker)){
        facturaBroker = 0;
    }

    let suma = facturaAsesor + facturaReferido + facturaBroker;

    $('#inputSumaImpFac').val(suma);   
    calculoTotalComision();
}


function calculoTotalComision() {
    let retencion = limpiarFormatoMoneyVal('#inputRetencion');
    let totalNeto = limpiarFormatoMoneyVal('#inputSumaNeto');

    if(isNaN(retencion)){
        retencion = 0;
    }
    if(isNaN(totalNeto)){
        totalNeto = 0;
    }

    // console.log(retencion, totalNeto);

    let total = totalNeto - retencion;
    $('#inputTotalNeto').val(total);
    
}


function disabledCampos3() {
    
    let estado = inputEstatus.val();
    if(estado == 'CONCLUIDO'){
    enganche.attr('readonly', true);
    selectEtiqueta.attr('readonly', true);
    selectVendedor.attr('readonly', true);
    porcentajeComisionable.attr('readonly', true);
    checksTipo.attr('disabled', true);
    inputsNeto.attr('readonly', true);
    inputReferido.attr('readonly', true);
    porcentajeReferido.attr('readonly', true);
    inputBroker.attr('readonly', true);
    formasPago.attr('readonly', true);




    }
}
