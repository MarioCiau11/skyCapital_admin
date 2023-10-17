<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Correo de Prueba</title>
    <style>
        @font-face{
            font-family: 'work_sansregular';
            src: url('{{public_path()}}/fonts/WorkSans-VariableFont_wght.ttf') format('truetype}');
        }
        .contenedor{
            background-color: #D0D0D0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .contenedor .logo{
            max-width: 895px;
            max-height: 351px;
        }
        .contenedor .imgSuperior{
            max-width: 1128px;
            max-height: 752px;
        }
        h1{
            font-size: 30px;
            font-family: 'work_sansregular';
        }
        p{
            font-size: 20px;
        }
    </style>
</head>
<body>
    <table class="contenedor">
        <tbody>
            <tr>
                <td align="center">
                    <img class="imgSuperior" src="{{$foto1}}" alt="">
                </td>
            </tr>
            <br>

            <tr>
                <td align="center">
                    <h1>
                        AQUÍ TIENES TUS ACCESOS A NUESTRO PORTAL OFICIAL PARA CLIENTES
                    </h1>
                </td>
            </tr>
            
            <br>
            <tr>
                <td align="center">
                    <p>
                        Por este medio, te invitamos a visitar nuestro nuevo portal de acceso para clientes, un nuevo servicio de SkyCapital para brindar control y organización a todo tu proceso de adquisición en cualquiera de nuestros proyectos.
                    </p>
                </td>
            </tr>

            <br>
            <tr>
                <td align="center">
                    <img src="{{$foto2}}" alt="">
                </td>
            </tr>

            <br>
            <tr>
                <td style="border-bottom: 1px solid #000000; background: unset; height: 1px; width: 100%; margin: 0px;"></td>
            </tr>

            <tr>
                <td align="center">
                    <h2>
                        Aquí están tus credenciales de acceso
                    </h2>
                    <p>
                        Te recomendamos guardarlas en un lugar seguro. También puedes modificar tu contraseña directamente en la plataforma.
                    </p>
                </td>
            </tr>

            <tr>
                <td align="center">
                    <p>USUARIO</p>
                    <p>CONTRASEÑA</p>
                    <button>
                        ACCESO AL PORTAL
                    </button>
                </td>
            </tr>

            
            <tr>
                <td align="center">
                    <tr>
                        <td align="center" valign="top"><a target="_blank" href="https://viewstripo.email">
                            <img title="Facebook" src="https://stripo.email/static/assets/img/social-icons/circle-black/facebook-circle-black.png" alt="Fb" height="24"></a>
                        </td>
                        <td align="center" valign="top"><a target="_blank" href="https://viewstripo.email">
                            <img title="Instagram" src="https://stripo.email/static/assets/img/social-icons/circle-black/instagram-circle-black.png" alt="Inst" height="24"></a>
                        </td>
                        <td align="center" valign="top"><a target="_blank" href="https://viewstripo.email">
                            <img title="Linkedin" src="https://stripo.email/static/assets/img/social-icons/circle-black/linkedin-circle-black.png" alt="In" height="24"></a>
                        </td>    
                    </tr>
                </td>
            </tr>
            

            <tr>
                <td align="center">
                    <i>Copyright (C) 2023 INMOBILIARIA SKY DE YUCATAN. Todos los derechos reservados.</i>
                    <br>
                    <span>
                        Has recibido este correo electrónico porque lo has aceptado en nuestro sitio web
                    </span>
                    <br>
                    <br>
                    <span>Nuestra dirección de correo es:</span>
                    <br>
                    <span>INMOBILIARIA SKY DE YUCATAN AV ANDRES GARCIA LAVIN 261 INT 701 COL SAN RAMON MERIDA, YUCATAN 97117 Mexico</span>
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>