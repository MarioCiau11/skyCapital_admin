<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href=" {{route('config.monedas.show',['moneda' => Crypt::encrypt($moneda->idMoneda)])}}" class="show" data-toggle="tooltip" data-placement="top" title="Mostrar registro"><i class="fa fa-eye" aria-hidden="true"></i></a>
            <a href=" {{route('config.monedas.edit',['moneda' => Crypt::encrypt($moneda->idMoneda)])}}" class="edit" data-toggle="tooltip" data-placement="top" title="Editar registro"><i class="fa fa-edit" aria-hidden="true"></i></a>
            @if ($moneda->estatus == '1')
            <form class="deleteForm" action="{{route('config.monedas.destroy',['moneda' =>Crypt::encrypt($moneda->idMoneda)])}}" method="POST" class="deleteform">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete" data-toggle="tooltip" data-placement="top" title="Eliminar registro">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            @endif
        </div>
    </td>
    <td >{{$moneda->clave}}</td>
    <td >{{$moneda->nombre}}</td>
    <td >{{$moneda->descripcion}} </td>
    <td >{{$moneda->tipoCambio}} </td>
    <td >{{$moneda->estatus == 1 ? 'Alta' : 'Baja'}}</td>
    <td >{{$moneda->userRelation->username}} </td>
    <td >{{$moneda->fechaAlta}}</td>
    <td >{{$moneda->fechaCambio}}</td>
    <td >{{$moneda->fechaBaja}}</td>
</tr>
