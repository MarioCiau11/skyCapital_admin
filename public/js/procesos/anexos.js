function showMessage(titulo, mensaje, icon) {
    swal({
        title: titulo,
        text: mensaje,
        icon: icon,
        button: false,
        timer: 1500
    });
    $("#loader").hide();
}

function eliminarAnexo(id) {
    event.preventDefault();
    $.ajax({
        url: '/eliminar/anexo/',
        method: 'GET',
        data: {
            idDoc: id
        },
        success: function({
            status,
            mensaje
        }) {
            if (status) {
                $('#' + id).remove();
                showMessage('Documento eliminado', mensaje, 'success');
                //recargamos la pagina
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
                
            } else {
                showMessage('Documento no eliminado', mensaje, 'error')
            }
        }
    })
}

let inputFile = $('#file');
let listaDeArchivos = $('#listaDeArchivos');
let archivosParaSubir = [];

function actualizarListaDeArchivos() {
    let listaHTML = archivosParaSubir.map(function(item, index) {
        return `<li class="row button-listdoc">
                    <label class="col-lg-12">${item.name}</label>
                </li>`;
    });
    listaDeArchivos.html(listaHTML);
}

inputFile.on('change', function (e) {
    let files = e.target.files;

    if (files.length == 0) return;

    files = Array.from(files);
    archivosParaSubir = files;
    actualizarListaDeArchivos();
});

// $(document).on("click", ".file-list-eliminar", function () {
//     let index = $(this).data('index');
//     archivosParaSubir.splice(index, 1);
//     actualizarListaDeArchivos();
// });

