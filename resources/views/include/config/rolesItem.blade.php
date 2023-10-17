
<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href="{{ route('config.roles.show', ['role' => Crypt::encrypt($rol->id)]) }}" class="show" data-toggle="tooltip" data-placement="top" title="Mostrar registro"><i class="fa fa-eye" aria-hidden="true"></i></a>
            <a href="{{ route('config.roles.edit', ['role' => Crypt::encrypt($rol->id)]) }}" class="edit" data-toggle="tooltip" data-placement="top" title="Editar registro"><i class="fa fa-edit" aria-hidden="true"></i></a>

            @if($rol->status == '1')
            <form action="{{ route('config.roles.destroy', ['role' => Crypt::encrypt($rol->id)]) }}" method="POST" class="deleteForm">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete" data-toggle="tooltip" data-placement="top" title="Eliminar registro">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
            </form>
            @endif         
        </div>
    </td>
    <td>{{ $rol->id }}</td>
    <td>{{ $rol->name }}</td>
    <td>{{ $rol->descript }}</td>
    <td>{{ $rol->status == 1 ? 'Alta' : 'Baja'}}</td>
    <td>{{ $rol->created_at }}</td>
    <td>{{ $rol->updated_at }}</td>
</tr>