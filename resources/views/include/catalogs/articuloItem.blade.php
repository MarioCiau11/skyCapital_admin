<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href=" {{route('cat.articulos.show',['articulo' => Crypt::encrypt($articulo->idArticulos)])}}" class="show" data-toggle="tooltip" data-placement="top" title="Mostrar registro"><i class="fa fa-eye" aria-hidden="true"></i></a>
            <a href=" {{route('cat.articulos.edit',['articulo' => Crypt::encrypt($articulo->idArticulos)])}}" class="edit" data-toggle="tooltip" data-placement="top" title="Editar registro"><i class="fa fa-edit" aria-hidden="true"></i></a>
            @if ($articulo->estatus == '1')
            <form class="deleteForm" action="{{route('cat.articulos.destroy',['articulo' =>Crypt::encrypt($articulo->idArticulos)])}}" method="POST" class="deleteform">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete" data-toggle="tooltip" data-placement="top" title="Eliminar registro">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            @endif
        </div>
    </td>
    <td >{{$articulo->clave}}</td>
    <td >{{$articulo->tipo}}</td>
    <td >{{$articulo->descripcion}} </td>
    <td >{{$articulo->unidadesRelation->unidad}} </td>
    <td >{{$articulo->getCategoria != null ? $articulo->getCategoria->nombre : ''}} </td>
    <td >{{$articulo->getGrupo != null ? $articulo->getGrupo->nombre : ''}} </td>
    <td >{{number_format($articulo->IVA,2)}} %</td>
    <td >{{$articulo->user_id != null ?$articulo->getUser->username : null}}</td>
    <td >$ {{number_format($articulo->precio,2)}}</td>
    <td >{{$articulo->estatus == 1 ? 'Alta' : 'Baja'}}</td>
    <td >{{$articulo->fechaAlta}}</td>
    <td >{{$articulo->fechaCambio}}</td>
    <td >{{$articulo->fechaBaja}}</td>
</tr>