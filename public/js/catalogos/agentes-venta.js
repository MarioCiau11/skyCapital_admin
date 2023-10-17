jQuery(document).ready(function ( $ ){
    const selectEstatus = $('#selectEstatus');
    const selectTipo = $('#selectTipo');
    const selectCategoria = $('#selectCategoria');
    const selectGrupo = $('#selectGrupo');

    selectEstatus.add(selectCategoria).add(selectTipo).add(selectGrupo).select2({
        minimumResultsForSearch:-1,
        width:'100%'
    });
    
});