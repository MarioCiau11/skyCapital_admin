//selects de la vista
const selectMovimiento = $('#selectMovimiento');
const selectCondicion = $('#selectCondicion');
const selectProyecto = $('#selectProyecto');
const selectMoneda = $('#selectMoneda');
const selectModulo = $('#selectModulo');
const selectProp = $('#selectProp');
const selectCoprop = $('#selectCoprop');
const selectFormaCobro = $('#selectFormaCobro');
const selectCuentaDinero = $('#selectCuentaDinero');
const selectFormaCambio = $('#selectFormaCambio');

//inputs de la vista
const inputCambio = $('#inputCambio');
const inputValor = $('#inputValor');
const inputNivel = $('#inputNivel');
const inputMT2 = $('#inputMT2');
const inputCajones = $('#inputCajones');
const inputTipo = $('#inputTipo');
const inputValorV = $('#inputValorV');
const inputMT2V = $('#inputMT2V');
const inputModuloV = $('#inputModuloV');
const inputProp = $('#inputProp');
const inputCoprop = $('#inputCoprop-1');
const inputFechaVencimiento = $('#inputFechaVencimiento');
const inputValorC = $('#inputValOperacionC');
const inputClientes = $('#inputClientes');
const inputObservaciones = $('#inputObservaciones');
const inputAsignadoPropietario = $('#inputAsignadoPropietario');
const impAsign = $('#impAsign');
const inputArticles = $("#inputArticles");
const inputImporteCobro = $("#inputImporteCobro");
const inputCobro = $("#inputCobro");
const inputArticlesDelete = $("#inputArticlesDelete");
const inputTipoCondicion = $("#inputTipoCondicion");
const modulo = $('#moduloSeleccionado').val();
const coprops = $('#inputCoprops');
const inputFolio = $('#inputFolio');
const inputIdVenta = $('#inputIdVenta');

const radioTipoContrato = $('.radioTipoContrato');
const radioEsquema = $('.radioEsquema');
const inputEstatus = $('#inputEstatus');

const agregarArticulos = $('#agregarArticulos');
//botones de la vista
const btnGuardarVenta = $('#btnGuardarVenta');
const btnGuardarCobro = $('#btnGuardarCobro');
const btnAfectarCobro = $('#btnAfectarCobro');
const btnAgregar = $('#btnAgregar');
const btnAddClient = $('#btnAddClient');
const btnCobro = $('#btnCobro');

const addInput = $('#addInput');

// tds de la vista
const tdPrecio = $('#tdPrecio');
const DatosCoprops = [];

const checksTipo = $('.checksTipo');

const cobranzaModal = $('#cobranzaModal');

//Tablas de la vista
const tablaArticulos = $('#tableArticulos');
let tablaArticulosModal;

const tablaClientes = $('#tableClientes');
let tablaClientesModal;

let contadorArticulos = $('#cantidadArticulos').val();
// console.log(contadorArticulos);
let auxContador = 1000;

let articulosDelete = [];


let importeOld = limpiarFormatoMoneyVal($("#inputSubtotal"));
let ivaOld = limpiarFormatoMoneyVal($("#inputImpuestos"));
let totalOld = limpiarFormatoMoneyVal($("#inputTotal"));

let cambio = inputCambio.val();


let aplica;

//cargar lo requerido para la vista
jQuery(document).ready(function () {

    //quitar enter de los formularios
    $(document).on("keypress", "form", function (event) {
        return event.keyCode != 13;
    });

    selectMovimiento.add(selectCondicion).add(selectProyecto).add(selectMoneda).add(selectFormaCobro).add(selectCuentaDinero).add(selectFormaCambio).select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });
    selectMovimiento.on('change', function () {
    //   console.log(this.value);
      if (this.value == "Factura") {
        selectProyecto.attr('required', false);
        selectModulo.attr('required', false);
        selectMoneda.attr('required', false);
        radioTipoContrato.attr('required', false);
        radioEsquema.attr('required', false);
        inputFechaContrato.attr('required', false);
        selectPeriodicidad.attr('required', false);
        inputFechaIni.attr('required', false);
        inputFechaFin.attr('required', false);
        inputMeses.attr('required', false);
        inputEnganche.attr('required', false);
        $('.checksTipo').attr('required', false);
      }else{
        selectProyecto.attr('required', true);
        selectModulo.attr('required', true);
        selectMoneda.attr('required', true);
        radioTipoContrato.attr('required', true);
        radioEsquema.attr('required', true);
        inputFechaContrato.attr('required', true);
        selectPeriodicidad.attr('required', true);
        inputFechaIni.attr('required', true);
        inputFechaFin.attr('required', true);
        inputMeses.attr('required', true);
        inputEnganche.attr('required', true);
        $('.checksTipo').attr('required', true);
      }
    });

    selectModulo.add(selectProp).add(selectCoprop).select2({
        width: '100%'
    });


    tablaArticulosModal = jQuery("#tableArticulosModal").DataTable({
        select: {
            style: "multi",
        },
        language: language,
        fnDrawCallback: function (oSettings) {
            jQuery("#shTable_paginate ul").addClass("pagination-active");
        },
    });

    tablaClientesModal = jQuery("#tableClientesModal").DataTable({
        select: {
            style: "single",
        },
        language: language,
        fnDrawCallback: function (oSettings) {
            jQuery("#shTable_paginate ul").addClass("pagination-active");
        },
    });
    aplica = $("#thAplica").is(":visible");
    

    disabledCampos();

    if(aplica){
        $('.btn-delete-articulo').attr('onclick', '');

        selectMovimiento.attr('readonly', true);
        selectProyecto.attr('readonly', true);
        selectMoneda.attr('readonly', true);
        selectModulo.attr('readonly', true);
        selectProp.attr('readonly', true);
        selectCoprop.attr('readonly', true);
        inputCambio.attr('readonly', true);
        inputValor.attr('readonly', true);
        inputNivel.attr('readonly', true);
        inputMT2.attr('readonly', true);
        inputCajones.attr('readonly', true);
        inputObservaciones.attr('readonly', true);
        inputAsignadoPropietario.attr('readonly', true);
        impAsign.attr('readonly', true);
        inputTipo.attr('readonly', true);
        inputValorV.attr('readonly', true);
        inputMT2V.attr('readonly', true);
        inputModuloV.attr('readonly', true);
        inputProp.attr('readonly', true);
        inputCoprop.attr('readonly', true);
        inputFechaVencimiento.attr('readonly', true); 
        inputValorC.attr('readonly', true);
        inputClientes.attr('readonly', true);
        inputArticles.attr('readonly', true);
        inputArticlesDelete.attr('readonly', true);
    
        radioTipoContrato.attr('disabled', true);
        radioEsquema.attr('disabled', true);

        $('#itemArticulos').each(function () {
            $(this).find('td').each(function () {
                $(this).attr('contenteditable', false);

            });
        });

        $(document).off('keydown');
    
        btnAgregar.hide();
        btnGuardarVenta.hide();
        addInput.hide();
        $('#addInput').attr('onclick', '');
    }
    

});



    //Agregar copropietarios
    let campos_max = 10;   //max de 9 campos
    let x = 2;
    $('#addInput').click(function(e) {
        e.preventDefault();
        if (x < campos_max) {
            let jsonClientes = JSON.parse(inputClientes.val());
    
            $('#nCoprop').append(`
                <label class="col-lg-2 col-form-label field-label mt-3">
                    <span class="table-add float-right mb-3 mr-2"></span>
                    Co-Propietario ${x}
                </label>
                <div class="col-lg-6 mt-3">
                    <div class="col-sm-12 input-font input-correction">
                        <select id="selectCoprop-${x}" name="selectCopropArray[]" >
                            <option value="">Seleccione un copropietario</option>
                            ${Object.entries(jsonClientes).map(([key, value]) => `<option value="${key}">${value}</option>`)}
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-12 mt-3">
                    <input type="text" name="impAsignArray[]" class="input-bordered input-font currency" placeholder="Importe asignado ($)">
                </div>
            `);
            x++;
            let select = $(`#selectCoprop-${x-1}`);
            select.select2({
                width: '100%'
            });
            select.on('change', function (e) {
                let valor = {
                    id:$(this).val(),
                    nombre:$(this).find('option:selected').text()
                }
                DatosCoprops.push(valor);
                $('#inputCoprops').val(JSON.stringify(DatosCoprops));
            });
        }
    });

//Agregar copropietario al input
btnAddClient.on('click', function (e) {
    e.preventDefault();

    const rowData = tablaClientesModal.rows(".selected").data();
        
    tablaClientesModal.rows(".selected").deselect();
    jQuery(inputProp).val(rowData[0][2]);
    
});

selectProp.on('change', function (e) {
    ajaxRequest(
        "/getCliente",
        "post",
        {
            _token: csrfToken,
            idCliente: $(this).val(),
        },
        function ({ estatus, data }) {

            if( data.condicionPago != null){
                selectCondicion.val(data.condicionPago).trigger('change');
            }else{
              selectCondicion.val(null).trigger('change');
            }
        }
    );
});

//Obtener las condiciones de credito
selectCondicion.on('change', function (e) {
    ajaxRequest(
        "/getCondicion",
        "post",
        {
            _token: csrfToken,
            idCondicionesc: $(this).val(),
        },
        function ({ estatus, data }) {
            // console.log(data);
            if (estatus == true) {
                let fechaActual = moment();
                let fechaVencimiento = moment(fechaActual).add(data.diasVencimiento, "days");

                if (data.tipoDias == "Naturales" || data.diasHabiles == "Todos") {
                    fechaVencimiento = moment(fechaActual).add(data.diasVencimiento, "days");
                }
                if (data.tipoDias == "Hábiles") {
                    let habilesEmpresa = data.diasHabiles;

                    if (habilesEmpresa == "Lun-Vie") {
                        while (!fechaActual.isAfter(fechaVencimiento)) {
                            if (
                                fechaActual.isoWeekday() === 6 ||
                                fechaActual.isoWeekday() === 7
                            ) {
                                fechaVencimiento.add(1, "days");
                            }
                            fechaActual.add(1, "days");
                        }
                    }

                    if (habilesEmpresa == "Lun-Sab") {
                        while (!fechaActual.isAfter(fechaVencimiento)) {
                            if (fechaActual.isoWeekday() === 7) {
                                fechaVencimiento.add(1, "days");
                            }
                            fechaActual.add(1, "days");
                        }
                    }
                }

                inputFechaVencimiento.val(fechaVencimiento.format("YYYY-MM-DD"));
                inputTipoCondicion.val(data.tipoCondicion)


            } else {
                inputFechaVencimiento.val('');
                inputTipoCondicion.val('');
            }
        }
    );
});


//Obtener el tipo de cambio
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

//Obtener los modulos del proyecto
// selectProyecto.on('change', async function (e) {
//     await ajaxRequest(
//         "/getModulos",
//         "post",
//         {
//             _token: csrfToken,
//             idProyecto: $(this).val(),
//             estatus: inputEstatus.val(),
//             aplica: $("#thAplica").is(":visible")
//         },
//          function ({ estatus, data }) {

//             if (estatus == true) {
//                 selectModulo.children().remove().end().append('<option value="">Cargando...</option>');
//                 armarSelect2(selectModulo, data, 'Seleccione un Módulo', 'idModulo', 'clave', 'descripcion');
//                 if (modulo !== null) {
//                     selectModulo.val(modulo);
//                     selectModulo.trigger('change');
//                 }

//             } else {
//                 //limpiar el select
//                 armarSelect2(selectModulo, [], 'Sin Módulos', 'idModulo', 'clave', 'descripcion');
//             }

//         }
//     );
// });

selectProyecto.on('change', async function (e) {
    try {
        const response = await ajaxRequest(
            "/getModulos",
            "post",
            {
                _token: csrfToken,
                idProyecto: $(this).val(),
                estatus: inputEstatus.val(),
                aplica: $("#thAplica").is(":visible")
            },
            function ({ estatus, data }) {

                if (estatus == true) {
                    selectModulo.children().remove().end().append('<option value="">Cargando...</option>');
                    armarSelect2(selectModulo, data, 'Seleccione un Módulo', 'clave', 'clave', 'descripcion');
                    if (modulo !== null) {
                        selectModulo.val(modulo);
                        selectModulo.trigger('change');
                    }

                } else {
                    // Limpiar el select
                    armarSelect2(selectModulo, [], 'Sin Módulos', 'clave', 'clave', 'descripcion');
                }

            }
        );
        
        if (response && response.error) {
            // Si la petición AJAX tiene un error, recargamos la página
            location.reload();
        }
    } catch (error) {
        // Manejar el error aquí
        console.error("Error en la petición AJAX:", error);
    }
});


//Obtener los datos del modulo
selectModulo.on('change', function (e) {
    let idModulo;
    idModulo = $(this).val();

    if(inputEstatus.val() == 'PENDIENTE' || (aplica == true) ){
        idModulo = modulo;
    }
;

ajaxRequest(
        "/getModulo",
        "post",
        {
            _token: csrfToken,
            idModulo: idModulo,
        },
         function ({ estatus, data }) {

            if (estatus == true) {
                inputValor.val(data.valorOperacion / limpiarFormatoMoneyVal(inputCambio));
                inputNivel.val('Piso: ' + data.nivelPiso);
                inputMT2.val('MT2: ' + data.mt2);
                inputCajones.val('# Cajones: ' + (data.numCajones != null ? data.numCajones : 0));
                inputTipo.val('Tipo: ' + data.tipo);
                inputModuloV.val('Módulo: ' + data.clave);
                inputMT2V.val('MT2: ' + data.mt2);
                if(aplica == false){
                calculoTotales();
                }
                $('#inputEngancheC').change();
                $('#inputComisionable').change();
                
            } 

        }
    );

});

selectCuentaDinero.on('change', function (e) {
    let idCuenta;
    idCuenta = $(this).val();
    ajaxRequest(
        "/getCuenta",
        "post",
        {
            _token: csrfToken,
            idCuenta: idCuenta,
        },
         function ({ estatus, data }) {
            // console.log(data);
            console.log(data);

            $('#inputTipoBancoCambio').val(data.cuentaMoneda.idMoneda);

            if(data.cuenta.tipoCuenta == 'Banco'){
                // let totalFactura = limpiarFormatoMoneyVal($('#inputTotalFacturaCobro'));
                // $('#inputImporteCobro').val(totalFactura);
                // $('#inputTotalCobradoCobro').val(totalFactura);
                // $('#inputSaldoCobro').val(0);
                // $('#inputCambioCobro').val(0);
                selectFormaCambio.val('');
                selectFormaCambio.attr('readonly', true);
            

                // $('#inputImporteCobro').attr('readonly', true);
            }else{
                // $('#inputImporteCobro').attr('readonly', false);
                selectFormaCambio.attr('readonly', false);
            }

        }
    );

});

selectFormaCobro.on('change', function (e) {
    let idFormaPago;
    idFormaPago = $(this).val();
    ajaxRequest(
        "/getFormaPago",
        "post",
        {
            _token: csrfToken,
            idFormaPago: idFormaPago,
        },
         function ({ estatus, data }) {
            // console.log(data);
            console.log(data);

            if(data.clave != 'EFECTIVO'){
                $("#inputInformacionAdicional").attr('readonly', false);
          

            }else
            {
                $("#inputInformacionAdicional").attr('readonly', true);
                $("#inputInformacionAdicional").val('');
            }


        }
    );

});



//funcion para agregar articulos a la tabla
agregarArticulos.on('click', function () {
   
    //quitar la primera fila de la tabla
    if(contadorArticulos == 0){
    $('#controlArticulo').hide();
    }

    const rowData = tablaArticulosModal.rows(".selected").data();
    let datos = [];
    let claves = Object.keys(rowData);

    for (let i = 0; i < claves.length; i++) {
        if (!isNaN(claves[i])) {
            datos.push(rowData[claves[i]]);
        }
    }


    if (datos.length > 0) {
        for (let i = 0; i < datos.length; i++) {
            // console.log(datos[i]);
            let clave = datos[i][0];
            let articulo = datos[i][1];
            let tipo = datos[i][2];
            let precio = formatoMoney(limpiarFormatoMoney(datos[i][3]) / limpiarFormatoMoneyVal(inputCambio));
            let unidad = datos[i][4];
            let IVA = datos[i][5];
          
            $('#itemArticulos').append(`<tr id="fila-${clave}">
                <td style="display:none"></td>
                <td style="display:none"></td>
                <td contenteditable="true" oninput="buscadorArticulo(this)">${clave}</td>
                <td >${articulo}</td>
                <td contenteditable="true" class="currency" oninput="calculoImporte(this)" id="cantidad-${clave}"></td>
                <td>${unidad}</td>
                <td  contenteditable="true" class="currency" oninput="calculoImporte(this)" id="precio-${clave}">${precio}</td>
                <td>$0.00</td>
                <td>${IVA}</td>
                <td>$0.00</td>
                <td>$0.00</td>
                <td>
                <i onclick="eliminarRenglon(this)" class="fa fa-trash  btn-delete-articulo" aria-hidden="true" style="color: red; font-size: 25px; cursor: pointer;"></i>
                </td>
                <td style="display:none;"></td>
            </tr>`);

            contadorArticulos++;
        
        }
        tablaArticulosModal.rows(".selected").deselect();
        jQuery("#cantidadArticulos").val(contadorArticulos);
    }
});
      
function eliminarRenglon(id) {

    let idFila = obtenerTablaId(id);
  

    if(contadorArticulos > 0){
        contadorArticulos--;
    }

    if(idFila != 'controlArticulo' && idFila != 'controlArticulo2'){
    armarJsonArticulosDelete(idFila);  
    $('#'+idFila).remove();
    if(contadorArticulos == 0){
       $('#controlArticulo').show();
    }
    }else{
        //limpiar primer td
        $('#'+idFila+' td:nth-child(3)').text('');
        $('#'+idFila+' td:nth-child(5)').text('');
        limpiarCampos(idFila)
    }

    jQuery("#cantidadArticulos").val(contadorArticulos);
    calculoTotales();
}

function calculoImporte(id) {
    let idFila = obtenerTablaId(id);
    let cantidad = limpiarFormatoMoneyText('#'+idFila+' td:nth-child(5)');
    let precio = limpiarFormatoMoneyText('#'+idFila+' td:nth-child(7)');
    if(!isNaN(cantidad) && !isNaN(precio)){
    let importe = cantidad * precio;
    $('#'+idFila+' td:nth-child(8)').text(formatoMoney(importe));
    calculoIva(idFila);
    }else{
        $('#'+idFila+' td:nth-child(8)').text('$0.00');
        $('#'+idFila+' td:nth-child(10)').text('$0.00');
        $('#'+idFila+' td:nth-child(11)').text('$0.00');
        calculoIva(idFila);
    }
    
}

function calculoIva(idFila) {
    let importe = limpiarFormatoMoneyText('#'+idFila+' td:nth-child(8)');
    let porcentajeIva = limpiarFormatoMoneyText('#'+idFila+' td:nth-child(9)');
    let iva = importe * (porcentajeIva / 100);
    $('#'+idFila+' td:nth-child(10)').text(formatoMoney(iva));
    calculoTotal(idFila);
}

function calculoTotal(idFila) {
    let importe = limpiarFormatoMoneyText('#'+idFila+' td:nth-child(8)');
    let iva = limpiarFormatoMoneyText('#'+idFila+' td:nth-child(10)');
    let total = importe + iva;
    $('#'+idFila+' td:nth-child(11)').text(formatoMoney(total));
    calculoTotales();

}
    
function calculoTotales() {
    let valorOperacion = limpiarFormatoMoneyVal(inputValor);
    // console.log({valorOperacion});
    if(isNaN(valorOperacion)){
        valorOperacion = 0;
    }

    let importe = 0;
    let iva = 0;
    let total = 0;


    jQuery("#itemArticulos tr").each(function (row, tr) {
        importe += limpiarFormatoMoneyText(jQuery(tr).find('td:nth-child(8)'));
        iva += limpiarFormatoMoneyText(jQuery(tr).find('td:nth-child(10)'));
        total += limpiarFormatoMoneyText(jQuery(tr).find('td:nth-child(11)'));
    });

   

    if (inputEstatus.val() != "PENDIENTE") {
        $("#inputSubtotal").val(importe);
        $("#inputImpuestos").val(iva);
        $("#inputTotal").val(total + valorOperacion);
        $('#inputImporteCobro').val(total + valorOperacion);
        $('#inputTotalFacturaCobro').val(total + valorOperacion);
        // console.log(importeOld, ivaOld, totalOld);
    

        inputValorV.val(valorOperacion).trigger('change');
        inputValorC.val(valorOperacion).trigger('change');
        tdPrecio.text(formatoMoney( valorOperacion));
    } else {
        // console.log(importeOld, ivaOld, totalOld);
        $("#inputSubtotal").val(importeOld);
        $("#inputImpuestos").val(ivaOld);
        $("#inputTotal").val(totalOld);
    }
    armarJsonArticulos();
}

function buscadorArticulo(id) {
    let idFila = obtenerTablaId(id);
    let clave = $('#'+idFila+' td:nth-child(3)').text();

    ajaxRequest(
        "/getArticulo",
        "post",
        {
            _token: csrfToken,
            clave: clave,
        },
        function ({ estatus, data }) {
            if (estatus == true) {
                $('#'+idFila+' td:nth-child(4)').text(data.descripcion);
                $('#'+idFila+' td:nth-child(6)').text(data.unidad);
                $('#'+idFila+' td:nth-child(7)').text(formatoMoney(data.precio / limpiarFormatoMoneyVal(inputCambio)));
                $('#'+idFila+' td:nth-child(9)').text(formatoMoneySin(data.IVA));
                // $('#'+idFila+' td:nth-child(5)').focus();
                contadorArticulos++;
                jQuery("#cantidadArticulos").val(contadorArticulos);
        
            } else {
                $('#'+idFila+' td:nth-child(4)').text('');
                $('#'+idFila+' td:nth-child(5)').text('');

                $('#'+idFila+' td:nth-child(6)').text('');
                $('#'+idFila+' td:nth-child(7)').text('');
                $('#'+idFila+' td:nth-child(8)').text('');
                $('#'+idFila+' td:nth-child(9)').text('');
                $('#'+idFila+' td:nth-child(10)').text('');
                $('#'+idFila+' td:nth-child(11)').text('');

                $('#'+idFila+' td:nth-child(3)').focus();
                
                if(contadorArticulos > 0){
                    contadorArticulos--;
                }
            
                jQuery("#cantidadArticulos").val(contadorArticulos);
            }

        }
    );

}

function limpiarCampos(id) {
      //limpiar campos
      $('#'+id+' td:nth-child(4)').text('');
      $('#'+id+' td:nth-child(5)').text('');
      $('#'+id+' td:nth-child(6)').text('');
      $('#'+id+' td:nth-child(7)').text('$0.00');
      $('#'+id+' td:nth-child(8)').text('$0.00');
      $('#'+id+' td:nth-child(9)').text('0.00');
      $('#'+id+' td:nth-child(10)').text('$0.00');
      $('#'+id+' td:nth-child(11)').text('$0.00');
}

function obtenerTablaId(td) {
    const tabla = td.closest('table');
    const tablaId = tabla.id;
    //buscar el id del tr
    const trId = td.closest('tr').id;
    return trId;

  }

function armarJsonArticulosDelete(fila) {
 
    let id =  limpiarFormatoMoneyText($('#'+fila+' td:nth-child(13)'));
    articulosDelete.push({
        id: id,
    });
    inputArticlesDelete.val(JSON.stringify(articulosDelete));
    return articulosDelete;
}

function armarJsonArticulos() {
    
    let articulos = [];
    jQuery("#itemArticulos tr").each(function (row, tr) {
        if(contadorArticulos > 0){
            let articulo = {
        
                articulo: jQuery(tr).find('td:nth-child(3)').text(),
                descripcion: jQuery(tr).find('td:nth-child(4)').text(),
                unidad: jQuery(tr).find('td:nth-child(6)').text(),
                cantidad: limpiarFormatoMoneyText(jQuery(tr).find('td:nth-child(5)')),
                precio: limpiarFormatoMoneyText(jQuery(tr).find('td:nth-child(7)')),
                importe: limpiarFormatoMoneyText(jQuery(tr).find('td:nth-child(8)')),
                porcentajeIva: limpiarFormatoMoneyText(jQuery(tr).find('td:nth-child(9)')),
                iva: limpiarFormatoMoneyText(jQuery(tr).find('td:nth-child(10)')),
                total: limpiarFormatoMoneyText(jQuery(tr).find('td:nth-child(11)')),
                id: limpiarFormatoMoneyText(jQuery(tr).find('td:nth-child(13)')),
            };
            articulos.push(articulo);
        }
      
    });


    inputArticles.val(JSON.stringify(articulos));
    return articulos;
}

function copiar() {
    event.preventDefault();
    mensajeConfirmacion(
        "¿Está seguro de copiar este movimiento?",
        "",
        "warning",
        function () {
      
            $.ajax({
                url: "/ventas/copiar",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                },
                data: {
                    id: inputIdVenta.val(),
                },
                success: function ({ status, message, id }) {
                    // console.log(message, id);
                    mensajeSuccess('Ventas Copiado', message);
                    setTimeout(function () {
                        window.location.href = "/ventas/create?venta="+id;
                    }, 1000);     
                }
            });

        }
    );
}

function eliminar() {
    event.preventDefault();	
    estado = true;
    if(inputFolio.val() != '') {
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
                url: "/ventas/eliminar",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                },
                data: {
                    id: inputIdVenta.val(),
                },
                success: function ({ status, message }) {

                    if (status == true) {
                        mensajeSuccess('Ha sido eliminado', message);
                        setTimeout(function () {
                            window.location.href = "/ventas";
                        }, 1000);
                    } else {
                        mensajeError('Error', message);
                    }

                },
            });

        }
    );
}

function cancelar() {
    event.preventDefault();
    mensajeConfirmacion(
        "¿Está seguro de cancelar el movimiento?",
        "Una vez cancelado no podrá recuperarse",
        "warning",
        function () {
      
            $.ajax({
                url: "/ventas/cancelar",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                },
                data: {
                    idVenta: inputIdVenta.val(),
                },
                success: function ({ status, mensaje }) {

                    if (status == true) {
                        mensajeSuccess('Ventas Cancelar', mensaje);
                        setTimeout(function () {
                            window.location.href = "/ventas";
                        }, 1000);
                    } else {
                        mensajeError('Error', mensaje);
                    }

                },
            });

        }
    );
}
function afectar() {
    event.preventDefault();
    recuperarPlanVentaVal();
    recuperarCorrida();
    if (validarAfectar()) {

        if(selectMovimiento.val() == 'Factura' && inputTipoCondicion.val() == 'Contado'){
            //hacer que el btnCobro abra el modal de cobro
            cobranzaModal.attr('aria-modal', true);
            cobranzaModal.addClass('show');
            $("#cobranzaModal").modal({
                backdrop: 'static',
                keyboard: true,
                show: true,
            });
            cobranzaModal.slideToggle();
        
            return;
        }

        requestAfectar();
        
    }
}
function  requestAfectar() {
    mensajeConfirmacion(
        "¿Está seguro de afectar la venta?",
        "Una vez afectada no podrá realizar cambios",
        "warning",
        function () {
      
            mostrarLoader();

            habilitarRadios();
            let retrasoAleatorio = Math.floor(Math.random() * 3000) + 1000; // Entre 1000 y 3000 milisegundos
            setTimeout(() => {
                $.ajax({
                    url: "/ventas/afectar",
                    type: "post",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken
                    },
                    data: $('#form-create').serialize(),
                    success: function ({ status, message, id }) {
    
                        if (status == true) {
                            mensajeSuccess('Afectación exitosa', message);
                            setTimeout(function () {
                                window.location.href = "/ventas/create?venta="+id;
                            }, 1000);
                        } else {
                            mensajeError('Error', message);
                        }
    
                        quuitarLoader();
    
                    },
                });
            }, retrasoAleatorio);

           

        }
    );
}
function guardarFactura(){
    event.preventDefault();
    if (validarAfectar()) {
        recuperarCorrida();
        recuperarPlanVentaVal();
        // mostrarLoader();
        habilitarRadios();
        $.ajax({
            url: "/ventas/guardarFactura",
            type: "post",
            headers: {
                "X-CSRF-TOKEN": csrfToken
            },
            data: $('#form-create').serialize(),
            success: function({message, status,id}){
                // quuitarLoader();
                if (status == true) {
                    mensajeSuccess('Guardado', message);
                    setTimeout(function () {
                        window.location.href = "/ventas/create?venta="+id;
                    }, 1000);
                } else {
                    mensajeError('Error', message);
                }
            }
        });   
    }
}
function generarFactura() {
    event.preventDefault();
    // console.log('generar factura');

    mensajeConfirmacion(
        "¿Está seguro de generar la factura?",
        "Se generará la factura con los datos actuales",
        "warning",
        function () {
            // Código para afectar la venta aquí
            habilitarRadios();

            $.ajax({
                url: "/ventas/generarFactura",
                type: "post",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                },
                data: $('#form-create').serialize(),
                success: function ({ status, message, id }) {

                    if (status == true) {
                        mensajeSuccess('Generación', message);
                        setTimeout(function () {
                            window.location.href = "/ventas/create?venta="+id;
                        }, 1000);
                    } else {
                        mensajeError('Error', message);
                    }

                },
            });

        }
    );
}

function getAnticipos() {
    console.log(selectModulo.val());
    ajaxRequest(
        '/getMovCxC',
        'GET',
        {
            cliente: selectProp.val(),
            movimiento:'Anticipo',
            moneda: selectMoneda.val(),
            modulo: selectModulo.val(),
        },
        function ({ data }) {

            // console.log(data);
            // Convertir el objeto en un array
            const dataArray = Object.keys(data);
            if (dataArray.length != 0) {
                tablaListaAnticipo.clear();
                let total = 0;
              dataArray.forEach(element => {
               
                  let keys = Object.keys(data[element]);
                  let newRows;
                  keys.forEach(key => {
                       newRows = [
                          data[element].folioMov,
                          data[element].movimiento,
                          data[element].estatus,
                          formatoMoney(data[element].saldo),
                          data[element].cuentaDinero,
                          data[element]['money'].clave,
                          data[element].idCXC,
                          data[element]['money'].idMoneda,
                      ];

                      });
                      tablaListaAnticipo.row.add(newRows).draw();
                        total += parseFloat(data[element].saldo);
                      $('#tfoodAnticiposModal').show();
                        $('#sumaAnticipos').html(formatoMoney(total));
              });


            } else {
                tablaListaAnticipo.clear().draw();
                $('#tfoodAnticiposModal').hide();
            }
        }
    );
}

//funcion para detectar los comandos de teclado
$(document).keydown(function (e) {

    if (e.ctrlKey && e.keyCode === 40) {
        if (contadorArticulos > 0) {
            $('#itemArticulos').append(`<tr id="fila-${auxContador}">
            <td style="display:none"></td>
            <td style="display:none"></td>
            <td contenteditable="true" oninput="buscadorArticulo(this)"></td>
            <td></td>
            <td contenteditable="true" class="currency" oninput="calculoImporte(this)"></td>
            <td></td>
            <td contenteditable="true" class="currency" oninput="calculoImporte(this)"></td>
            <td>$0.00</td>
            <td>0.00</td>
            <td>$0.00</td>
            <td>$0.00</td>
            <td>
            <i onclick="eliminarRenglon(this)" class="fa fa-trash  btn-delete-articulo" aria-hidden="true" style="color: red; font-size: 25px; cursor: pointer;"></i>
            </td>
            <td style="display: none;"></td>
        </tr>`);
            $('#itemArticulos tr:last td:nth-child(3)').focus();
            auxContador++;
        }
    }

    if (e.ctrlKey && e.keyCode === 38) {
        //eliminar el ultimo tr
        if (contadorArticulos == 0) {

            //Dejar el primer renglon
            $('#itemArticulos tr:last td:nth-child(3)').focus();
            let contadorRenglones = $('#itemArticulos tr').length;

            if (contadorRenglones > 1) {
                $('#itemArticulos tr:last').remove();
            }
            calculoTotales();
        }
    }
});

function disabledCampos() {
    
    let estado = inputEstatus.val();
    if(estado == 'CONCLUIDO' || estado == 'CANCELADO' || estado == 'PENDIENTE'){
    selectMovimiento.attr('readonly', true);
    selectCondicion.attr('readonly', true);
    selectProyecto.attr('readonly', true);
    selectMoneda.attr('readonly', true);
    selectModulo.attr('readonly', true);
    selectProp.attr('readonly', true);
    selectCoprop.attr('readonly', true);
    selectCuentaDinero.attr('readonly', true);
    selectFormaCambio.attr('readonly', true);
    selectFormaCobro.attr('readonly', true);
    inputImporteCobro.attr('readonly', true);
    inputCambio.attr('readonly', true);
    inputValor.attr('readonly', true);
    inputNivel.attr('readonly', true);
    inputMT2.attr('readonly', true);
    inputCajones.attr('readonly', true);
    inputObservaciones.attr('readonly', true);
    inputObservaciones.css('resize', 'none');
    inputAsignadoPropietario.attr('readonly', true);
    impAsign.attr('readonly', true);
    inputTipo.attr('readonly', true);
    inputValorV.attr('readonly', true);
    inputMT2V.attr('readonly', true);
    inputModuloV.attr('readonly', true);
    inputProp.attr('readonly', true);
    inputCoprop.attr('readonly', true);
    inputFechaVencimiento.attr('readonly', true); 
    inputValorC.attr('readonly', true);
    inputClientes.attr('readonly', true);
    inputArticles.attr('readonly', true);
    inputArticlesDelete.attr('readonly', true);

    radioTipoContrato.attr('disabled', true);
    radioEsquema.attr('disabled', true);

    //deshabilitar funcion eliminarRenglon
    $('.btn-delete-articulo').attr('onclick', '');

    //desabilitar toda la tabla
    $('#itemArticulos tr td:nth-child(3)').attr('contenteditable', false);
    $('#itemArticulos tr td:nth-child(5)').attr('contenteditable', false);
    $('#itemArticulos tr td:nth-child(7)').attr('contenteditable', false);
    $('#itemArticulos tr td:nth-child(12)').attr('contenteditable', false);
    


    btnGuardarVenta.hide();
    $('#btnGuardarFactura').hide();
    btnAgregar.hide();
    addInput.hide();
    btnGuardarCobro.hide();
    btnAfectarCobro.hide();

    const selects = document.querySelectorAll('select[name="selectCopropArray[]"]');

    // Ahora puedes recorrer los elementos y deshabilitarlos
    selects.forEach((select) => {
    select.disabled = true;
    });


    //quitar evento de teclado
    $(document).off('keydown');

    }

    if(estado == 'POR CONFIRMAR'){
        btnGuardarVenta.show();
    }
}

function validarAfectar() {

    // console.log(inputEstatus.val(), selectMovimiento.val(), $("#thAplica").is(":visible"));
    // console.log(selectMovimiento.val());

    let estado = true;
    if (selectMovimiento.val() === null || selectMovimiento.val() === '') {
        mensajeError('Validación', 'Debe seleccionar un movimiento');
        estado = false;
        return false;
    }
    if (selectMovimiento.val() != 'Factura') {
        if (selectProyecto.val() === '') {
            mensajeError('Validación', 'Debe seleccionar un proyecto');
            estado = false;
            return false;
        }

        if (selectModulo.val() === '' || selectModulo.val() === null) {
            mensajeError('Validación', 'Debe seleccionar un módulo');
            estado = false;
            return false;
        }
    }

    if (selectProp.val() === '') {
        mensajeError('Validación', 'Debe seleccionar un propietario');
        estado = false;
        return false;
    }

    if (selectCondicion.val() === '') {
        mensajeError('Validación', 'Debe seleccionar una condición');
        estado = false;
        return false;
    }

    if (selectMovimiento.val() != 'Factura') {
        if (validarTipoContrato() === false) {
            estado = false;
            mensajeError('Validación', 'Debe seleccionar un tipo de contrato');
            return false;
        }


        if (validarEsquema() === false) {
            estado = false;
            mensajeError('Validación', 'Debe seleccionar un esquema de pago');
            return false;
        }

        if (inputMeses.val() === '') {
            if ($('#radioContado').is(':checked')) {
                return true;
            }
            estado = false;
            mensajeError('Validación', 'Debe ingresar los meses');
            return false;

        }

        if (!$("#thAplica").is(":visible")) {
            if (validarTablaCorrida() === false) {
                if ($('#radioContado').is(':checked')) {
                    return true;
                }
                estado = false;
                mensajeError('Validación', 'No se ha generado la corrida');
                return false;
            }

        }

        console.log(validarTablaPorcentaje());

        if(validarTablaPorcentaje() === false){
            estado = false;
            mensajeError('Validación', 'La suma de los porcentajes debe ser igual a 100');
            return false;
        }


        // if (validarChecks() === false) {
        //     estado = false;
        //     mensajeError('Validación', 'Debe seleccionar un tipo de comisión');
        //     return false;
        // }
    }
    if (validarInputTotal() === false) {
        estado = false;
        mensajeError('Validación', 'El total de la venta no puede ser 0');
        return false;
    }


    return estado;
}

function validarTipoContrato() {
    let checkboxes = radioTipoContrato;
    let alMenosUnoSeleccionado = false;
  
    for (let i = 0; i < checkboxes.length; i++) {
      if (checkboxes[i].checked) {
        alMenosUnoSeleccionado = true;
        break;
      }
    }
  
    if (alMenosUnoSeleccionado) {
      return true;
    } else {
    return false;
    }
}
function validarInputTotal() {
    let total = $('#inputTotal').val();
    // console.log(total);
    if (total != 0 && total != '$ 0.00') {
        return true;
    }
    else {
        return false;
    }
}

function validarEsquema() {
    let checkboxes = radioEsquema;
    let alMenosUnoSeleccionado = false;
  
    for (let i = 0; i < checkboxes.length; i++) {
      if (checkboxes[i].checked) {
        alMenosUnoSeleccionado = true;
        break;
      }
    }
  
    if (alMenosUnoSeleccionado) {
      return true;
    } else {
    return false;
    }
}


function validarChecks() {
    let checkboxes = checksTipo;
    let alMenosUnoSeleccionado = false;
  
    for (let i = 0; i < checkboxes.length; i++) {
      if (checkboxes[i].checked) {
        alMenosUnoSeleccionado = true;
        break;
      }
    }
  
    if (alMenosUnoSeleccionado) {
      return true;
    } else {
    return false;
    }
}

function validarTablaPorcentaje() {
    let total = 0;
    let estado = true;

    $("#tbodyPlanVenta tr").each(function () {
        let porcentaje = $(this).find('td').eq(1).text().replace('%', '').replace(/\s/g, '');
        let porcentajeNum = parseInt(porcentaje);

        if (!isNaN(porcentajeNum)) {
            total += porcentajeNum;
        }
    });

    if (total < 100) {
        estado = false;
    }

    return estado;
}



// function validarTablaPorcentaje() {
//     let total = 0;
//     let estado = true;
//     let arreglo = [];
//     $("#tbodyPlanVenta tr").each(function () {
//         // console.log($(this).find('td').eq(1).text());
//         let porcentaje = $(this).find('td').eq(1).text().replace('%', '');
//         //quitar espacios en blanco
//         porcentaje = porcentaje.replace(/\s/g, '');
//         //convertir a entero
//         porcentaje = parseInt(porcentaje);
//         console.log({porcentaje});
//         if(isNaN(porcentaje)){
//             porcentaje = 0;
//         }
//         total += parseFloat(porcentaje);


    
//     });
    
//     if (total < 100) {
//         estado = false;
//     }else{
//         estado = true;
//     }
//     return estado;
// }

function validarTablaCorrida() {
    let contadorRenglones = $('#tableCorrida tr').length;
    return (contadorRenglones === 0) ? false : true;
}

function habilitarRadios() {
    // Código para afectar la venta aquí
    radioTipoContrato.attr('disabled', false);
    radioEsquema.attr('disabled', false);
    checksTipo.attr('disabled', false);
}

function calculoCobro() {
    const totalFactura = limpiarFormatoMoneyVal($('#inputTotalFacturaCobro'));
    let totalCobrado = limpiarFormatoMoneyVal($('#inputTotalCobradoCobro'));
    let importe = limpiarFormatoMoneyVal($('#inputImporteCobro'));

    let saldo = totalFactura - importe;
    let cambio = 0;
    if(importe > totalFactura){
        cambio = importe - totalFactura;
    }else{
        cambio = 0;
    }

    $('#inputTotalCobradoCobro').val(importe);
    $('#inputSaldoCobro').val(saldo);
    $('#inputCambioCobro').val(cambio);

    // console.log(totalFactura, totalCobrado, importe, saldo);

}

function submitCobro() {
    habilitarRadios();
    armarJsonCobro();

    btnGuardarVenta.click();
}

function afectarCobro() {

    if(validarCobro()){
        habilitarRadios();
        armarJsonCobro();
        requestAfectar();
    }
}

function validarCobro() {
    let estado = true;


    if($('#inputImporteCobro').val() > $('#inputTotalFacturaCobro').val()){
        mensajeError('Validación', 'El importe no puede ser mayor al total de la factura');
        estado = false;
        return false;
    }

    if($('#selectCuentaDinero').val() === ''){
        mensajeError('Validación', 'Debe seleccionar una cuenta de dinero');
        estado = false;
        return false;
    }
    if($('#inputTipoBancoCambio').val() != limpiarFormatoMoneyVal(selectMoneda)){
        mensajeError('Validación', 'Debe seleccionar una cuenta de dinero con la misma moneda que el cambio');
        estado = false;
        return false;
    }

    if($('#selectFormaCobro').val() === ''){
        mensajeError('Validación', 'Debe seleccionar una forma de cobro');
        estado = false;
        return false;
    }

    
    // if($('#selectFormaCambio').val() === ''){
    //     mensajeError('Validación', 'Debe seleccionar una forma de cambio');
    //     estado = false;
    //     return false;
    // }


    if($('#inputImporteCobro').val() == '$ 0.00'){
        mensajeError('Validación', 'Debe ingresar un importe');
        estado = false;
        return false;
    }

    return estado;

}

function armarJsonCobro() {
    
    let idCobro = $('#inputIdCobro').val();
    let totalFactura = limpiarFormatoMoneyVal($('#inputTotalFacturaCobro'));
    let totalCobrado = limpiarFormatoMoneyVal($('#inputTotalCobradoCobro'));
    let importe = limpiarFormatoMoneyVal($('#inputImporteCobro'));
    let saldo = limpiarFormatoMoneyVal($('#inputSaldoCobro'));
    let cambio = limpiarFormatoMoneyVal($('#inputCambioCobro'));
    let formaCobro = $('#selectFormaCobro').val();
    let cuentaDinero = $('#selectCuentaDinero').val();
    let formaCambio = $('#selectFormaCambio').val();
    let informacionAdicional = $('#inputInformacionAdicional').val();

    let json = {
        idCobro: idCobro,
        importe: importe,
        formaCobro: formaCobro,
        cuentaDinero: cuentaDinero,
        formaCambio: formaCambio,
        informacionAdicional: informacionAdicional,
        totalFactura: totalFactura,
        totalCobrado: totalCobrado,
        cambio: cambio,
        saldo: saldo,
    }

    
    inputCobro.val(JSON.stringify(json));

    return json;
}