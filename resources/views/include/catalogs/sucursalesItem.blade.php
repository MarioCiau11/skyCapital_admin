<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href=" {{route('cat.sucursales.show',['sucursale' => Crypt::encrypt($sucursal->idSucursal)])}}" class="show" data-toggle="tooltip" data-placement="top" title="Mostrar registro"><i class="fa fa-eye" aria-hidden="true"></i></a>
            <a href=" {{route('cat.sucursales.edit',['sucursale' => Crypt::encrypt($sucursal->idSucursal)])}}" class="edit" data-toggle="tooltip" data-placement="top" title="Editar registro"><i class="fa fa-edit" aria-hidden="true"></i></a>
            @if ($sucursal->estatus == '1')
            <form class="deleteForm" action="{{route('cat.sucursales.destroy',['sucursale' =>Crypt::encrypt($sucursal->idSucursal)])}}" method="POST" class="deleteform">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete" data-toggle="tooltip" data-placement="top" title="Eliminar registro">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            @endif
        </div>
    </td>
    <td >{{$sucursal->clave}}</td>
    <td >{{$sucursal->nombre}}</td>
    <td >{{$sucursal->empresas->nombreEmpresa}}</td>
    <td >{{$sucursal->direccion}} </td>
    <td >{{$sucursal->colonia}} </td>
    <td >{{$sucursal->codigoPostal}} </td>
    <td >{{$sucursal->ciudad}} </td>
    <td >{{$sucursal->estado}} </td>
    <td >{{$sucursal->pais}} </td>
    <td >{{$sucursal->estatus == 1 ? 'Alta' : 'Baja'}}</td>
    <td >{{$sucursal->fechaAlta}}</td>
    <td >{{$sucursal->fechaCambio}}</td>
    <td >{{$sucursal->fechaBaja}}</td>
</tr>
