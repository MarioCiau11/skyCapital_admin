<tr>
    <td class="td-option">
        <div class="contenedor-opciones">
            <a href=" {{route('cat.proyectos.show',['proyecto' => Crypt::encrypt($proyecto->idProyecto)])}}" class="show" data-toggle="tooltip" data-placement="top" title="Mostrar registro"><i class="fa fa-eye" aria-hidden="true"></i></a>
            <a href=" {{route('cat.proyectos.edit',['proyecto' => Crypt::encrypt($proyecto->idProyecto)])}}" class="edit" data-toggle="tooltip" data-placement="top" title="Editar registro"><i class="fa fa-edit" aria-hidden="true"></i></a>
            @if ($proyecto->estatus != 0)
            <form class="deleteForm" action="{{route('cat.proyectos.destroy',['proyecto' =>Crypt::encrypt($proyecto->idProyecto)])}}" method="POST" class="deleteform">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete" data-toggle="tooltip" data-placement="top" title="Eliminar registro">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
            @endif
        </div>
    </td>
    <td >{{$proyecto->clave}}</td>
    <td >{{$proyecto->nombre}}</td>
    <td >{{$proyecto->descripcion}}</td>
    <td >$ {{ number_format($proyecto->presupuesto, 2)}}</td>
    <td >{{$proyecto->añoFinProyecto}}</td>
    <td >{{ number_format($proyecto->mt2, 2)}}</td>
    <td >{{ number_format($proyecto->nivelesTotales, 2)}}</td>
    <td >{{$proyecto->categoria != null ? $proyecto->getCategoria->nombre : null}} </td>
    <td >{{$proyecto->grupo != null ? $proyecto->getGrupo->nombre : null}} </td>
    <td >{{$proyecto->estatus == 1 ? 'Alta' : 'Baja'}}</td>
    <td >{{$proyecto->fechaAlta}}</td>
    <td >{{$proyecto->fechaCambio}}</td>
    <td >{{$proyecto->fechaBaja}}</td>
</tr>
