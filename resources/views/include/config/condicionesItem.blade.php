
<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href=" {{route('config.condiciones-credito.show',['condiciones_credito' => Crypt::encrypt($condicion->idCondicionesc)])}}" class="show" data-toggle="tooltip" data-placement="top" title="Mostrar registro"><i class="fa fa-eye" aria-hidden="true"></i></a>
            <a href=" {{route('config.condiciones-credito.edit',['condiciones_credito' => Crypt::encrypt($condicion->idCondicionesc)])}}" class="edit" data-toggle="tooltip" data-placement="top" title="Editar registro"><i class="fa fa-edit" aria-hidden="true"></i></a>
            @if ($condicion->estatus == '1')
            <form class="deleteForm" action="{{route('config.condiciones-credito.destroy',['condiciones_credito' =>Crypt::encrypt($condicion->idCondicionesc)])}}" method="POST" class="deleteform">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete" data-toggle="tooltip" data-placement="top" title="Eliminar registro">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            @endif
        </div>
    </td>
    <td >{{$condicion->nombreCondicion}}</td>
    <td >{{$condicion->tipoCondicion}}</td>
    <td >{{$condicion->diasVencimiento}} </td>
    <td >{{$condicion->tipoDias}} </td>
    <td >{{$condicion->diasHabiles}} </td>
    {{-- <td >{{$condicion->metodoPago == null 
    ? $condicion->metodoPago : $condicion->getMetodoPago->descripcion}} </td> --}}
    <td >{{$condicion->estatus == 1 ? 'Alta' : 'Baja'}}</td>
    <td>{{ $condicion->user_id == null ? $condicion->user_id : $condicion->getUser->user_name }}</td>
    <td >{{$condicion->fecha_Alta}}</td>
    <td >{{$condicion->fecha_Cambio}}</td>
    <td >{{$condicion->fecha_Baja}}</td>
</tr>

