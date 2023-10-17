jQuery(document).ready(function(){
    $('#selectEstatus').select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });
    $('#selectRol').select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });
    $('#selectEmpresa').select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });

    const $select = jQuery("#select-multi").select2({
        placeholder: 'Seleccione uno...',
        allowClear: true
    });

    // jQuery("#shTable").on('length.dt', function (e, settings, len) {
    //     console.log(len);
      
    // }); 
    
})