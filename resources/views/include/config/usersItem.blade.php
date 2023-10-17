<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href="{{ route('config.usuarios.show', ['usuario' => Crypt::encrypt($user->user_id)]) }}" class="show" data-toggle="tooltip" data-placement="top" title="Mostrar registro"><i class="fa fa-eye" aria-hidden="true"></i></a>
            <a href="{{ route('config.usuarios.edit', ['usuario' => Crypt::encrypt($user->user_id)]) }}" class="edit" data-toggle="tooltip" data-placement="top" title="Editar registro"><i class="fa fa-edit" aria-hidden="true"></i></a>

            @if($user->user_status == '1')
            <form action="{{ route('config.usuarios.destroy', ['usuario' => Crypt::encrypt($user->user_id)]) }}" method="POST" class="deleteForm">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete" data-toggle="tooltip" data-placement="top" title="Eliminar registro">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
            </form>
            @endif         
        </div>
    </td>
    <td>{{ $user->user_id }}</td>
    <td>{{ $user->user_name }}</td>
    <td>{{ $user->username }}</td>
    <td>{{ $user->email }}</td>
    <td>{{ $user->user_rol }}</td>
    <td>{{ $user->user_status == 1 ? 'Alta' : 'Baja' }}</td>
</tr>