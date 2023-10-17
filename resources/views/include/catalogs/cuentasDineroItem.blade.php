<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href=" {{route('cat.cuentas-dinero.show',['cuentas_dinero' => Crypt::encrypt($cuentas->idCuentasDinero)])}}" class="show" data-toggle="tooltip" data-placement="top" title="Mostrar registro"><i class="fa fa-eye" aria-hidden="true"></i></a>
            <a href=" {{route('cat.cuentas-dinero.edit',['cuentas_dinero' => Crypt::encrypt($cuentas->idCuentasDinero)])}}" class="edit" data-toggle="tooltip" data-placement="top" title="Editar registro"><i class="fa fa-edit" aria-hidden="true"></i></a>
            @if ($cuentas->estatus == '1')
            <form class="deleteForm" action="{{route('cat.cuentas-dinero.destroy',['cuentas_dinero' =>Crypt::encrypt($cuentas->idCuentasDinero)])}}" method="POST" class="deleteform">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete" data-toggle="tooltip" data-placement="top" title="Eliminar registro">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            @endif
        </div>
    </td>
    <td >{{$cuentas->clave}}</td>
    <td >{{$cuentas->idInstitucionf != null ?$cuentas->getInstituciones->nombre : null }}</td>
    <td >{{$cuentas->noCuenta}} </td>
    <td >{{$cuentas->tipoCuenta}} </td>
    <td >{{$cuentas->idEmpresa != null ? $cuentas->getEmpresas->nombreEmpresa : null}} </td>
    <td> {{$cuentas->estatus == 1 ? 'Alta' : 'Baja'}} </td>
</tr>
