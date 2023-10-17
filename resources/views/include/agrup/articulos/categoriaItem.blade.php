<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href=" {{route('agrup.articulos.categoria.edit',['Articulos_Categorium' => Crypt::encrypt($categorias->idCategoria)])}}" class="edit" data-toggle="tooltip" data-placement="top" title="Editar registro"><i class="fa fa-edit" aria-hidden="true"></i></a>
            @if ($categorias->estatus == '1')
            <form class="deleteForm" action="{{route('agrup.articulos.categoria.destroy',['Articulos_Categorium' =>Crypt::encrypt($categorias->idCategoria)])}}" method="POST" class="deleteform">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete" data-toggle="tooltip" data-placement="top" title="Eliminar registro">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            @endif
        </div>
    </td>
    <td >{{$categorias->nombre}}</td>
    <td >{{$categorias->estatus == 1 ? 'Alta' : 'Baja'}}</td>
</tr>