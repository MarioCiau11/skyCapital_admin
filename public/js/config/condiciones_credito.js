    const tipoCredito = jQuery('#selectTipo_condicion');
    const tipoDias = jQuery('#selectTipo_dias');
    const diasHabil = jQuery('#selectDias_habil');

jQuery(document).ready(function($){
    $('#selectEstatus, #selectTipo_condicion , #selectTipo_dias , #selectDias_habil , #selectMetodo').select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });



    if (tipoDias.val() == "Naturales") {
        diasHabil.val("").attr('readonly', true).trigger('change');
    }

    if (tipoCredito.val() === 'Contado') {
        jQuery('#diasVenci').val("").attr('readonly', true).trigger('blur');
        jQuery('#selectTipo_dias').val("").attr('readonly', true).trigger(
            'change');
        jQuery('#selectDias_habil').val("").attr('readonly', true).trigger('change');
    }

   
})

tipoCredito.on('change', function() {
    if (tipoCredito.val() == 'Contado') {
        jQuery('#diasVenci').val("").attr('readonly', true);
        jQuery('#selectTipo_dias').val("").attr('readonly', true).trigger(
            'change');
        jQuery('#selectDias_habil').val("").attr('readonly', true).trigger('change');
    } else {
        jQuery('#diasVenci, #selectTipo_dias, #selectDias_habil').attr('readonly', false);

    }
});

tipoDias.on('change', function() {
    if (tipoDias.val() == "Naturales") {
        diasHabil.val("").attr('readonly', true).trigger('change');
    } else {
        diasHabil.attr('readonly', false);
    }
});
