<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href=" {{route('cat.etiquetas.show',['etiqueta' => Crypt::encrypt($etiqueta->idEtiqueta)])}}" class="show" data-toggle="tooltip" data-placement="top" title="Mostrar registro"><i class="fa fa-eye" aria-hidden="true"></i></a>
            <a href=" {{route('cat.etiquetas.edit',['etiqueta' => Crypt::encrypt($etiqueta->idEtiqueta)])}}" class="edit" data-toggle="tooltip" data-placement="top" title="Editar registro"><i class="fa fa-edit" aria-hidden="true"></i></a>
            @if ($etiqueta->estatus == '1')
            <form class="deleteForm" action="{{route('cat.etiquetas.destroy',['etiqueta' =>Crypt::encrypt($etiqueta->idEtiqueta)])}}" method="POST" class="deleteform">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete" data-toggle="tooltip" data-placement="top" title="Eliminar registro">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            @endif
        </div>
    </td>
    <td >{{$etiqueta->clave}}</td>
    <td >{{$etiqueta->nombre}}</td>
    <td >{{$etiqueta->estatus == 1 ? 'Alta' : 'Baja'}}</td>
    <td >{{$etiqueta->fechaAlta}}</td>
    <td >{{$etiqueta->fechaCambio}}</td>
    <td >{{$etiqueta->fechaBaja}}</td>
</tr>
