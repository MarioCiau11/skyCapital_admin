<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href=" {{route('cat.modulos.show',['modulo' => Crypt::encrypt($modulo->idModulo)])}}" class="show" data-toggle="tooltip" data-placement="top" title="Mostrar registro"><i class="fa fa-eye" aria-hidden="true"></i></a>
            <a href=" {{route('cat.modulos.edit',['modulo' => Crypt::encrypt($modulo->idModulo)])}}" class="edit" data-toggle="tooltip" data-placement="top" title="Editar registro"><i class="fa fa-edit" aria-hidden="true"></i></a>
            @if ($modulo->estatus != 'Baja')
            <form class="deleteForm" action="{{route('cat.modulos.destroy',['modulo' =>Crypt::encrypt($modulo->idModulo)])}}" method="POST" class="deleteform">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete" data-toggle="tooltip" data-placement="top" title="Eliminar registro">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            @endif
        </div>
    </td>
    <td >{{$modulo->clave}}</td>
    <td >{{$modulo->descripcion}}</td>
    <td >{{$modulo->tipo}}</td>
    <td >{{$modulo->proyecto ? $modulo->getProyectos->nombre : null}}</td>
    <td >{{number_format($modulo->mt2, 2)}}</td>
    <td > $ {{number_format($modulo->valorOperacion, 2)}}</td>
    <td >{{number_format($modulo->nivelPiso, 0)}}</td>
    <td >{{number_format($modulo->numCajones,0)}}</td>
    <td >{{$modulo->getBank != null ? $modulo->getBank->clave : ''}}</td>
    <td >{{$modulo->cuenta}}</td>
    <td >{{$modulo->clabe}}</td>
    <td >{{$modulo->estatus}}</td>
    <td >{{$modulo->fechaAlta}}</td>
    <td >{{$modulo->fechaCambio}}</td>
    <td >{{$modulo->fechaBaja}}</td>
</tr>
