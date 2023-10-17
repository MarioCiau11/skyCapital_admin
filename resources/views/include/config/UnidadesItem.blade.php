<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href="{{ route('config.unidades.show', ['unidade' => Crypt::encrypt($unidades->idUnidades)]) }}"
                class="show" data-toggle="tooltip" data-placement="top" title="Mostrar registro"><i class="fa fa-eye"
                    aria-hidden="true"></i></a>
            <a href="{{ route('config.unidades.edit', ['unidade' => Crypt::encrypt($unidades->idUnidades)]) }}"
                class="edit" data-toggle="tooltip" data-placement="top" title="Editar registro"><i class="fa fa-edit"
                    aria-hidden="true"></i></a>

            @if ($unidades->estatus == '1')
                <form
                    action="{{ route('config.unidades.destroy', ['unidade' => Crypt::encrypt($unidades->idUnidades)]) }}"
                    method="POST" class="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete" data-toggle="tooltip" data-placement="top"
                        title="Eliminar registro">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                </form>
            @endif
        </div>
    </td>
    <td>{{ $unidades->unidad }}</td>
    <td>{{ $unidades->decimalValida }}</td>
    <td>{{ $unidades->estatus == 1 ? 'Alta' : 'Baja' }}</td>
    <td>{{ $unidades->userRelation->username }}</td>
    <td>{{ $unidades->fechaAlta }}</td>
    <td>{{ $unidades->fechaCambio }}</td>
    <td>{{ $unidades->fecha_Baja }}</td>
</tr>
