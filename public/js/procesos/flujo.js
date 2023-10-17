jQuery(document).ready(function () {
  // const tablaFlujo = armarDataTable('tablaFlujo', false, false, true, false);
  const tablaFlujo = $('#tablaFlujo');

  const btnFlujoPrincipal = $('#btnFlujoPrincipal');
  const btnAvanzarFlujo = $('#btnAvanzarFlujo');
  const btnRetrocederFlujo = $('#btnRetrocederFlujo');
 

  tablaFlujo.on('click','tbody tr', function () {
    let fila = $(this);
    let datosFila = fila.find('td').map(function () {
      return $(this).text();
    }).get();
    ocultarMovimientos(datosFila);
  });

  btnFlujoPrincipal.on('click', function () {
    let datos = JSON.parse($('#inputFlujoPrincipal').val());
    BTNflujoPrincipal(datos);
  });

  btnAvanzarFlujo.on('click', function () {
    let datos = $('#inputMovimientoPosterior').val();
    
    movimientosPosteriores(datos);
  });
  btnRetrocederFlujo.on('click', function () {
    let datos = $('#inputMovimientoPosterior').val();
    movimientosAnteriores(datos);
    
  });
});
//funcion para regresar el flujo principal
function BTNflujoPrincipal(arrayFlujo) {
  let bodyFlujo = $('#bodyFlujo');
  let inputFlujoPrincpal = $('#inputFlujoPrincipal');
  let inputMovimientoPosterior = $('#inputMovimientoPosterior');

  if (arrayFlujo != null) {
    inputFlujoPrincpal.val(JSON.stringify(arrayFlujo));
    inputMovimientoPosterior.val(JSON.stringify(arrayFlujo[0]));
    let x = 0;
    bodyFlujo.empty();
    arrayFlujo.forEach(element => {
      let data = `
            <tr id = "movimientoFlujo${x}">
                <td>${element.cancelado == 1 ? '<i class="fas fa-ban"></i>':''} ${ element.origenMovimiento + '-' + element.origenFolio}</td>
                <td>${element.origenModulo}</td>
                <td>----</td>
                <td>${element.cancelado == 1 ? '<i class="fas fa-ban"></i>':''} ${ element.destinoMovimiento + '-' + element.destinoFolio}</td>
                <td>${element.destinoModulo}</td>
                <td style="display: none">${element.destinoId}</td>
                <td style="display: none">${element.origenId}</td>
                <td style="display: none">${element.idEmpresa}</td>
                <td style="display: none">${element.idSucursal}</td>
                <td style="display: none">${element.cancelado}</td>
            </tr>
            `;
      bodyFlujo.append(data);
      x++;

    });
  } else {
    bodyFlujo.empty();
    let data = `
            <tr>
                <td></td>
                <td></td>
                <td>----</td>
                <td></td>
                <td></td>
                <td style="display: none"></td>
                <td style="display: none"></td>
                <td style="display: none"></td>
                <td style="display: none"></td>
            </tr>
            `;
    bodyFlujo.append(data);
  }
  if (Object.keys(arrayFlujo).length === 1) {
    $('#btnAvanzarFlujo').show();
    $('#btnRetrocederFlujo').show();
  }else
  {
    $('#btnAvanzarFlujo').hide();
    $('#btnRetrocederFlujo').hide();
  }
}
//función para aevolver o agregar el flujo principal
function flujoPrincipal(idMovimiento,ModuloOrigen) {
  // console.log(idMovimiento);
  let bodyFlujo = $('#bodyFlujo');
  let inputFlujoPrincpal = $('#inputFlujoPrincipal');
  let inputMovimientoPosterior = $('#inputMovimientoPosterior');
  ajaxRequest('/getFlujo', 'get',
    {
      idMovimiento: idMovimiento,
      PRINCIPAL: true,
      modulo:ModuloOrigen
    },
    function(data){
      if (Object.keys(data).length != 0) {
        // console.log(data);
        inputFlujoPrincpal.val(JSON.stringify(data));
        inputMovimientoPosterior.val(JSON.stringify(data[0]));
        bodyFlujo.empty();
        data.forEach(element => {
          let data = `
            <tr>
                <td>${element.cancelado == 1 ? '<i class="fas fa-ban"></i>':''} ${ element.origenMovimiento + '-' + element.origenFolio}</td>
                <td>${element.origenModulo}</td>
                <td>----</td>
                <td>${element.cancelado == 1 ? '<i class="fas fa-ban"></i>':''} ${ element.destinoMovimiento + '-' + element.destinoFolio}</td>
                <td>${element.destinoModulo}</td>
                <td style="display: none">${element.destinoId}</td>
                <td style="display: none">${element.origenId}</td>
                <td style="display: none">${element.idEmpresa}</td>
                <td style="display: none">${element.idSucursal}</td>
                <td style="display: none">${element.cancelado}</td>
            </tr>
            `;
          bodyFlujo.append(data);
        });
      }
      else{
        bodyFlujo.empty();
        let data = `
          <tr>
              <td></td>
              <td></td>
              <td>----</td>
              <td></td>
              <td></td>
              <td style="display: none"></td>
              <td style="display: none"></td>
              <td style="display: none"></td>
              <td style="display: none"></td>
              <td style="display: none"></td>
          </tr>
          `;
        bodyFlujo.append(data);
      }
      if (Object.keys(data).length === 0 || Object.keys(data).length === 1) {
        $('#btnAvanzarFlujo').show();
        $('#btnRetrocederFlujo').show();
      }else
      {
        $('#btnAvanzarFlujo').hide();
        $('#btnRetrocederFlujo').hide();
      }
    }
  );
}
//función para ocultar movimientos no seleccionados
function ocultarMovimientos(Datos) {
  // console.log(Datos);
  let rowData = Datos;
  let inputMovimiento = $('#inputMovimientoPosterior');
  let bodyFlujo = $('#bodyFlujo');
  let datos = {
    destinoId: rowData[5],
    origenId: rowData[6],
    idEmpresa: rowData[7],
    idSucursal: rowData[8],
    origenModulo:rowData[1],
    destinoModulo:rowData[4],
    cancelado:rowData[9]
  };
  inputMovimiento.val(JSON.stringify(datos));
  bodyFlujo.empty();
  bodyFlujo.append(
    `<tr>
        <td>${rowData[9] == 1 ? '<i class="fas fa-ban"></i>':''} ${rowData[0]}</td>
        <td>${rowData[1]}</td>
        <td>${rowData[2]}</td>
        <td>${rowData[9] == 1 ? '<i class="fas fa-ban"></i>':''} ${rowData[3]}</td>
        <td>${rowData[4]}</td>
        <td style="display: none">${rowData[5]}</td>
        <td style="display: none">${rowData[6]}</td>
        <td style="display: none">${rowData[7]}</td>
        <td style="display: none">${rowData[8]}</td>
        <td style="display: none">${rowData[9]}</td>
    </tr>`
  );
  $('#btnAvanzarFlujo').show();
  $('#btnRetrocederFlujo').show();
}
//función para recuerar movimientos posteriores
function movimientosPosteriores(Data) {
 if (Data != '') {
  datos = JSON.parse(Data);
  let rowData = datos;
  let destinoId = rowData.destinoId;
  let idEmpresa = rowData.idEmpresa;
  let idSucursal = rowData.idSucursal;
  let origenModulo = rowData.origenModulo;
  let destinoModulo = rowData.destinoModulo;
  let origenId = rowData.origenId;
  ajaxRequest('/getFlujo', 'get',
    {
      idMovimiento: destinoId,
      idEmpresa: idEmpresa,
      idSucursal: idSucursal,
      modulo: destinoModulo,
      search: 'NEXT'
    },
    function (data) {
      if (Object.keys(data).length != 0) {
        // console.log(data);
        let bodyFlujo = $('#bodyFlujo');
        let inputMovimiento = $('#inputMovimientoPosterior');
        bodyFlujo.empty();
        if (Object.keys(data).length === 0 || Object.keys(data).length === 1) {
          inputMovimiento.val(JSON.stringify(data[0]));
            $('#btnAvanzarFlujo').show();
            $('#btnRetrocederFlujo').show();
        }else
        {
          inputMovimiento.val( JSON.stringify({
            status:'demasiados registros'
          }));
        }
        data.forEach(element => {
          let data = `
            <tr>
                <td>${element.cancelado == 1 ? '<i class="fas fa-ban"></i>':''} ${ element.origenMovimiento + '-' + element.origenFolio}</td>
                <td>${element.origenModulo}</td>
                <td>----</td>
                <td>${element.cancelado == 1 ? '<i class="fas fa-ban"></i>':''} ${ element.destinoMovimiento + '-' + element.destinoFolio}</td>
                <td>${element.destinoModulo}</td>
                <td style="display: none">${element.destinoId}</td>
                <td style="display: none">${element.origenId}</td>
                <td style="display: none">${element.idEmpresa}</td>
                <td style="display: none">${element.idSucursal}</td>
                <td style="display: none">${element.cancelado}</td>
            </tr>
            `;
          bodyFlujo.append(data);
        });
      }
    });
  }
}
function movimientosAnteriores(Data) {
  if (Data != '') {
    datos = JSON.parse(Data);
    let rowData = datos;
    let destinoId = rowData.destinoId;
    let idEmpresa = rowData.idEmpresa;
    let idSucursal = rowData.idSucursal;
    let origenModulo = rowData.origenModulo;
    let destinoModulo = rowData.destinoModulo;
    let origenId = rowData.origenId;
    ajaxRequest('/getFlujo', 'get',
      {
        idMovimiento: origenId,
        idEmpresa: idEmpresa,
        idSucursal: idSucursal,
        modulo: origenModulo,
        search: 'PREV'
      },
      function (data) {
        if (Object.keys(data).length != 0) {
          let bodyFlujo = $('#bodyFlujo');
          let inputMovimiento = $('#inputMovimientoPosterior');
          bodyFlujo.empty();
          if (Object.keys(data).length === 0 || Object.keys(data).length === 1) {
            inputMovimiento.val(JSON.stringify(data[0]));
              $('#btnAvanzarFlujo').show();
              $('#btnRetrocederFlujo').show();
          }else
          {
            inputMovimiento.val( JSON.stringify({
              status:'demasiados registros'
            }));
          }
          data.forEach(element => {
            let data = `
              <tr>
                  <td>${element.cancelado == 1 ? '<i class="fas fa-ban"></i>':''} ${ element.origenMovimiento + '-' + element.origenFolio}</td>
                  <td>${element.origenModulo}</td>
                  <td>----</td>
                  <td>${element.cancelado == 1 ? '<i class="fas fa-ban"></i>':''} ${ element.destinoMovimiento + '-' + element.destinoFolio}</td>
                  <td>${element.destinoModulo}</td>
                  <td style="display: none">${element.destinoId}</td>
                  <td style="display: none">${element.origenId}</td>
                  <td style="display: none">${element.idEmpresa}</td>
                  <td style="display: none">${element.cancelado}</td>
              </tr>
              `;
            bodyFlujo.append(data);
          });          
        }
      });
    }
}
