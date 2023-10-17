<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office"
    style="font-family:arial, 'helvetica neue', helvetica, sans-serif">

<head>
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta charset="UTF-8">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="telephone=no" name="format-detection">
    <title>Nueva plantilla de correo electrónico 2023-06-27</title>
</head>
@php
    // dd($pathImageE, $html1);
@endphp
<body style="width:100%;font-family:arial, 'helvetica neue', helvetica, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0">
    <div class="es-wrapper-color" style="background-color:#ffffff; padding: 10px 20px;">
        <div class="container">
            <table cellpadding="0" cellspacing="0" width="100%"
            style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                <tr>
                    <td align="center" valign="top"
                        style="padding:0;Margin:0;width:560px">
                        <table cellpadding="0" cellspacing="0" width="100%"
                            role="presentation"
                            style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                            <tr>
                                <td align="center"
                                    style="padding:0;Margin:0;font-size:0px"><img
                                    class=""
                                    @if ($pathImageE != null)
                                        src="{{ isset($message) ? $message->embed($pathImageE) : '' }}"
                                        {{-- src="{{ $base64ImageE }}" --}}
                                    @endif
                                    alt
                                    style="max-width:140px; display:block; border:0;
                                    outline:none; text-decoration:none; -ms-interpolation-mode:bicubic">
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        <br>
        @php
            echo $html1;
        @endphp
    </div>
</body>

</html>
