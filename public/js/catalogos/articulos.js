jQuery(document).ready(function ($) {
    const selectCategoria = $('#selectCategoria');
    const selectGrupo = $('#selectGrupo');
    const selectEstatus = $('#selectEstatus');
    const selectTipo = $('#selectTipo');
    const selectUnidad = $('#selectUnidad');


    selectEstatus.add(selectCategoria).add(selectGrupo).add(selectTipo).add(selectUnidad).select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });
})