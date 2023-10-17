const inputFolio = $('#inputFolioMov');
const inputIdMovimiento = $('#inputIdCxC');
const selectMovimiento = $('#selectMovimiento');
const selectMoneda = $('#selectMoneda');
const selectFormaPago = $('#selectFormaPago');

//inputs de la vista
const inputCliente = $('#inputCliente');
const inputNombreCliente = $('#inputNombreCliente');
const inputCambio = $('#inputTipoCambio');
const inputObservaciones = $('#inputObservaciones');
const inputReferencia = $('#inputReferencia');
const inputImporte = $('#inputImporte');
const inputImpuesto = $('#inputImpuesto');
const inputCuenta = $('#inputCuenta');
const inputCuentaMoneda = $('#inputCuentaMoneda');
const inputTotal = $('#inputTotal');
const inputEstatus = $('#inputEstatus');
const inputProyecto = $('#inputProyecto');
const inputModulo = $('#inputModulo');
const inputAnticipo = $('#inputAnticipo');

const inputMovimientos = $('#inputMovimientos');
const inputMovimientosDelete = $("#inputMovimientosDelete");

//botones para agregar datos de los modales
const btnAgregarCliente = $('#btnAddClient');
const btnAgregarCuentas = $('#btnAddCuenta');
const btnAgregarMovimiento =$('#agregarMovimiento');    
const btnAgregarProyectos = $('#btnAddProyecto');
const btnAgregarAnticipo  = $('#agregarAnticipo');
const btnAgregarModulos = $('#btnAddModulo');
const btnGuardarMovimiento =$('#btnGuardarVenta');
//botones para verificación de modales
const btnAbrirModalAnticipos = $('#AbrirModalAnticipos');
const btnAbrirModalCuentas = $('#AbrirModalCuentas');
const btnAbrirModalMovimientos = $('.abrirModalMovimientos');
const btnAbrirModalModulos = $('#abrirModalModulos');
const modalClientes = $('#modalClientes');
const modalProyectos = $('#modalProyectos');



let clienteSeleccionado = [];
let cuentaSeleccionada = [];
let proyectoSeleccionado = [];
let moduloSeleccionado = [];
let AnticipoSeleccionado = [];
let movimientoSeleccionado = [];
let movimientosDelete = [];

let contadorMovimientos = $('#contadorMovimientos').val();
let tablaListaMovimientos, tablaListaAnticipo;

let cliente = $('#inputCliente').val();
let moneda = $('#selectMoneda').val();

// Declara un Set para almacenar las claves únicas de los movimientos ya agregados
let movimientosAgregados = new Set();

jQuery(document).ready(function($) {
    //selects de la vista
    if($('input[name="inputAplicaConsecutivo[]"]').length > 0){
    let movimientosAplica = limpiarFormatoMoneyVal($('input[name="inputAplicaConsecutivo[]"]'));

    movimientosAgregados.add(movimientosAplica);
    }

    // inputImporte.attr('readOnly', true);
    // inputImpuesto.attr('readOnly', true);
    if (selectMovimiento.val() == "Factura" || selectMovimiento.val() == "CONCLUIDO") {
       showInp();
    }

    selectMovimiento.add(selectMoneda).add(selectFormaPago).select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });

    $("#form-create").submit(function (event) {
        // event.preventDefault(); // Evitar que se envíe el formulario (opcional)
        armarJsonArticulos();
      });
      selectMovimiento.on('change', function(e){
        if (this.value == 'Anticipo'|| this.value == "Factura") {
            $('#divAuxiliar').hide();
        }
      });
    const tablaClientes = $('#tablaClientesSelect').DataTable({
        select:true,
        language: language,
        columnDefs: [
            {
                target: 3,
                visible: false,
            }
        ],
    });
    tablaListaMovimientos = $('#tableMovimientosModal').DataTable({
        select: 'multi',
        language: {
            "decimal": ",",
            "emptyTable": "Ningún Movimiento disponible en esta tabla",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 to 0 of 0 registros",
            "infoFiltered": "(Filtrado de _MAX_ total registros)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ registros",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Clave/Nombre:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
            },
        },
        columnDefs: [
            {
                target: 7,
                visible: false,
            }

        ],
        
    });
    const tablaCuentas = $('#tablaCuentasSelect').DataTable({
        select:true,
        language: language,
        columnDefs:[
            {targets:[4, 5] ,visible:false}
        ]
    });
    const tablaProyectos = $('#tablaProyectosSelect').DataTable({
        select:true,
        language: language,
    });
    const tablaModulos = $('#tablaModulosSelect').DataTable({
        select:true,
        language: {
            "decimal": ",",
            "emptyTable": "No hay Modulos disponibles del Proyecto seleccionado ",
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
            {target:2,visible:false,}
        ]
    });
     tablaListaAnticipo = $('#tableAnticiposModal').DataTable({
        select:true,
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
                {targets:[6, 7],visible:false,}
            ]
    });

    tablaClientes.on('select', function(e, dt, type, indexes) {
        if (type === 'row') {
          clienteSeleccionado = tablaClientes.rows(indexes).data().toArray()[0];
            // console.log(clienteSeleccionado);
        }
    });

    tablaCuentas.on('select', function(e, dt, type, indexes) {
        if (type === 'row') {
          cuentaSeleccionada = tablaCuentas.rows(indexes).data().toArray()[0];

        }
    });

    tablaProyectos.on('select', function(e, dt, type, indexes) {
        if (type === 'row') {
          proyectoSeleccionado = tablaProyectos.rows(indexes).data().toArray()[0];
        }
    });

    tablaModulos.on('select', function(e, dt, type, indexes) {
        if (type === 'row') {
          moduloSeleccionado = tablaModulos.rows(indexes).data().toArray()[0];
        }
    });
    tablaListaAnticipo.on('select', function(e, dt, type, indexes) {
        if (type === 'row') {
          AnticipoSeleccionado = tablaListaAnticipo.rows(indexes).data().toArray()[0];
            
        }
    });
    tablaListaMovimientos.on('select', function(e, dt, type, indexes) {
        if (type === 'row') {
          // Obtener los datos de las filas seleccionadas
          const datosSeleccionados = tablaListaMovimientos.rows(indexes).data().toArray();
      
          // Agregar cada selección al arreglo movimientoSeleccionado
          for (const seleccion of datosSeleccionados) {
            movimientoSeleccionado.push(seleccion);
          }
        }
      });

    //Validaciones
    // btnGuardarMovimiento.on('click', function(e){
    //     e.preventDefault();
    //     if (validacionMovimiento()) {
    //         $('form').submit();
    //     }
    //     else{
    //         swal({
    //             title: "Error",
    //             text: "El importe del detalle excede al saldo del anticipo",
    //             icon: "warning",
    //             button: "Aceptar",
    //         });
    //     }
    // });
    inputImporte.on('focus',function (e) {
        e.preventDefault();
        if (validacionImporte()) {
            if(selectMovimiento.val() != "Factura" && inputEstatus.val() != "CONCLUIDO"){
                // inputImporte.removeAttr('readonly');
            // inputImpuesto.removeAttr('readonly');
            }
        }
        else{
            swal({
                title: "Error",
                text: "No se ha seleccionado al cliente en el movimiento",
                icon: "warning",
                button: "Aceptar",
            });
        }
    })
    inputCliente.on('change', function(e){
        ajaxRequest(
            '/getCliente',
            'post',
            {
                _token: csrfToken,
                idCliente: $(this).val(),
            },
            function ({ data }) {
               if (data != null) {
                   inputNombreCliente.val(data.razonSocial);
               }
               else{
                swal({
                    title: "Error",
                    text: "No se ha encontrado el cliente",
                    icon: "warning",
                    button: "Aceptar",
                });
                inputCliente.val('');
                inputNombreCliente.val('');
               }
            }
        );
    });
    btnAbrirModalModulos.on('click', function(e){
        e.preventDefault();
        if (validacionProyecto()) {
            let idProyecto = $('#inputProyecto').val();
            ajaxRequest(
                "/obtenerModulos",
                "GET",
                {
                    proyecto: idProyecto,
                },
                function (data) {
                    if (data.length != 0) {
                        tablaModulos.clear();
                        data.forEach(element => {
                            let newRow = [
                                element.clave,
                                element.descripcion,
                                element.idModulo
                            ]
                            tablaModulos.row.add(newRow).draw();
                        });
                    }
                    else{
                        tablaModulos.clear().draw();
                    }
                }
            );
            btnAbrirModalModulos.attr('data-target', '#modalTableModulos');
            btnAbrirModalModulos.attr('data-toggle', 'modal');
        }
        else{
            btnAbrirModalModulos.removeAttr('data-target');
            btnAbrirModalModulos.removeAttr('data-toggle');
            let estado = $('#inputEstatus').val();
            if(estado != "CONCLUIDO" && estado != "CANCELADO" && estado != "PENDIENTE"){
            swal({
                title: "Error",
                text: "No se ha seleccionado un Proyecto",
                icon: "warning",
                button: "Aceptar",
            });
        }
        }
    });
    btnAbrirModalAnticipos.on('click', function(e){
        e.preventDefault();
        if (validacionAnticipos()) {
            btnAbrirModalAnticipos.attr('data-target', '#modalAnticiposMov');
            btnAbrirModalAnticipos.attr('data-toggle', 'modal');

            obtenerAnticipos(inputCliente.val(), selectMoneda.val());
        }
        else{
            btnAbrirModalAnticipos.removeAttr('data-target');
            btnAbrirModalAnticipos.removeAttr('data-toggle');
            swal({
                title: "Error",
                text: "No se ha seleccionado un cliente",
                icon: "warning",
                button: "Aceptar",
            });
        }
        
    });

    btnAbrirModalCuentas.on('click', function(e){
        e.preventDefault();
        // if (validacionCuentas()) {
            btnAbrirModalCuentas.attr('data-target', '#modalTableCuentas');
            btnAbrirModalCuentas.attr('data-toggle', 'modal');
        // }else{
            // btnAbrirModalCuentas.removeAttr('data-target');
            // btnAbrirModalCuentas.removeAttr('data-toggle');
            // // if($('#inputEstatus').val() == 'SIN AFECTAR'){
            //     swal({
            //         title: "Error",
            //         text: "No se ha seleccionado la forma de pago en el movimiento",
            //         icon: "warning",
            //         button: "Aceptar",
            //     });
            // }
          
        // }
    });

    btnAbrirModalMovimientos.on('click',function(e){
        e.preventDefault();

        // if (validacionAnticipos()) {
            // btnAbrirModalMovimientos.attr('data-target', '#modalMov');
            // btnAbrirModalMovimientos.attr('data-toggle', 'modal');

            
        // }
        // else{
        //     btnAbrirModalMovimientos.removeAttr('data-target');
        //     btnAbrirModalMovimientos.removeAttr('data-toggle');
        //     swal({
        //         title: "Error",
        //         text: "No se ha seleccionado un cliente",
        //         icon: "warning",
        //         button: "Aceptar",
        //     });
        // }
    });

    //Agregar datos de los modales a los inputs
    btnAgregarCliente.on('click', function(e){
        // console.log('click');
        e.preventDefault();
        if(clienteSeleccionado.length > 0){
            inputCliente.val(clienteSeleccionado[0]);
            inputNombreCliente.val(clienteSeleccionado[1]);
            //agregar datos a la Lista de Anticipos(Modal)
            // console.log(cliente, moneda);
            // obtenerAnticipos(inputCliente.val(), selectMoneda.val());
            //agregar datos a la Lista de Movimientos(Modal)

            obtenerMovimientos(inputCliente.val(), selectMoneda.val());
  
              
            $('#modalClientes').modal('hide');

            saldoCliente(inputCliente.val(), selectMoneda.val());
        
            inputImporte.val('');
            $('#inputImporteCalc').val('');
            $('#tbodyMovimientos').empty();
            $("#tbodyMovimientos").append(
                `<tr id="controlMovimientos">
                <td>
                    <select name="selectTableAplicacion" id="selectTableAplicacion">
                        <option value="Factura">Factura</option>
                    </select>
                </td>
                <td style="display: flex">
                    <div contenteditable="false" style="width: 80%" id="tdAplicaConsecutivo"></div>
                    <div>
                        <a href="" class="btn btn-sm btn-auto btn-info"  data-toggle="modal" data-target="#modalMov"><i class="fas fa-search fa-lg"></i></a>
                    </div>
                </td>
                <td contenteditable="true" class="currency" id="tdImporte"></td>
                <td id="tdDiferencia"></td>
                <td id="tdPorcentaje"></td>
                <td>
                    <i  class="fa fa-trash  btn-delete-articulo" aria-hidden="true" style="color: red; font-size: 25px; cursor: pointer;"></i>
                </td>
                <td style="display: none" id="tdSaldo"></td>
                <td style="display: none" id="tdId"></td>
                <td style="display: none" id="tdReferencia"></td> 
            </tr>`
            );
            
            
        }
    });
    
    btnAgregarCuentas.on('click', function(e){
        e.preventDefault();
        //sacar el name del select Moneda
        let moneda = $('#selectMoneda option:selected').text();
        // console.log(cuentaSeleccionada, moneda);
        // return;
        if(cuentaSeleccionada.length > 0){
            $('#inputCuenta').val(cuentaSeleccionada[0]);
            $('#inputCuentaMoneda').val(cuentaSeleccionada[5]);
            $('#inputIdCuenta').val(cuentaSeleccionada[4]);
            $('#modalCuentas').modal('hide');
        }
    });

    btnAgregarAnticipo.on('click', function(e){
        e.preventDefault();
        if(AnticipoSeleccionado.length > 0){
           
            // return false;
            $('#inputAnticipo').val(AnticipoSeleccionado[1]+'-'+AnticipoSeleccionado[0]);
            $('#inputMonedaAnticipo').val(AnticipoSeleccionado[7]);
            $('#inputIdAnticipo').val(AnticipoSeleccionado[6]);
            $('#inputAnticipoImporte').val(AnticipoSeleccionado[3]);
            $('#inputImporte').val(AnticipoSeleccionado[3]).trigger('input');
            $('#modalAnticiposMov').modal('hide');
        }
    });

    $('.modalAplica').click(function(event) {
        // Prevenir la recarga de la página
        event.preventDefault();
      });


    btnAgregarMovimiento.on("click", function (e) {
    e.preventDefault();
    if (movimientoSeleccionado.length > 0) {
        $('#controlMovimientos').hide();

        const rowData = tablaListaMovimientos.rows(".selected").data();
        let datos = [];
        let claves = Object.keys(rowData);

        for (let i = 0; i < claves.length; i++) {
        if (!isNaN(claves[i])) {
            datos.push(rowData[claves[i]]);
        }
        }

        if (datos.length > 0) {
            // console.log(datos);
        for (let i = 0; i < datos.length; i++) {
            let aplicaConsecutivo = datos[i][1];

            // Verifica si el movimiento ya ha sido agregado previamente
            if (!movimientosAgregados.has(aplicaConsecutivo)) {
            let importe = datos[i][3];
            let saldo = datos[i][4];
            let referencia = datos[i][7];

            $("#tbodyMovimientos").append(
                `<tr id="fila-${aplicaConsecutivo}">
                <td>
                    <select name="selectTableAplicacion" id="selectTableAplicacion">
                    <option value="Factura">Factura</option>
                    </select>
                </td>
                <td style="display: flex">
                    <div contenteditable="false" style="width: 80%" id="tdAplicaConsecutivo">${aplicaConsecutivo}</div>
                    <div>
                    <a href="" class="btn btn-sm btn-auto btn-info modalAplica" data-toggle="modal" data-target="#modalMov">
                        <i class="fas fa-search fa-lg"></i>
                    </a>
                    </div>
                </td>
                <td contenteditable="true" id="tdImporte" oninput="calculoDiferencia(this)">${saldo}</td>
                <td id="tdDiferencia" class="currency">$ 0.00</td>
                <td id="tdPorcentaje" class="percentage">0 %</td>
                <td>
                    <i onclick="eliminarRenglon(this)" class="fa fa-trash btn-delete-articulo" aria-hidden="true" style="color: red; font-size: 25px; cursor: pointer;"></i>
                </td>
                <td style="display: none" id="tdSaldo">${saldo}</td>
                <td style="display: none" id="tdId"></td>
                <td style="display: none" id="tdReferencia">${referencia}</td>
                </tr>`
            );

            // Agrega la clave del movimiento al Set para marcarlo como agregado
            movimientosAgregados.add(aplicaConsecutivo);
            contadorMovimientos++;
            calculoImporte();
            }

        

            $("#contadorMovimientos").val(contadorMovimientos);
            tablaListaMovimientos.rows(".selected").deselect();
            $("#modalMov").modal("hide");
            // console.log(movimientosAgregados);
        }
        }

    }
    });


    btnAgregarProyectos.on('click', function(e){
        e.preventDefault();
        if(proyectoSeleccionado.length > 0){
            $('#inputProyecto').val(proyectoSeleccionado[2]);
            $('#inputNombreProyecto').val(proyectoSeleccionado[1]);
            $('#modalCuentas').modal('hide');
        }
    });

    btnAgregarModulos.on('click', function(e){
        e.preventDefault();
        if(moduloSeleccionado.length > 0){
            $('#inputModulo').val(moduloSeleccionado[0]);
            $('#inputNombreModulo').val(moduloSeleccionado[1]);
            $('#modalCuentas').modal('hide');
        }
    });

    selectMoneda.on('change', function (e) {
        ajaxRequest(
            "/tipoCambio",
            "post",
            {
                _token: csrfToken,
                idMoneda: $(this).val(),
            },
            function ({ estatus, data }) {
                if (estatus == true) {
                    inputCambio.val(data.tipoCambio);
                } else {
                    inputCambio.val('');
                }
    
                limpiarTablaMovimientos();
            }
        );
    });


    saldoCliente($('#inputCliente').val(), $('#selectMoneda').val());

    if(cliente != '' && moneda != ''){
    //agregar datos a la Lista de Movimientos(Modal)
    obtenerMovimientos(cliente, moneda);
    }


    disabledCampos();

    

});

function validacionAnticipos(){
    let cliente = $('#inputCliente').val();
    if (cliente != '') {
        return true;
    }
    else{
        return false;
    }
}
function validacionMovimiento(){
    let saldoMovimiento = $('#inputSaldo').val();
    let importe = $('#tdImporte').text();
    if (importe > saldoMovimiento) {
        return false;    
    }else{
        return true;
    }
}

function validacionImporte(){
    let cliente = $('#inputCliente').val();
    if (cliente != '') {
        return true;
    }
    else{
        return false;
    }
}
function validacionCuentas(){
    let formaPago = $('#selectFormaPago').val();
    if(formaPago != ''){
        return true;
    }
    else{
        return false;
    }
}

function validacionProyecto(){
    let proyecto = $('#inputProyecto').val();
    if(proyecto != ''){
        return true;
    }
    else{
        return false;
    }
}

// function validarMoneda(){
//     let formaPago = $('#selectFormaPago option:selected').text();
//     let monedaCuenta = $('#inputMonedaCuenta').val();
//     if(formaPago != '' && monedaCuenta != ''){
//         formaPago != monedaCuenta ? validacion = false : validacion = true;
//         return validacion;
//     }
//     else{
//         return false;
//     }
// }
window.onload = function() {
    showInp();
  }

  function limpiarTablaMovimientos() {
    if(tablaListaMovimientos != null){
        tablaListaMovimientos.clear().draw();
    }
  }
function obtenerMovimientos(cliente, moneda) {
    ajaxRequest(
        '/getMovCxC',
        'GET',
        {
          cliente: cliente,
          movimiento: 'Factura',
          moneda: moneda,
        },
        function ({ data }) {
          const dataArray = Object.keys(data);
          if (dataArray.length != 0) {
            // console.log(data);
            tablaListaMovimientos.clear();
            dataArray.forEach(element => {
             
                let keys = Object.keys(data[element]);
                let newRows;
                keys.forEach(key => {
                     newRows = [
                        data[element].movimiento,
                        data[element].folioMov,
                        data[element].fechaVencimiento,
                        formatoMoney(data[element].total),
                        formatoMoney(data[element].saldo),
                        data[element]['money'].clave,
                        data[element]['sucursal'].nombre,
                        data[element].idCXC,
                    ];

                    });
                    tablaListaMovimientos.row.add(newRows).draw();
                
            });
          } else {
            tablaListaMovimientos.clear().draw();
          }
        }
      );
}

function obtenerAnticipos(cliente, moneda) {
    ajaxRequest(
        '/getMovCxC',
        'GET',
        {
            cliente: cliente,
            movimiento:'Anticipo',
            moneda: moneda,
        },
        function ({ data }) {
            // console.log(data);
            // Convertir el objeto en un array
            const dataArray = Object.keys(data);
            if (dataArray.length != 0) {
                tablaListaAnticipo.clear();
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
                  
              });
            } else {
                tablaListaAnticipo.clear().draw();
            }
          });
}
function saldoCliente(idCliente, idMoneda) {

    ajaxRequest(
        "/getSaldoCliente",
        "post",
        {
            _token: csrfToken,
            idMoneda: idMoneda,
            idCliente: idCliente,
        },
        function ({ estatus, data }) {
           if(estatus == true){
            // console.log(data);
            $('#inputSaldoCliente').val(data.saldo);
           }else{
            $('#inputSaldoCliente').val(0);
           }

        }
    );
}

function showInp(){
    var select = document.getElementById("selectMovimiento");
    var options = document.getElementsByTagName("option");
    let requireds = $('input[required],select[required]');
    for (let i = 0; i < requireds.length; i++) {
        requireds[i].removeAttribute('required');
    }

    getSelectValue = document.getElementById("selectMovimiento").value;
    // console.log(getSelectValue);
    if(getSelectValue=="Anticipo"){
        inputCliente.attr('required', true);
        selectMoneda.attr('required', true);
        inputCuenta.attr('required', true);
        selectFormaPago.attr('required', true);
        inputImporte.attr('required', true);
        inputImpuesto.attr('required', true);
        inputProyecto.attr('required', true);
        inputModulo.attr('required', true);
        document.getElementById("divCuenta").style.display = "block";
        document.getElementById("divAnticipo").style.display = "none";
        document.getElementById("divTabla").style.display = "none";
        document.getElementById("divProyecto").style.display = "block";
        document.getElementById("divNameProyecto").style.display = "block";
        document.getElementById("divModulo").style.display = "block";
        document.getElementById("divNameModulo").style.display = "block";
        document.getElementById("divAuxiliar").style.display = "none";
        document.getElementById("divImpuestos").style.display = "block";
        document.getElementById("divImporteTotal").style.display = "block";
    }
    else if(getSelectValue=="Aplicación"){
        inputCliente.attr('required', true);
        selectMoneda.attr('required', true);
        inputCuenta.attr('required', true);
        selectFormaPago.attr('required', true);
        inputImporte.attr('required', true);
        inputAnticipo.attr('required', true);
        document.getElementById("divCuenta").style.display = "block";
        document.getElementById("divAnticipo").style.display = "block";
        document.getElementById("divTabla").style.display = "block";
        document.getElementById("divProyecto").style.display = "none";
        document.getElementById("divNameProyecto").style.display = "none";
        document.getElementById("divModulo").style.display = "none";
        document.getElementById("divNameModulo").style.display = "none";
        document.getElementById("divAuxiliar").style.display = "block";
        document.getElementById("divImpuestos").style.display = "none";
        document.getElementById("divImporteTotal").style.display = "none";
    }
    else if(getSelectValue=="Cobro"){
        inputCliente.attr('required', true);
        selectMoneda.attr('required', true);
        inputCuenta.attr('required', true);
        selectFormaPago.attr('required', true);
        inputImporte.attr('required', true);
        document.getElementById("divCuenta").style.display = "block";
        document.getElementById("divAnticipo").style.display = "none";
        document.getElementById("divTabla").style.display = "block";
        document.getElementById("divProyecto").style.display = "none";
        document.getElementById("divNameProyecto").style.display = "none";
        document.getElementById("divModulo").style.display = "none";
        document.getElementById("divNameModulo").style.display = "none";
        document.getElementById("divAuxiliar").style.display = "block";
        document.getElementById("divImpuestos").style.display = "none";
        document.getElementById("divImporteTotal").style.display = "none";

    }else if(getSelectValue=="Factura"){
        document.getElementById("divCuenta").style.display = "block";
        document.getElementById("divAnticipo").style.display = "none";
        document.getElementById("divTabla").style.display = "none";
        document.getElementById("divProyecto").style.display = "none";
        document.getElementById("divNameProyecto").style.display = "none";
        document.getElementById("divModulo").style.display = "none";
        document.getElementById("divNameModulo").style.display = "none";
        document.getElementById("divAuxiliar").style.display = "none";
        document.getElementById("divImpuestos").style.display = "block";
        document.getElementById("divImporteTotal").style.display = "block";
    }
}


function eliminar() {
    event.preventDefault();	
    estado = true;
    if(inputFolio.val() != '') {
        mensajeError('Error', 'No se puede eliminar este registro porque ya tiene folio asignado');
        estado = false;
        return false;
    }

    // console.log(inputIdMovimiento.val());

    mensajeConfirmacion(
        "¿Está seguro de eliminar el movimiento en estatus sin afectar?",
        "Una vez eliminado no podrá recuperarse",
        "warning",
        function () {
      
            $.ajax({
                url: "/cxc/eliminar",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                },
                data: {
                    id: inputIdMovimiento.val(),
                },
                success: function ({ status, message }) {

                    if (status == true) {
                        mensajeSuccess('Ha sido eliminado', message);
                        setTimeout(function () {
                            window.location.href = "/cxc";
                        }, 1000);
                    } else {
                        mensajeError('Error', message);
                    }

                },
            });

        }
    );
}

function copiar() {
    event.preventDefault();
    mensajeConfirmacion(
        "¿Está seguro de copiar este movimiento?",
        "",
        "warning",
        function () {
      
            $.ajax({
                url: "/cxc/copiar",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                },
                data: {
                    id: inputIdMovimiento.val(),
                },
                success: function ({ status, message, id }) {
                    mensajeSuccess('Ha sido copiado', message);
                    setTimeout(function () {
                        window.location.href = "/cxc/create?cxc=" + id;
                    }, 1000);     
                }
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
                url: "/cxc/cancelar",
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                },
                data: {
                    idCxC: inputIdMovimiento.val(),
                },
                success: function ({ status, mensaje }) {

                    if (status == true) {
                        mensajeSuccess('CxC Cancelar', mensaje);
                        setTimeout(function () {
                            window.location.href = "/cxc";
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
    
    if(validarAfectar()) {
        requestAfectar();
    }

}
function  requestAfectar() {
    mensajeConfirmacion(
        "¿Está seguro de afectar el movimiento?",
        "Una vez afectado no podrá realizar cambios",
        "warning",
        function () {
            armarJsonArticulos();
      
            mostrarLoader();
            let retrasoAleatorio = Math.floor(Math.random() * 3000) + 1000; // Entre 1000 y 3000 milisegundos
            setTimeout(() => {
            $.ajax({
                url: "/cxc/afectar",
                type: "post",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                },
                data: $('#form-create').serialize(),
                success: function ({ status, message, id }) {

                    if (status == true) {
                        mensajeSuccess('Afectación exitosa', message);
                        setTimeout(function () {
                            window.location.href = "/cxc/create?cxc=" + id;
                        }, 1000);
                    } else {
                        mensajeError('Error', message);
                    }

                    quuitarLoader();

                },
            });             }, retrasoAleatorio);

        }
    );
}

function validarAfectar() {

    let estado = true;

    if(selectMovimiento.val() == '') {
        mensajeError('Validación', 'Debe seleccionar un tipo de movimiento');
        estado = false;
        return false;
    }
    if(selectMoneda.val() == '') {
        mensajeError('Validación', 'Debe seleccionar una moneda');
        estado = false;
        return false;
    }

    if(inputCliente.val() == '') {
        mensajeError('Validación', 'Debe seleccionar un cliente');
        estado = false;
        return false;
    }

    if(selectFormaPago.val() == '') {
        mensajeError('Validación', 'Debe seleccionar una forma de pago');
        estado = false;
        return false;
    }

    if(inputCuenta.val() == '') {
        mensajeError('Validación', 'Debe seleccionar una cuenta');
        estado = false;
        return false;
    }

    if(inputImporte.val() == '') {
        mensajeError('Validación', 'Debe ingresar un importe');
        estado = false;
        return false;
    }
   
    // if(inputImpuesto.val() == '') {
    //     mensajeError('Validación', 'Debe ingresar un impuesto');
    //     estado = false;
    //     return false;
    // }

    // if(inputImporteTotal.val() == '') {
    //     mensajeError('Validación', 'Debe ingresar un importe total');
    //     estado = false;
    //     return false;
    // }

    // console.log(selectMovimiento.val());
    if(selectMovimiento.val() != 'Cobro' && selectMovimiento.val() != 'Aplicación'){
    if(inputProyecto.val() == '') {
        mensajeError('Validación', 'Debe seleccionar un proyecto');
        estado = false;
        return false;
    }

    if(inputModulo.val() == '') {
        mensajeError('Validación', 'Debe seleccionar un módulo');
        estado = false;
        return false;
    }
    }

    if(selectMovimiento.val() == 'Cobro' || selectMovimiento.val() == 'Aplicación' ){
        if(contadorMovimientos == 0){
            mensajeError('Validación', 'Debe seleccionar al menos un movimiento');
            estado = false;
            return false;
        }

        if(limpiarFormatoMoneyVal(inputImporte) != limpiarFormatoMoneyVal($('#inputImporteCalc'))){
            mensajeError('Validación', 'El importe no es igual al total de los movimientos');
            estado = false;
            return false;
        }
    }

    if(selectMovimiento.val() == 'Aplicación'){

        if(inputAnticipo.val() == '') {
            mensajeError('Validación', 'Debe seleccionar un anticipo');
            estado = false;
            return false;
        }
        if(limpiarFormatoMoneyVal(inputImporte) > limpiarFormatoMoneyVal($("#inputAnticipoImporte"))){
            mensajeError('Validación', 'El importe no puede ser mayor al saldo del anticipo');
            estado = false;
            return false;
        }

        if(limpiarFormatoMoneyVal($("#inputImporteCalc")) > limpiarFormatoMoneyVal($("#inputAnticipoImporte"))){
            mensajeError('Validación', 'El importe no puede ser mayor al saldo del anticipo');
            estado = false;
            return false;
        }

        // console.log($('#selectMoneda').val(), $('#inputMonedaAnticipo').val());
        if($('#selectMoneda').val() != $('#inputMonedaAnticipo').val()){
            mensajeError('Validación', 'La moneda del anticipo seleccionado debe ser igual a la moneda del movimiento.');
            estado = false;
            return false;
        }
    }

    if($('#selectMoneda').val() != $('#inputCuentaMoneda').val()){
        mensajeError('Validación', 'La moneda de la cuenta de dinero seleccionada debe ser igual a la moneda del movimiento.');
        estado = false;
        return false;
    }

   

    return estado;
}

function generarCobro() {
    event.preventDefault();
    
    mensajeConfirmacion(
        "¿Está seguro de generar el cobro?",
        "Se generará el cobro con los datos actuales",
        "warning",
        function () {
            $.ajax({
                url: "/cxc/generarCobro",
                type: "post",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                },
                data: $('#form-create').serialize(),
                success: function ({ status, message, id }) {

                    if (status == true) {
                        mensajeSuccess('Generación', message);
                        setTimeout(function () {
                            window.location.href = "/cxc/create?cxc=" + id;
                        }, 1000);
                    } else {
                        mensajeError('Error', message);
                    }

                },
            });

        }
    );
}


function generarAplicacion() {
    event.preventDefault();
    
    mensajeConfirmacion(
        "¿Está seguro de generar la aplicación?",
        "Se generará la aplicación con los datos actuales",
        "warning",
        function () {
            $.ajax({
                url: "/cxc/generarAplicacion",
                type: "post",
                headers: {
                    "X-CSRF-TOKEN": csrfToken
                },
                data: $('#form-create').serialize(),
                success: function ({ status, message, id }) {

                    if (status == true) {
                        mensajeSuccess('Generación', message);
                        setTimeout(function () {
                            window.location.href = "/cxc/create?cxc=" + id;
                        }, 1000);
                    } else {
                        mensajeError('Error', message);
                    }

                },
            });

        }
    );
}
function igualarImporte(){
let importe = limpiarFormatoMoneyVal($('#inputImporteCalc'));
$('#inputImporte').val(importe);

}

function eliminarRenglon(id) {
    let idFila = obtenerTablaId(id);
    // console.log(idFila, contadorMovimientos);
    if(contadorMovimientos > 0){
        contadorMovimientos--;
    }
    // return false;

    if (idFila != '') {
        // Get the aplicaConsecutivo value from the row being deleted
        let aplicaConsecutivo = limpiarFormatoMoneyText($('#'+idFila).find('#tdAplicaConsecutivo'));
        // Remove the aplicaConsecutivo from the Set
        armarJsonMovimientosDelete(idFila);
        movimientosAgregados.delete(aplicaConsecutivo);
        // Remove the row from the table
        $('#'+idFila).remove();
        calculoImporte();
     
    }

    if(contadorMovimientos == 0){
        $('#controlMovimientos').show();
    }

    $("#contadorMovimientos").val(contadorMovimientos);

    // console.log(idFila, contadorMovimientos);
}
function armarJsonMovimientosDelete(fila) {
 
    let id =  limpiarFormatoMoneyText($('#'+fila+' td:nth-child(8)'));
    movimientosDelete.push({
        id: id,
    });
    inputMovimientosDelete.val(JSON.stringify(movimientosDelete));
    return movimientosDelete;
}
function armarJsonArticulos() {
    
    let movimientos = [];
    jQuery("#tbodyMovimientos tr").each(function (row, tr) {
        if(contadorMovimientos > 0){
            // console.log(tr);
            let movimiento = {
                aplica: $(tr).find('td:eq(0) select').val(),
                aplicaConsecutivo: $(tr).find('td:eq(1) #tdAplicaConsecutivo').text(),
                importe: $(tr).find('td:eq(2)').text(),
                diferencia: $(tr).find('td:eq(3)').text(),
                porcentaje: $(tr).find('td:eq(4)').text(),
                id: $(tr).find('td:eq(7)').text(),
                referencia: $(tr).find('td:eq(8)').text(),
            };
            movimientos.push(movimiento);
        }
      
    });


    inputMovimientos.val(JSON.stringify(movimientos));
    return movimientos;
}

function calcularImporteTotal(){
    let total  = 0;
    let importe = limpiarFormatoMoneyVal($('#inputImporte'));
    let impuesto = limpiarFormatoMoneyVal($('#inputImpuesto'));
    isNaN(importe) ? importe = 0 : importe = importe;
    isNaN(impuesto) ? impuesto = 0 : impuesto = impuesto;
    if (selectMovimiento.val() == 'Aplicación' || selectMovimiento.val() == 'Cobro') {
        impuesto = 0;
    }else{
        impuesto = importe * 0.16 ;
    }
    $('#inputImpuesto').val(formatoMoney(impuesto));
    total = importe + impuesto;
    // console.log(selectMovimiento.val(),impuesto,total);
    $('#inputImporteTotal').val(formatoMoney(total));
    $('#inputSaldo').val(formatoMoney(total));

}
function calcularImpuestoTotal(){
    let total  = 0;
    let importe = limpiarFormatoMoneyVal($('#inputImporte'));
    let impuesto = limpiarFormatoMoneyVal($('#inputImpuesto'));
    isNaN(importe) ? importe = 0 : importe = importe;
    isNaN(impuesto) ? impuesto = 0 : impuesto = impuesto;
    total = importe + impuesto;
    $('#inputImporteTotal').val(formatoMoney(total));
    $('#inputSaldo').val(formatoMoney(total));
    
}


function calculoDiferencia(id) {
    let idFila = obtenerTablaId(id);

    const importeInicial = limpiarFormatoMoneyText($('#' + idFila).find('#tdSaldo'));
    let importe = limpiarFormatoMoneyText($('#' + idFila).find('#tdImporte'));

    if(importe > importeInicial){
        mensajeError('Validación', 'El importe no puede ser mayor al saldo');
        $('#' + idFila).find('#tdImporte').text(formatoMoney(importeInicial));
        $('#' + idFila).find('#tdDiferencia').text(formatoMoney(0));
        $('#' + idFila).find('#tdPorcentaje').text('0%');
        calculoImporte();
        return false;
    }

    let diferencia = importeInicial - importe;
    let porcentaje = (importe / importeInicial) * 100;
    //cortar a dos decimales sin redondear
    porcentaje = Math.floor(porcentaje * 100) / 100;

    if(diferencia < 0){
        porcentaje = 0;
        diferencia = 0;
    }
    // $('#' + idFila).find('#tdImporte').text(formatoMoney(importe));
    $('#' + idFila).find('#tdDiferencia').text(formatoMoney(diferencia));
    $('#' + idFila).find('#tdPorcentaje').text(porcentaje + '%');

    calculoImporte();
 
    aplicarMascaraMoneda($('#' + idFila).find('#tdImporte'));
}

function aplicarMascaraMoneda(elemento) {

    let importe = limpiarFormatoMoneyText($(elemento));
    // console.log(importe);
    //como hacer que al escribir en el input se vaya formateando sin perder el foco
    $(elemento).focusout(function () {
        $(elemento).text(formatoMoney(importe));
    });
    
}

function calculoImporte() {
    // Inicializar la variable importe con valor 0
    let importe = 0;

    jQuery("#tbodyMovimientos tr").each(function (row, tr) {
        // verificar si el tr es controlMovimientos si es así no contar
        let importeMovimiento = $(tr).find('#tdImporte').text().replace(/[^0-9.-]+/g, "") * 1;
        importe += importeMovimiento;
    });

    // inputImporte.val(importe);
    $('#inputImporteCalc').val(importe);
    $('#inputImporteTotal').val(importe);
}

function obtenerTablaId(td) {
    const tabla = td.closest('table');
    const tablaId = tabla.id;
    //buscar el id del tr
    const trId = td.closest('tr').id;
    return trId;

  }

  function disabledCampos(){
    let estado = $('#inputEstatus').val();

    if(estado == 'CONCLUIDO' || estado == 'CANCELADO' || estado == 'PENDIENTE'){
        selectMovimiento.attr('readonly', true);
        selectMoneda.attr('readonly', true);
        inputCambio.prop('readonly', true);
        inputCliente.prop('readonly', true);
        inputImporte.prop('readonly', true);
        inputObservaciones.prop('readonly', true);
        inputReferencia.prop('readonly', true);
        selectFormaPago.attr('readonly', true);
        inputImpuesto.prop('readonly', true);
        $("#divSaldoMovimiento").show();


        $('.modalAplica').prop('disabled', true);
        //quitar el evento que recarga la pagina
        $('.modalAplica').removeAttr('data-toggle');
        $('#btnGuardarVenta').hide();


        modalClientes.prop('disabled', true);
        modalProyectos.prop('disabled', true);
        btnAbrirModalCuentas.prop('disabled', true);
        btnAbrirModalCuentas.attr('onclick', '');
        btnAbrirModalModulos.prop('disabled', true);
        $('.btn-delete-articulo').attr('onclick', '');
        $('#EqualImporte').attr('onclick', '');

        $('#tbodyMovimientos tr td:nth-child(1) select').prop('disabled', true);

        $('#tbodyMovimientos tr td:nth-child(3)').attr('contenteditable', false);
    }
  }