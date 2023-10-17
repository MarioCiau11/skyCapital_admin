@php
    use Carbon\Carbon;
@endphp
<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href="{{ route('proc.tesoreria.create',['tesoreria' =>Crypt::encrypt($tesoreria->idTesoreria)]) }}" class="show" data-toggle="tooltip" data-placement="top" title="Mostrar registro"><i class="fa fa-eye" aria-hidden="true"></i></a>
          
        </div>
    </td>
    <td >{{$tesoreria->movimiento}}</td>
    <td >{{$tesoreria->folioMov}}</td>
    <td >{{$tesoreria->getMoneda != null ? $tesoreria->getMoneda->clave : null}} </td>
    <td >${{number_format($tesoreria->tipoCambio, 2)}} </td>
    <td >{{$tesoreria->beneficiario != null ? $tesoreria->getCliente->razonSocial : null}} </td>
    <td >{{$tesoreria->cuentaDinero}}</td>
    <td >{{$tesoreria->getFormaPago != null ? $tesoreria->getFormaPago->nombre : null}} </td>
    <td >{{$tesoreria->origen}} </td>
    <td >{{$tesoreria->origenTipo}} </td>
    <td>{{ $tesoreria->fechaEmision }}</td>



    <td >$ {{number_format($tesoreria->importe, 2)}} </td>
    <td >$ {{number_format($tesoreria->impuestos, 2)}} </td>
    <td >$ {{number_format($tesoreria->importeTotal, 2)}} </td>
    <td >$ {{number_format($tesoreria->saldoCuenta, 2)}} </td>
    <td >{{$tesoreria->estatus}}</td>
    <td >{{$tesoreria->fechaAlta}}</td>
    <td >{{$tesoreria->fechaCambio}}</td>

</tr>