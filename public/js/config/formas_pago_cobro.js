jQuery(document).ready(function($){
    const selectMoneda = $('#selectMoneda');
    const selectEstatus = $('#selectEstatus');

    selectEstatus.add(selectMoneda).select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });
    
})