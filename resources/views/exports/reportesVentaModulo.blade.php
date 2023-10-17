@php
    setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
    $fechaI = strftime("%d de %B de %Y", strtotime($fechaInicio));
    $fechaF = strftime("%d de %B de %Y", strtotime($fechaFinal));
@endphp
<table>
    <thead>
        <tr></tr><tr></tr>
        <tr>
            <th></th><th></th><th></th><th></th>
            <th>Fecha impresión: {{date('d/m/Y')}}</th>
        </tr>
        <tr><th></th><th></th><th></th><th></th>
            <th>Nombre de la empresa: &nbsp; <b style="font-size: 12px;"> {{session('company')->nombreEmpresa}}</b></th>
            <th>
                @php
                    if ($fechaInicio != '' && $fechaFinal != '') { echo "Periodo consultado del $fechaI al $fechaF";
                    } else { echo "Periodo consultado: ", strtoupper($fecha); }
                @endphp
            </th>
        </tr>
        <tr><th></th><th></th><th></th><th></th>
            <th>Moneda: {{$moneda}} </th>
        </tr><tr></tr>
        <tr>
            <th width="10">No.</th>
            <th width="35"># Contrato</th>
            <th width="30">Fecha contrato</th>
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
            <th>Vendedor/Broker/Referidor 1</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($articulos as $key => $articulo)
            <tr>
                <td >{{$key+1}}</td>
                <td >{{$articulo->claveProyecto}} </td>
                <td >{{date("d/m/Y", strtotime($articulo->fechaContrato))}}</td>
                <td ></td>
                <td >{{$articulo->getCliente != null ? $articulo->getCliente->razonSocial : ''}}</td>
                <td >{{$articulo->getModulo != null ? $articulo->getModulo->nivelPiso : ''}}</td>
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
                <td >{{$articulo->getAgente != null ? $articulo->getAgente->nombre : ''}}</td>
            </tr>
        @endforeach
    </tbody>
</table>