<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href=" {{route('cat.instituciones.show',['institucione' => Crypt::encrypt($institucion->idInstitucionf)])}}" class="show" data-toggle="tooltip" data-placement="top" title="Mostrar registro"><i class="fa fa-eye" aria-hidden="true"></i></a>
            <a href=" {{route('cat.instituciones.edit',['institucione' => Crypt::encrypt($institucion->idInstitucionf)])}}" class="edit" data-toggle="tooltip" data-placement="top" title="Editar registro"><i class="fa fa-edit" aria-hidden="true"></i></a>
            @if ($institucion->estatus == '1')
            <form class="deleteForm" action="{{route('cat.instituciones.destroy',['institucione' =>Crypt::encrypt($institucion->idInstitucionf)])}}" method="POST" class="deleteform">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete" data-toggle="tooltip" data-placement="top" title="Eliminar registro">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            @endif
        </div>
    </td>
    <td >{{$institucion->clave}}</td>
    <td >{{$institucion->nombre}}</td>
    <td >{{$institucion->estatus == 1 ? 'Alta' : 'Baja'}}</td>
    <td >{{$institucion->fechaAlta}}</td>
    <td >{{$institucion->fechaCambio}}</td>
    <td >{{$institucion->fechaBaja}}</td>
</tr>
