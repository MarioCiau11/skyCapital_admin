jQuery(document).ready(function ($) {
    jQuery("#select-empresas, #select-sucursales").select2({
        minimumResultsForSearch: -1,
        width: "100%",
    });


    console.log(csrfToken);

    jQuery("#username").on("change", function () {
        const username = jQuery(this).val();
        if (username != "") {
            ajaxRequest(
                "/login/verificar",
                "post",
                { 
                    _token: csrfToken,
                    username: username 
                },
                function (response) {
                    if (response.status == 404) {
                        jQuery("#user-mensaje").css({
                            display: "block",
                        })
                        jQuery("#user-mensaje").html(response.data);
                    } else {
                        jQuery("#user-mensaje").css({
                            display: "none",
                        })
                    }
                }
            );
        }else{

            //limpiar contraseña
            jQuery("#password").val("");

            //limpiar el select de empresas
            jQuery("#select-empresas").html("");
            jQuery("#select-empresas").append(
                `<option value="">Seleccione una empresa</option>`
            );

            //limpiar el select de sucursales
            jQuery("#select-sucursales").html("");
            jQuery("#select-sucursales").append(
                `<option value="">Seleccione una sucursal</option>`
            );

            jQuery("#select-empresas").trigger("change");
            jQuery("#select-sucursales").trigger("change");


        }
    });



    jQuery("#password").on("change", function () {
        const password = jQuery(this).val();
        const username = jQuery("#username").val();
        if (password != "") {
            // const encryptedPassword = CryptoJS.AES.encrypt(password, "1234567891234567").toString();

            ajaxRequest(
                "/login/passVerificar",
                "post",
                {
                    _token: csrfToken,
                    password: password,
                    username: username
                },
                function ({ data, status, companies }) {
                    if (status == 404) {
                        jQuery("#pass-mensaje").css({
                            display: "block",
                        })
                        jQuery("#pass-mensaje").html(data);

                    } else {
                        jQuery("#pass-mensaje").css({
                            display: "none",
                        })
                        //armar el select de empresas
                        jQuery("#select-empresas").html("");
                        jQuery("#select-empresas").append(
                            `<option value="">Seleccione una empresa</option>`
                        );
                        for (let i = 0; i < companies.length; i++) {
                            jQuery("#select-empresas").append(
                                `<option value="${companies[i].empresas.idEmpresa}">${companies[i].empresas.nombreEmpresa}</option>`
                            );
                        }
                        jQuery("#select-empresas").trigger("change");

                    }
                }
            );
        }
    });

    jQuery("#select-empresas").on("change", function () {
        const idEmpresa = jQuery(this).val();
        if (idEmpresa != "") {
            ajaxRequest(
                "/login/sucursales",
                "post",
                { 
                    _token: csrfToken,
                    idEmpresa: idEmpresa,
                },
                function ({ data, status, sucursales }) {
                    if(status == 200){
                        //armar el select de sucursales
                        armarSelect2("#select-sucursales", sucursales, "Seleccione una sucursal", "idSucursal", "clave", "nombre");
                        
                    }
                }
            );
        }
    });

    jQuery("#btn-login").on("click", function (event) {

        event.preventDefault();

        const username = jQuery("#username").val();
        const password = jQuery("#password").val();
        const idSucursal = jQuery("#select-sucursales").val();
        const idEmpresa = jQuery("#select-empresas").val();

        if(username != "" && password != "" && idEmpresa != "" && (idSucursal != null && idSucursal != "")){
            jQuery("#form-login").submit();
            $("#btn-login").prop("disabled", true);
        }


    });
});

//funcion global para armar un select2
