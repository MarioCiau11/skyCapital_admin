<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href=" {{route('cat.empresas.show',['empresa' => Crypt::encrypt($empresa->idEmpresa)])}}" class="show" data-toggle="tooltip" data-placement="top" title="Mostrar registro"><i class="fa fa-eye" aria-hidden="true"></i></a>
            <a href=" {{route('cat.empresas.edit',['empresa' => Crypt::encrypt($empresa->idEmpresa)])}}" class="edit" data-toggle="tooltip" data-placement="top" title="Editar registro"><i class="fa fa-edit" aria-hidden="true"></i></a>
            @if ($empresa->estatus == '1')
            <form class="deleteForm" action="{{route('cat.empresas.destroy',['empresa' =>Crypt::encrypt($empresa->idEmpresa)])}}" method="POST" class="deleteform">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete" data-toggle="tooltip" data-placement="top" title="Eliminar registro">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            @endif
        </div>
    </td>
    <td >{{$empresa->clave}}</td>
    <td >{{$empresa->nombreEmpresa}}</td>
    <td >{{$empresa->nombreCorto}}</td>
    <td >{{$empresa->descripcion}} </td>
    <td >{{$empresa->RFC}} </td>
    <td >{{$empresa->estatus == 1 ? 'Alta' : 'Baja'}}</td>
    <td >{{$empresa->logo}} </td>
    <td >{{$empresa->fechaAlta}}</td>
    <td >{{$empresa->fechaCambio}}</td>
    <td >{{$empresa->fechaBaja}}</td>
</tr>
