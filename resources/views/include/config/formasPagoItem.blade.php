<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href=" {{route('config.formas-pago.show',['formas_pago' => Crypt::encrypt($formas->idFormaspc)])}}" class="show" data-toggle="tooltip" data-placement="top" title="Mostrar registro"><i class="fa fa-eye" aria-hidden="true"></i></a>
            <a href=" {{route('config.formas-pago.edit',['formas_pago' => Crypt::encrypt($formas->idFormaspc)])}}" class="edit" data-toggle="tooltip" data-placement="top" title="Editar registro"><i class="fa fa-edit" aria-hidden="true"></i></a>
            @if ($formas->estatus == '1')
            <form class="deleteForm" action="{{route('config.formas-pago.destroy',['formas_pago' =>Crypt::encrypt($formas->idFormaspc)])}}" method="POST" class="deleteform">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete" data-toggle="tooltip" data-placement="top" title="Eliminar registro">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            @endif
        </div>
    </td>
    <td >{{$formas->clave}}</td>
    <td >{{$formas->nombre}}</td>
    <td >{{$formas->descripcion}} </td>
    <td >{{$formas->monedaSat}} </td>
    <td >{{$formas->estatus == 1 ? 'Alta' : 'Baja'}}</td>
    <td >{{$formas->userRelation->username}} </td>
    <td >{{$formas->fecha_Alta}}</td>
    <td >{{$formas->fecha_Cambio}}</td>
    <td >{{$formas->fecha_Baja}}</td>
</tr>
