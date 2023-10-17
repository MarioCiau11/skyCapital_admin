@extends('layouts.layout')
@section('title', $Institucion->nombre)

@section('content')

    <main class="ath-body">
        <section class="bg-white">

            <div class="columns is-marginless is-centered is-mobile">
                <div class="column is-11">

                    <div class="columns is-mobile">
                        <div class="column">
                            <h1 class="title is-3">Institución: {{ $Institucion->nombre }}</h1>
                        </div>
                    </div>
                    <hr class="is-marginless">
                    {{ Breadcrumbs::render('cat.instituciones.show', $Institucion) }}

                    <div class="columns">
                        <div class="column">
                            <div class="buttons">
                                <ul class="btn-grp gutter-20px gutter-vr-20px">
                                    <li> <a href="{{ route('cat.instituciones.edit', Crypt::encrypt($Institucion->idInstitucionf)) }}"
                                            class="badge badge-outline badge-info">Editar Institución</a></li>
                                    @if($Institucion->estatus == 1)
                                    <li>
                                        <form id="delete-form"
                                            action="{{ route('cat.instituciones.destroy', Crypt::encrypt($Institucion->idInstitucionf)) }}"
                                            method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button id="button-delete" type="button"
                                                class="badge badge-outline badge-danger delete2">Eliminar Institución</button>
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
                                <td>{{ $Institucion->clave }}</td>
                            </tr>
                            <tr>
                                <td>Nombre</td>
                                <td>{{ $Institucion->nombre }}</td>
                            </tr>
                            <tr>
                                <td>Estatus</td>
                                <td>{{ $Institucion->estatus == 1 ? 'Alta' : 'Baja' }}</td>
                            </tr>
                            <tr>
                                <td>Ciudad</td>
                                <td>{{ $Institucion->ciudad }}</td>
                            </tr>
                            <tr>
                                <td>Estado</td>
                                <td>{{ $Institucion->estado }}</td>
                            </tr>
                            <tr>
                                <td>País</td>
                                <td>{{ $Institucion->pais }}</td>
                            </tr>
                            <tr>
                                <td>Usuario que lo Registró</td>
                                <td>{{ $Institucion->getUser != null ? $Institucion->getUser->user_name : null }}</td>
                            </tr>
                            <tr>
                                <td>Fecha Creación</td>
                                <td>{{ $Institucion->fechaAlta }}</td>
                            </tr>
                            <tr>
                                <td>Última Actualización</td>
                                <td>{{ $Institucion->fechaCambio }}</td>
                            </tr>
                            <tr>
                                <td>Fecha de Baja</td>
                                <td>{{ $Institucion->fechaBaja }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </section>
    </main>



@endsection
