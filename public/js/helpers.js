//Token para ajax
const  csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


jQuery(document).ready(function () {

  //Modal de confirmacion de eliminacion
  jQuery(".delete").on("click", function (e) {
    e.preventDefault();
    swal({
        title: "¿Estás seguro de dar de baja este registro?",
        text: "Esta operación dará de baja este registro ",
        icon: "warning",
        buttons: {
            cancel: "CANCELAR",
            confirm: "OK",
        },
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            jQuery(this).parent().submit();
        }
    });
});

jQuery(".delete2").on("click", function (e) {
    e.preventDefault();
    swal({
        title: "¿Estás seguro de dar de baja este registro?",
        text: "Esta operación dará de baja este registro ",
        icon: "warning",
        buttons: {
            cancel: "CANCELAR",
            confirm: "OK",
        },
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            jQuery(this).parent().submit();
        }
    });
});


const shTable = jQuery("#shTable").DataTable({
    responsive: true,
    paging: true,
    pagingTag: 'button',
    searching: false,
    scroll: true,
    language: language,
    autoWidth: true,
    deferRender: true,
    scrollCollapse: true,
    fnDrawCallback: function (oSettings) {
        jQuery("#shTable_paginate ul").addClass("pagination-active");
    },
    // "aaSorting": [[ 17, "asc" ]]
});



    // Show/Hide Columns Dropdown
    jQuery("#shCol").click(function (event) {
        
        event.stopPropagation();
    });

    const shCol_input = jQuery("#shCol input");

    shCol_input.on("click", function () {
        // Get the column API object
        const column = shTable.column($(this).val());
        // Toggle the visibility
        if ($(this).is(":checked")) column.visible(true);
        else column.visible(false);
    });

    const resetTableColumns = function () {
        // ocultamos las columnas que en su checkbos esten deshabilitados
        for (let index = 0; index < shCol_input.length; index++) {
            const column = shTable.column(shCol_input[index].value);
            if (!shCol_input[index].checked) {
                column.visible(false);
            }
        }
    };
    resetTableColumns();

});

//funcion global para usar ajax
async function ajaxRequest(url, type, data, callback) {
    await jQuery.ajax({
        url: url,
        type: type,
        data: data,
        success: function (data) {
            callback(data);
        }
    });
}


function armarSelect2(id, data, placeholder, value, text, text2 = null) {
    jQuery(id).html("");
    //agregar efecto cargando mientras se arma el select
    


    jQuery(id).append(
        `<option value="">${placeholder}</option>`
    );
    for (let i = 0; i < data.length; i++) {
        jQuery(id).append(
            `<option value="${data[i][value]}">${data[i][text]} ${'-'+data[i][text2]}</option>`
            // `<option value="${data[i].id}">${data[i].nombre}-${data[i][text2]}</option>`
        );
    }
    jQuery(id).trigger("change");
}

function armarDataTable (id,pagination,searching,select,ordering){
    let Tabla = $('#'+id).DataTable({
        responsive: true,
        paging: pagination,
        searching: searching,
        select: select,
        ordering: ordering,
    });
    return Tabla;
}

function limpiarFormatoMoney(id) {
    const valor = id.replace(/[^0-9.-]+/g, "");
    //coveritir a numero
    const numero = parseFloat(valor);
    return numero;
}

//funcion para limpiar y quitar formato money
function limpiarFormatoMoneyText(id) {
    const valor = $(id).text().replace(/[^0-9.-]+/g, "");
    //coveritir a numero
    const numero = parseFloat(valor);
    return numero;
}

function limpiarFormatoMoneyVal(id) {
    const valor = $(id).val().replace(/[^0-9.-]+/g, "");
    //coveritir a numero
    const numero = parseFloat(valor);
    return numero;
}

//funcion para dar formato a los numeros
function formatoMoney(number) {
    let valor = currency(number, { separator: ",", precision: 2, symbol: "$"}).format();
    return valor;
}

function formatoMoneySin(number) {
    let valor = currency(number, { separator: ",", precision: 2, symbol: ""}).format();
    return valor;
}

function formatoPorcentaje(number) {
    let valor = currency(number, { separator: ",", precision: 1, symbol: ""}).format();
    return valor;
}

//formatos de inputs
Inputmask({
    mask: "(999) 999-9999",
    removeMaskOnSubmit: true,
    clearMaskOnLostFocus: true,
  }).mask('.cellphone');
  
  Inputmask({
    mask: "(999) 999 9[9][9][9]",
    removeMaskOnSubmit: true,
    clearMaskOnLostFocus: true,
  }).mask('.phone');
  
  Inputmask({
    'alias': 'currency',
    removeMaskOnSubmit: true,
    clearMaskOnLostFocus: true,
    rightAlign: false,
    onKeyDown: function(event, buffer, caretPos, opts) {
        // keyCode 189 corresponds to the "-" key
        if (event.keyCode === 189) {
          event.preventDefault();
        }

        //verificar que no se oprima el - del teclado numerico
        if (event.keyCode === 109) {
            event.preventDefault();
        }
      },

  }).mask('.currency');
  
  Inputmask({
    alias: 'percentage',
    removeMaskOnSubmit: true,
    clearMaskOnLostFocus: true,
    rightAlign: false,
    oncomplete: function () {
      // Obtén el valor del campo de entrada sin el símbolo de porcentaje
      let inputValue = this.inputmask.unmaskedvalue();
      
      // Convierte el valor en un número decimal
      let numericValue = parseFloat(inputValue) || 0;
  
      // Valida si el valor es mayor que 100 y ajusta el valor si es necesario
      if (numericValue > 100) {
        this.inputmask.setValue('100%');
      }
    }
  }).mask('.percentage');
  
  Inputmask({
    'alias': 'decimal',
    removeMaskOnSubmit: true,
    clearMaskOnLostFocus: true,
    rightAlign: false,
  }).mask('.decimal');
  
  Inputmask({
    'alias': 'percentage',
    digits: 4,
    removeMaskOnSubmit: true,
    clearMaskOnLostFocus: true,
    rightAlign: false,
  }).mask('.percentage-retention');
  
  Inputmask({
    'alias': 'integer',
    min: 0,
    allowMinus: false,
    removeMaskOnSubmit: true,
    clearMaskOnLostFocus: true,
    rightAlign: false,
  }).mask('.integer');
  


  function mensajeConfirmacion(title, text, icon, callback) {
    swal({
        title: title,
        text: text,
        icon: icon,
        buttons: true,
        dangerMode: true,
        buttons: {
            cancel: "CANCELAR",
            confirm: "OK",
        },
    }).then((willDelete) => {
        if (willDelete) {
            callback();
        }
    });
  }

  function mensajeSuccess(title, text) {
    swal({
        title: title,
        text: text,
        icon: "success",
        button: "Aceptar",
    });
  }

  function mensajeError(title, text) {
    swal({
        title: title,
        text: text,
        icon: "error",
        button: "Aceptar",
    });
  }
  mensajeValidacion = function (title, text) {
    swal({
        title: title,
        text: text,
        icon: "warning",
        button: "Aceptar",
    });
  }
    
  function mostrarLoader(){
    $('.preloader').show();
    $('.spinner').show();
    $('.spinner').removeClass('load-done');
    // $('.spinner').addClass('spinner-round');
  }

  function quuitarLoader() {
    $('.preloader').hide();
    $('.spinner').hide();
  }
  
window.onload = function() {

    let altoPagina = $(document).height();
    
    let contenedor = jQuery(".menu-mega");
    // sacar alto del contenedor
    let altoContenedor = contenedor.height();
 
    if(altoContenedor > 310){
    jQuery(".bg-white").css("min-height", "855px");
    }
};
let form = $(".createForm");
if (form) {
    form.on("submit", function(e) {
        let requiredInputs = $('input[required] ,select[required]');
        let submitButton = $(".submitButton");

        for (let i = 0; i < requiredInputs.length; i++) {
            if (requiredInputs[i].value === '') {
                e.preventDefault();
                mensajeValidacion('Validación', 'Debe llenar todos los campos marcados con un *');
                return false;
            }
        }

        if (submitButton ) {
            submitButton.prop('disabled', true);
        }
    });
}
