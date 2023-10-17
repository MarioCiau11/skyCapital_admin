jQuery(document).ready(function ($) {
    $('#selectEstatus').select2({
        minimumResultsForSearch: -1,
        width: '100%'
    });
    $('#selectPais').select2({
        width: '100%'
    });
    $('#selectEstado').select2({
        width: '100%'
    });
    $('#selectCiudad').select2({
        width: '100%'
    });
    $('#selectRegimen').select2({
        width: '100%'
    });

    //cargamos la imagen al input file
    jQuery(".btn-upload-logo-empresa").on("click", function (e) {
        e.preventDefault();
        jQuery(".logoEmpresa").click();
    });

    //Añadimos el nombre a nuestro input file personalizado
    jQuery(".logoEmpresa").on("change", function (e) {
        console.log(e.target.files[0].type);
        //validar que si haya seleccionado una imagen
        if (e.target.files.length != 0) {
            if (e.target.files[0].type == "image/jpeg" || e.target.files[0].type == "image/png" || e
                .target.files[0].type == "image/jpg") {
                if ($('.title-img').hasClass('error-img')) {
                    $('.title-img').removeClass('error-img');
                }
                $('.title-img')[0].innerHTML = e.target.files[0].name;
            } else {
                if (!$('.title-img').hasClass('error-img')) {
                    $('.title-img').addClass('error-img');
                    $('.title-img')[0].innerHTML = "Solo formatos JPG y PNG";
                }
            }
        }
    });

    jQuery("#btn-certicate-cer").on('click', function (e) {
        e.preventDefault();
        jQuery("#cer").click();

    });

    //Evento para escuchar el cambio al input file del certificado cer
    jQuery("#cer").on('change', function (e) {
        $('#certificate-cer')[0].value = e.target.files[0].name;
    });

    //evento click al btn del certificado key
    jQuery("#btn-certicate-key").on('click', function (e) {
        e.preventDefault();
        jQuery("#key").click();

    });

    //Evento para escuchar el cambio al input file del certificado key
    jQuery("#key").on('change', function (e) {
        $('#certificate-key')[0].value = e.target.files[0].name;
    });

    $("#changePassword").change(() => {
        if ($("#changePassword").prop('checked')) {
            $("#passwordKey").prop('disabled', false);
        } else {
            $("#passwordKey").prop('disabled', true);
        }
    });



})