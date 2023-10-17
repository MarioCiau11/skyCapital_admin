@extends('layouts.layout')

@section('content')
<section class="bg-white">

    <div class="columns is-marginless is-centered is-mobile">
        <div class="column is-11">

        <div class="columns is-mobile">
            <div class="column">
            <h1 class="title is-3">Asesor: {{$Agentes->nombre}}</h1>
            </div>
        </div>
        <hr class="is-marginless">
        {{ Breadcrumbs::render('cat.agentes-venta.show', $Agentes) }}

        <div class="columns">
            <div class="column">
                <div class="buttons">
                <ul class="btn-grp gutter-20px gutter-vr-20px">
                    <li> <a href="{{route('cat.agentes-venta.edit', Crypt::encrypt($Agentes->idAgentes))}}" class="badge badge-outline badge-info">Editar Asesor</a></li>
                    <li><form id="delete-form" action="{{ route('cat.agentes-venta.destroy', Crypt::encrypt($Agentes->idAgentes)) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        @if ($Agentes->estatus != 0)
                            <button id="button-delete" type="button" class="badge badge-outline badge-danger delete2">Baja</button> 
                        @endif
                        </form></li>
                </ul>
                </div>
            </div>
        </div>
            <table class="table table is-bordered is-striped is-hoverable is-fullwidth">
                <tbody>
                <tr>
                    <td>Clave</td>
                    <td>{{ $Agentes->clave }}</td>
                </tr>
                <tr>
                    <td>Nombre</td>
                    <td>{{ ucwords($Agentes->nombre) }}</td>
                </tr>
                <tr>
                    <td>Tipo</td>
                    <td>{{ ucfirst($Agentes->tipo) }}</td>
                </tr>
                <tr>
                    <td>Categoría</td>
                    <td >{{$Agentes->categoria != null ? ucfirst($Agentes->relationCat->nombre) : null}} </td>
                </tr>
                <tr>
                    <td>Grupo</td>
                    <td >{{$Agentes->grupo != null ? ucfirst($Agentes->relationGrupo->nombre) : null}} </td>
                </tr>
                <tr>
                    <td>Usuario que lo Registró</td>
                    <td>{{ $Agentes->getUser->username }}</td>
                </tr>
                <tr>
                    <td>Estatus</td>
                    <td>{{ ($Agentes->estatus) == 1 ? 'Alta':'Baja' }}</td>
                </tr>
                <tr>
                    <td>Fecha Creación</td>
                    <td>{{$Agentes->fechaAlta}}</td>
                </tr>
                <tr>
                    <td>Última Actualización</td>
                    <td>{{$Agentes->fechaCambio}}</td>
                </tr>
                <tr>
                    <td>Fecha de Baja</td>
                    <td>{{$Agentes->fechaBaja}}</td>
                </tr>
                </tbody>
            </table>
        </div>
        </div>
    </div>
</section>
@endsection