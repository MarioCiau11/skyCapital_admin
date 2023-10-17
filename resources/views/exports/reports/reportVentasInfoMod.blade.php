<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('css/reportes.css')}}">
    <title>Información de módulos</title>
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
                        <img src="{{$imgLogo}}">
                    </td><td></td><td></td>
                    <td align="center">Nombre de la empresa: <b>{{strtoupper(session('company')->nombreEmpresa)}}</b> </td>
                    <td>Fecha de impresión: {{(date('d/m/Y'))}} <br>
                        @php
                            if ($fechaInicio != '' && $fechaFinal != '') {
                                echo "Periodo consultado del $fechaI al $fechaF <br>";
                            }
                            else {
                                echo "Periodo consultado: $fecha <br>";
                            }
                        @endphp
                        Moneda: {{$moneda}}
                    </td>
                </tr>
            </table>
            <table class="table-bordered-4 empty">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>#Contrato</th>
                        <th>Fecha contrato</th>
                        <th>Fecha cierre venta</th>
                        <th># EK</th>
                        <th>Nombre completo y/o razón social</th>
                        <th>Comentarios</th>
                        <th>Ocupación del cliente</th>
                        <th>Interés en rentar</th>
                        <th>Nivel</th>
                        <th>Módulo</th>
                        <th>M2</th>
                        <th>Status</th>
                        <th>Enganche pagado</th>
                        <th>Contrato firmado</th>
                        <th>Esquema de pago</th>
                        <th># Pagos</th>
                        <th>Precio de lista</th>
                        <th>Descuento</th>
                        <th>Precio de operación</th>
                        <th>Precio / M2</th>
                        <th>% Comisión a pagar 1</th>
                        <th>$ Comisión a pagar 1</th>
                        <th>Tipo de vendedor</th>
                        <th>Vendedor/ Broker/ Referidor 1</th>
                        <th>% Comisión a pagar 2</th>
                        <th>$ Comisión a pagar 2</th>
                        <th>Vendedor/ Broker/ Referidor 2</th>
                        <th>% Comisión a pagar 3</th>
                        <th>$ Comisión a pagar 3</th>
                        <th>Vendedor/ Broker/ Referidor 3</th>
                        <th>Fecha de solicitud pago de comisión</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($articulos as $key => $articulo)
                        <tr>
                            <td >{{$key+1}}</td>
                            <td width="100">{{$articulo->claveProyecto}} </td>
                            <td >{{date("d/m/Y", strtotime($articulo->fechaContrato))}}</td>
                            <td ></td>
                            <td ></td>
                            <td width="100">{{$articulo->getCliente != null ? $articulo->getCliente->razonSocial : ''}}</td>
                            <td >{{$articulo->getCliente != null ? $articulo->getCliente->observaciones : ''}}</td>
                            <td >{{$articulo->getCliente != null ? $articulo->getCliente->ocupacion : ''}}</td>
                            <td ></td>
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
                            <td >{{$articulo->porcentajeAsesor}} %</td>
                            <td >${{number_format($articulo->netoAsesor, 2)}}</td>
                            <td >{{$articulo->getAgente != null ? ucfirst($articulo->getAgente->tipo) : ''}}</td>
                            <td >{{$articulo->getAgente != null ? ucwords($articulo->getAgente->nombre) : ''}}</td>
                            <td >{{$articulo->porcentajeReferido}} %</td>
                            <td >${{number_format($articulo->netoReferido, 2)}}</td>
                            <td >{{ucwords($articulo->referido)}}</td>
                            <td >{{$articulo->porcentajeBroker}} %</td>
                            <td >${{number_format($articulo->netoBroker, 2)}}</td>
                            <td >{{ucwords($articulo->broker)}}</td>
                            <td ></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>

