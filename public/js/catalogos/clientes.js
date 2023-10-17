jQuery(document).ready(function ($){
    console.log('el archivo js esta vinculado');
    const selectCategoria = $('#selectCategoria');
    const selectGrupo = $('#selectGrupo');
    const selectEstatus = $('#selectEstatus');
    const selectCondicion = $('#selectCondicion');
    const personascontainer =$('#personascontainer input[type = "checkbox"]');
    
    const checkPersonaM = $('#checkPersonaM');
    const checkPersonaF = $('#checkPersonaF');

    const inputCURP = $('#inputCURP');
    const inputNombres = $('#inputNombre');
    const inputApellidoP = $('#inputApellidoP');
    const inputApellidoM = $('#inputApellidoM');
    const requiredF = $('.requiredpF');
    const requiredM = $('.requiredpM');
    const files = $('#contenedorFile input[type = "file"]');
    const textoFile =$('#contenedorFile input[type = "text"]');
    files.on('change',function(){
        let val = $(this).val() !== '';
        if (val) {
            textoFile.prop('required',true);
        }else{
            textoFile.prop('required',false);
        } 
    })
    // var inputsText =[];
    // var inputsFile =[];

    // inputsText.push(textoFile);
    // inputsFile.push(textoFile);

    requiredF.hide();
    requiredM.hide();


    selectCategoria.add(selectEstatus).add(selectGrupo).add(selectCondicion).select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });

    checkPersonaM.on('change',function(){
        // console.log('se disparó el trigger');
        inputNombres.attr('required','required');
        inputApellidoP.attr('required','required');
        inputApellidoM.attr('required','required');
        inputCURP.removeAttr('required','required');
        requiredM.show();
        requiredF.hide();

    });
    checkPersonaF.on('change',function(){
        // console.log('se disparó el trigger');
        inputCURP.attr('required','required');
        inputNombres.removeAttr('required','required');
        inputApellidoP.removeAttr('required','required');
        inputApellidoM.removeAttr('required','required');
        requiredF.show();
        requiredM.hide();
    })

    personascontainer.click(function (){
        if ($(this).is(':checked')) {
            personascontainer.not(this).prop('checked',false).removeAttr('required');
        }
        else{
            personascontainer.attr('required','required');
        }
    });
    
    var maxField = 25; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper

    var fieldHTML = "<div class='field-item' ><label for='' class='field-label'>Nombre del Documento</label><div class='field-wrap-documents'><input value='' class='input-bordered txt' name='inputDocumento[]' type='text'><a href='javascript:void(0);' class='remove_button btn btn-sm btn-danger' title='Remove field'><i class='fa fa-times' aria-hidden='true'></i></a></div><div class='fiel-wrap'><input name='archivos[]'  type='file' name='archivo' class= 'files' ></div</div> "
    var x = 1; //Initial field counter is 1
    $(addButton).click(function() { //Once add button is clicked
        if (x < maxField) { //Check maximum number of input fields
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); // Add field html
        }
        let appendTxt = $('.txt');
        let appendFile = $('.files');

        // inputsText.push(appendFile);
        // inputsFile.push(appendTxt);
        // console.log(inputsFile);
        // console.log(inputsText);
    });

    $(wrapper).on('click', '.remove_button', function(e) { //Once remove button is clicked
        e.preventDefault();
        $(this).parent('div').parent('div').remove(); //Remove field html
        x--; //Decrement field counter
        // inputsFile.pop();
        // inputsText.pop();
        // console.log(inputsFile);
        // console.log(inputsText);
    });
    // recorrerArreglo();
    //     function recorrerArreglo(){
    //         for (let i = 0; i < inputsFile.length; i++) {
    //             const element = inputsFile[i];
    //             console.log(element);
    //             if (element.val !== null) {
    //                 inputsText[i].prop('required',true);
    //             }else{
    //                 inputsText[i].prop('required',false);
    //             }
    //         }
    //     }

    $('#ver').click(function() {
        var passwordInput = $('#contraseña');
        if (passwordInput.attr('type') === 'password') {
          passwordInput.attr('type', 'text');
        } else {
          passwordInput.attr('type', 'password');
        }
      });  
})
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
                showMessage('Documento Cliente', mensaje, 'success');
                //recargamos la pagina
                location.reload();
                
            } else {
                showMessage('Documento Cliente', mensaje, 'error')
            }
        }
    })
}