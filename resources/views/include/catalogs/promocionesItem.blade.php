<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href=" {{route('cat.promociones.show',['promocione' => Crypt::encrypt($promocion->idPromocion)])}}" class="show" data-toggle="tooltip" data-placement="top" title="Mostrar registro"><i class="fa fa-eye" aria-hidden="true"></i></a>
            <a href=" {{route('cat.promociones.edit',['promocione' => Crypt::encrypt($promocion->idPromocion)])}}" class="edit" data-toggle="tooltip" data-placement="top" title="Editar registro"><i class="fa fa-edit" aria-hidden="true"></i></a>
            @if ($promocion->estatus == '1')
            <form class="deleteForm" action="{{route('cat.promociones.destroy',['promocione' =>Crypt::encrypt($promocion->idPromocion)])}}" method="POST" class="deleteform">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete" data-toggle="tooltip" data-placement="top" title="Eliminar registro">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            @endif
        </div>
    </td>
    <td >{{$promocion->clave}}</td>
    <td >{{$promocion->nombre}}</td>
    <td >{{$promocion->estatus == 1 ? 'Alta' : 'Baja'}}</td>
    <td >{{$promocion->fechaAlta}}</td>
    <td >{{$promocion->fechaCambio}}</td>
    <td >{{$promocion->fechaBaja}}</td>
</tr>
