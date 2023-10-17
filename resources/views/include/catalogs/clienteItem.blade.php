<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href=" {{route('cat.clientes.show',['cliente' => Crypt::encrypt($cliente->idCliente)])}}" class="show" data-toggle="tooltip" data-placement="top" title="Mostrar registro"><i class="fa fa-eye" aria-hidden="true"></i></a>
            <a href=" {{route('cat.clientes.edit',['cliente' => Crypt::encrypt($cliente->idCliente)])}}" class="edit" data-toggle="tooltip" data-placement="top" title="Editar registro"><i class="fa fa-edit" aria-hidden="true"></i></a>
            @if ($cliente->estatus == '1')
            <form class="deleteForm" action="{{route('cat.clientes.destroy',['cliente' =>Crypt::encrypt($cliente->idCliente)])}}" method="POST" class="deleteform">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete" data-toggle="tooltip" data-placement="top" title="Eliminar registro">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            @endif
        </div>
    </td>
    <td >{{$cliente->clave}}</td>
    <td >{{$cliente->razonSocial}}</td>
    <td >{{$cliente->RFC}} </td>
    <td >{{$cliente->CURP}} </td>
    <td >{{$cliente->observaciones}}</td>
    <td >{{$cliente->ocupacion}}</td>
    <td >{{$cliente->getGrupo != null ? $cliente->getGrupo->nombre : ''}} </td>
    <td >{{$cliente->getCategoria != null ? $cliente->getCategoria->nombre : ''}} </td>
    <td >{{$cliente->getCondiciones != null ? $cliente->getCondiciones->nombreCondicion : ''}}</td>
    {{-- <td >{{$cliente->getGrupo != null ? $cliente->getGrupo->nombre : ''}} </td> --}}
    <td >{{$cliente->getUser!= null ? $cliente->getUser->username : ''}}</td>
    <td >{{$cliente->estatus == 1 ? 'Alta' : 'Baja'}}</td>
    <td >{{$cliente->fechaAlta}}</td>
    <td >{{$cliente->fechaCambio}}</td>
    <td >{{$cliente->fechaBaja}}</td>
</tr>