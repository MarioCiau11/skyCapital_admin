<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href=" {{route('cat.agentes-venta.show',['asesor_comercial' => Crypt::encrypt($agente->idAgentes)])}}" class="show" data-toggle="tooltip" data-placement="top" title="Mostrar registro"><i class="fa fa-eye" aria-hidden="true"></i></a>
            <a href=" {{route('cat.agentes-venta.edit',['asesor_comercial' => Crypt::encrypt($agente->idAgentes)])}}" class="edit" data-toggle="tooltip" data-placement="top" title="Editar registro"><i class="fa fa-edit" aria-hidden="true"></i></a>
            @if ($agente->estatus == '1')
            <form class="deleteForm" action="{{route('cat.agentes-venta.destroy',['asesor_comercial' =>Crypt::encrypt($agente->idAgentes)])}}" method="POST" class="deleteform">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete" data-toggle="tooltip" data-placement="top" title="Eliminar registro">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            @endif
        </div>
    </td>
    <td >{{$agente->clave}}</td>
    <td >{{ucwords($agente->nombre)}}</td>
    <td >{{ucfirst($agente->tipo)}} </td>
    <td >{{$agente->categoria != null ? ucfirst($agente->relationCat->nombre) : null}} </td>
    <td >{{$agente->grupo != null ? ucfirst($agente->relationGrupo->nombre) : null}} </td>
    <td >{{$agente->estatus == 1 ? 'Alta' : 'Baja'}}</td>
    <td >{{$agente->getUser->username}}</td>
    <td >{{$agente->fechaAlta}}</td>
    <td >{{$agente->fechaCambio}}</td>
    <td >{{$agente->fechaBaja}}</td>
</tr>