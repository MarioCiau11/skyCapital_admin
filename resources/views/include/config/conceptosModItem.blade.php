<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href=" {{route('config.conceptos-modulos.show',['conceptos_modulo' => Crypt::encrypt($conceptos->idConceptosm)])}}" class="show" data-toggle="tooltip" data-placement="top" title="Mostrar registro"><i class="fa fa-eye" aria-hidden="true"></i></a>
            <a href=" {{route('config.conceptos-modulos.edit',['conceptos_modulo' => Crypt::encrypt($conceptos->idConceptosm)])}}" class="edit" data-toggle="tooltip" data-placement="top" title="Editar registro"><i class="fa fa-edit" aria-hidden="true"></i></a>
            @if ($conceptos->estatus == '1')
            <form class="deleteForm" action="{{route('config.conceptos-modulos.destroy',['conceptos_modulo' =>Crypt::encrypt($conceptos->idConceptosm)])}}" method="POST" class="deleteform">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete" data-toggle="tooltip" data-placement="top" title="Eliminar registro">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            @endif
        </div>
    </td>
    <td >{{$conceptos->nombreConcepto}}</td>
    <td >{{$conceptos->modulo}}</td>
    <td >{{$conceptos->movimiento}}</td>
    <td >{{$conceptos->estatus == 1 ? 'Alta' : 'Baja'}}</td>
</tr>