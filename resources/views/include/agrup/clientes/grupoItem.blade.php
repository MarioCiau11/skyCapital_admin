<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href=" {{route('agrup.clientes.grupo.edit',['Clientes_Grupo' => Crypt::encrypt($grupos->idGrupo)])}}" class="edit" data-toggle="tooltip" data-placement="top" title="Editar registro"><i class="fa fa-edit" aria-hidden="true"></i></a>
            @if ($grupos->estatus == '1')
            <form class="deleteForm" action="{{route('agrup.clientes.grupo.destroy',['Clientes_Grupo' =>Crypt::encrypt($grupos->idGrupo)])}}" method="POST" class="deleteform">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete" data-toggle="tooltip" data-placement="top" title="Eliminar registro">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            @endif
        </div>
    </td>
    <td >{{$grupos->nombre}}</td>
    <td >{{$grupos->estatus == 1 ? 'Alta' : 'Baja'}}</td>
</tr>