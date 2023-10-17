jQuery(document).ready(function ($){
    const estatus = $('#selectEstatus');
    const tipCuenta = $('#selectTipo');
    const moneda = $('#selectMoneda');
    const empresa = $('#selectEmpresa');
    const banco = $('#selectBanco');
    const inputClave = $('#inputClave');

    estatus.add(tipCuenta).add(moneda).add(banco).select2({
        minimumResultsForSearch: -1,
        width: '100%'
    })
    empresa.select2({
    width : '100%'
    });
    console.log(inputClave.val());
    if (inputClave.val() != '') {
        inputClave.attr('readonly', true);
    }

})