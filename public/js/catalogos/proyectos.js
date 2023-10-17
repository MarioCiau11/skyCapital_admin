jQuery(document).ready(function ( $ ){
    $('#selectEstatus, #selectTipo, #selectProyecto, #selectCategoria, #selectGrupo').select2({
        minimumResultsForSearch:-1,
        width:'100%'
    });


    const imagesPreview = function(input, placeToInsertImagePreview) {
        $(".sinGuardar").remove();
        if (input.files) {
            const filesAmount = input.files.length;
            let identificador = 0;
            for (i = 0; i < filesAmount; i++) {
                //Validamos q los files sean imagenes y no documentos
                let tipoImagen = input.files[i].type.trim();
                if (tipoImagen == 'image/png' || tipoImagen == 'image/jpg' || tipoImagen ==
                    'image/jpeg') {
                    // jQuery(".spinner").show();
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        identificador++;
                        $($.parseHTML(
                                `<div class='imgContenedorPreview sinGuardar'>
                             <a data-fancybox='demo' data-src='${event.target.result}'>
                                <img src='${event.target.result}' class="imgPreview">
                            </a>
                            </div>`
                            ))
                            .appendTo(
                                placeToInsertImagePreview);
                        // jQuery("#loader").hide();
                    }
                    reader.readAsDataURL(input.files[i]);

                }
            }
        }

    };

    $('#file-5').on('change', function() {
        imagesPreview(this, 'div.gallery');
    });
    

    var maxField = 25; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    // var fieldHTML = '<div><input type="text" name="field_name[]" value=""/><a href="javascript:void(0);" class="remove_button" title="Remove field"><img src="remove-icon.png"/></a></div>'; //New input field html 

    var fieldHTML = "<div class='col-md-11' ><label for='' class='field-label'>Nombre del Documento</label><div class='field-wrap-documents'><input value='' class='input-bordered' name='nombreDocumento[]' type='text'><a href='javascript:void(0);' class='remove_button btn btn-sm btn-danger' title='Remove field'><i class='fa fa-times' aria-hidden='true'></i></a></div><div class='fiel-wrap'><input name='field_name[]'  type='file'></div</div> "; //New input field html 
    var x = 1; //Initial field counter is 1
    $(addButton).click(function() { //Once add button is clicked
        if (x < maxField) { //Check maximum number of input fields
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); // Add field html
        }
        // $('#nameDoc1').prop('required',true);
        // $('#fileDoc').prop('required',true);
        // $('.agregados').prop('required',true);
        // $('#nameDoc1').focus();

    });


    $(wrapper).on('click', '.remove_button', function(e) { //Once remove button is clicked
        e.preventDefault();
        $(this).parent('div').parent('div').remove(); //Remove field html
        x--; //Decrement field counter
    });

    $('#nameDoc1').on('keyup', function(e) {
        let valor = e.target.value;

        if (valor.length > 0) {
            // $('#fileDoc').setCustomValidity('Prueba');
            $('#fileDoc').prop('required', true);
        } else {
            $('#fileDoc').prop('required', false);
        }


    });

});

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


function eliminarImg(id) {
    $("#loader").show();
    $.ajax({
        url: '/eliminar/img/',
        method: 'GET',
        data: {
            idImg: id
        },
        success: function({
            status,
            mensaje
        }) {
            if (status) {
                $('#' + id).remove();
                showMessage('Imagen Proyecto', mensaje, 'success');
            } else {
                showMessage('Imagen Proyecto', mensaje, 'error')
            }
        }
    })
}

function eliminarDoc(id) {
    event.preventDefault();
    $("#loader").show();
    $.ajax({
        url: '/eliminar/doc/',
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
                showMessage('Documento Proyecto', mensaje, 'success');
                //recargamos la pagina
                location.reload();
                
            } else {
                showMessage('Documento Proyecto', mensaje, 'error')
            }
        }
    })
}

