jQuery(document).ready(function($){
    $('#selectEstatus').select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });

    
    const viewPermisos = jQuery('.view-permiso');
    const activePermisos = jQuery('#activePermisos');
    const selecPermisos = jQuery('#selecPermisos');
    const checkBoxes = jQuery('.setCheckBox');

    activePermisos.on('change', function() {
        if (activePermisos.is(':checked')) {
            viewPermisos.show();
        } else {
            viewPermisos.hide();
        }
    });

    selecPermisos.on('change', function() {
        if (selecPermisos.is(':checked')) {
            checkBoxes.prop('checked', true);
        } else {
            checkBoxes.prop('checked', false);
        }
    });
})