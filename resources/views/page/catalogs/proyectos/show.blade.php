@extends('layouts.layout')
@section('title', $Proyecto->clave)

@section('content')

    <main class="ath-body">
        <section class="bg-white">

            <div class="columns is-marginless is-centered is-mobile">
                <div class="column is-11">

                    <div class="columns is-mobile">
                        <div class="column">
                            <h1 class="title is-3">Proyecto: {{ $Proyecto->clave }}</h1>
                        </div>
                    </div>
                    <hr class="is-marginless">
                    {{ Breadcrumbs::render('cat.proyectos.show', $Proyecto) }}

                    <div class="columns">
                        <div class="column">
                            <div class="buttons">
                                <ul class="btn-grp gutter-20px gutter-vr-20px">
                                    <li> <a href="{{ route('cat.proyectos.edit', Crypt::encrypt($Proyecto->idProyecto)) }}"
                                            class="badge badge-outline badge-info">Editar Proyecto</a></li>
                                    @if($Proyecto->estatus == 1)
                                    <li>
                                        <form id="delete-form"
                                            action="{{ route('cat.proyectos.destroy', Crypt::encrypt($Proyecto->idProyecto)) }}"
                                            method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button id="button-delete" type="button"
                                                class="badge badge-outline badge-danger delete2">Baja Proyecto</button>
                                        </form>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <table class="table table is-bordered is-striped is-hoverable is-fullwidth">
                        <tbody>
                            <tr>
                                <td>Clave</td>
                                <td>{{ $Proyecto->clave }}</td>
                            </tr>
                            <tr>
                                <td>Nombre</td>
                                <td>{{ $Proyecto->nombre }}</td>
                            </tr>
                            <tr>
                                <td>Descripción</td>
                                <td>{{ $Proyecto->descripcion }}</td>
                            </tr>
                            {{-- <tr>
                                <td>Tipo</td>
                                <td>{{ $Proyecto->tipo }}</td>
                            </tr> --}}
                            <tr>
                                <td>Presupuesto</td>
                                <td> $ {{number_format($Proyecto->presupuesto, 2)}}</td>
                            </tr>
                            <tr>
                                <td># Cajones de Estacionamiento</td>
                                <td>{{ number_format($Proyecto->numCajones,0) }}</td>
                            </tr>
                            <tr>
                                <td># Cajones de Estacionamiento Restantes</td>
                                <td>{{ $Proyecto->numCajonesRestantes }}</td>
                            </tr>
                            <tr>
                                <td>Fecha Inicio Proyecto</td>
                                {{-- <td>{{ $Proyecto->fechaIniProyecto }}</td> --}}
                                <td>{{ date("d/m/Y",strtotime($Proyecto->fechaIniProyecto)) }}</td>
                            </tr>
                            <tr>
                                <td>Fecha Fin Proyecto</td>
                                <td>{{ date("d/m/Y",strtotime($Proyecto->fechaFinProyecto)) }}</td>
                            </tr>
                            <tr>
                                <td>Niveles Totales</td>
                                <td>{{ number_format($Proyecto->nivelesTotales, 0) }}</td>
                            </tr>
                            <tr>
                                <td>MT2</td>
                                <td>{{ number_format($Proyecto->mt2, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Dirección</td>
                                <td>{{ $Proyecto->direccion }}</td>
                            </tr>
                            <tr>
                                <td>Colonia</td>
                                <td>{{ $Proyecto->colonia }}</td>
                            </tr>
                            <tr>
                                <td>Ciudad</td>
                                <td>{{ $Proyecto->ciudadMunicipio }}</td>
                            </tr>
                            <tr>
                                <td>Estado</td>
                                <td>{{ $Proyecto->estado }}</td>
                            </tr>
                            <tr>
                                <td>País</td>
                                <td>{{ $Proyecto->pais }}</td>
                            </tr>
                            <tr>
                                <td>Categoría</td>
                                <td>{{ $Proyecto->getCategoria != null ?  $Proyecto->getCategoria->nombre : ''}}</td>
                            </tr>
                            <tr>
                                <td>Grupo</td>
                                <td>{{ $Proyecto->getGrupo != null ? $Proyecto->getGrupo->nombre : ''}}</td>
                            </tr>
                            <tr>
                                <td>Estatus</td>
                                <td>{{ $Proyecto->estatus == '1' ? 'Alta' : 'Baja' }}</td>
                            </tr>
                            <tr>
                                <td>Usuario que lo Registró</td>
                                <td>{{ $Proyecto->getUser != null ? $Proyecto->getUser->user_name : null }}</td>
                            </tr>
                            <tr>
                                <td>Fecha Creación</td>
                                <td>{{ $Proyecto->fechaAlta }}</td>
                            </tr>
                            <tr>
                                <td>Última Actualización</td>
                                <td>{{ $Proyecto->fechaCambio }}</td>
                            </tr>
                            <tr>
                                <td>Fecha de Baja</td>
                                <td>{{ $Proyecto->fechaBaja }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </section>
    </main>



@endsection
