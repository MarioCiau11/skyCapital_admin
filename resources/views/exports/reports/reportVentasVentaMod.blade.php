<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/reportes.css')}}">
    <title>Venta de módulos</title>
</head>
@php
    setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
    $fechaI = strftime("%d de %B de %Y", strtotime($fechaInicio));
    $fechaF = strftime("%d de %B de %Y", strtotime($fechaFinal));
@endphp
    <body>
        <div class="container">
            <br>
            <table  class="cabecera" style="font-size: 20px !important;">
                <tr>
                    <td class="logoI">
                        <img src="{{ $imgLogo}}" alt="">
                    </td>
                    <td align="center">Nombre de la empresa: <b>{{strtoupper(session('company')->nombreEmpresa)}}</b> </td>
                    <td>Fecha de impresión: {{(date('d/m/Y'))}} <br><br>
                        @php
                            if ($fechaInicio != '' && $fechaFinal != '') {
                                echo "Periodo consultado del $fechaI al $fechaF <br><br>";
                            }
                            else {
                                echo "Periodo consultado: $fecha <br><br>";
                            }
                        @endphp
                        Moneda: {{$moneda}} <br><br>
                    <br><br><br></td>
                </tr>
            </table>
            <table class="table-bordered-4 empty">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th># Contrato</th>
                        <th>Fecha contrato</th>
                        <th># EK</th>
                        <th>Nombre completo y/o razón social</th>
                        <th>Nivel</th>
                        <th>Módulo</th>
                        <th>M2</th>
                        <th>Status</th>
                        <th>Enganche pagado</th>
                        <th>Contrato firmado</th>
                        <th>Esquema de pago</th>
                        <th># de pagos</th>
                        <th>Precio de lista</th>
                        <th>Descuento</th>
                        <th>Precio de operación</th>
                        <th>Precio / M2</th>
                        <th>% Comisión a pagar 1</th>
                        <th>$ Comisión a pagar 1</th>
                        <th>Tipo de vendedor</th>
                        <th>Vendedor/ Broker/ Referidor 1</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($articulos as $key => $articulo)
                        <tr>
                            <td >{{$key+1}}</td>
                            <td width="120" >{{$articulo->claveProyecto}} </td>
                            <td >{{date("d/m/Y", strtotime($articulo->fechaContrato))}}</td>
                            <td ></td>
                            <td >{{$articulo->getCliente != null ? $articulo->getCliente->razonSocial : ''}}</td>
                            <td >{{$articulo->getModulo != null ? number_format($articulo->getModulo->nivelPiso, 0) : ''}}</td>
                            <td >{{$articulo->getModulo != null ? $articulo->getModulo->clave : ''}}</td>
                            <td >{{$articulo->getModulo != null ? $articulo->getModulo->mt2 : ''}}</td>
                            <td >{{$articulo->getModulo != null ? $articulo->getModulo->estatus : ''}}</td>
                            <td >{{$articulo->enganchePagado}} </td>
                            <td ></td>
                            <td >{{$articulo->esquemaPago == 1 ? 'Mensualidades' : 'Contado'}}</td>
                            <td >{{$articulo->getCorrida != null ? $articulo->getCorrida->count() : ''}}</td>
                            <td >${{$articulo->getModulo != null ? number_format($articulo->getModulo->valorOperacion,2) : ''}}</td>
                            <td >{{number_format(0, 2, "."). " %"}}</td>
                            <td >${{$articulo->getModulo != null ? number_format(($articulo->getModulo->valorOperacion),2) : ''}}</td>
                            <td >${{$articulo->getModulo != null ? number_format(($articulo->getModulo->valorOperacion / $articulo->getModulo->mt2),2) : ''}}</td>
                            <td width="70">{{$articulo->porcentajeAsesor}} %</td>
                            <td >${{number_format($articulo->netoAsesor, 2)}}</td>
                            <td >{{$articulo->getAgente != null ? ucfirst($articulo->getAgente->tipo) : ''}}</td>
                            <td >{{$articulo->getAgente != null ? $articulo->getAgente->nombre : ''}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>

