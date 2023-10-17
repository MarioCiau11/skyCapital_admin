@extends('layouts.layout')
@section('title', $Etiqueta->nombre)

@section('content')

    <main class="ath-body">
        <section class="bg-white">

            <div class="columns is-marginless is-centered is-mobile">
                <div class="column is-11">

                    <div class="columns is-mobile">
                        <div class="column">
                            <h1 class="title is-3">Etiqueta: {{ $Etiqueta->nombre }}</h1>
                        </div>
                    </div>
                    <hr class="is-marginless">
                    {{ Breadcrumbs::render('cat.etiquetas.show', $Etiqueta) }}

                    <div class="columns">
                        <div class="column">
                            <div class="buttons">
                                <ul class="btn-grp gutter-20px gutter-vr-20px">
                                    <li> <a href="{{ route('cat.etiquetas.edit', Crypt::encrypt($Etiqueta->idEtiqueta)) }}"
                                            class="badge badge-outline badge-info">Editar Etiqueta</a></li>
                                    @if($Etiqueta->estatus == 1)
                                    <li>
                                        <form id="delete-form"
                                            action="{{ route('cat.etiquetas.destroy', Crypt::encrypt($Etiqueta->idEtiqueta)) }}"
                                            method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button id="button-delete" type="button"
                                                class="badge badge-outline badge-danger delete2">Baja Etiqueta</button>
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
                                <td>{{ $Etiqueta->clave }}</td>
                            </tr>
                            <tr>
                                <td>Nombre</td>
                                <td>{{ $Etiqueta->nombre }}</td>
                            </tr>
                            <tr>
                                <td>Estatus</td>
                                <td>{{ $Etiqueta->estatus == 1 ? 'Alta' : 'Baja' }}</td>
                            </tr>
                            <tr>
                                <td>Usuario que lo Registró</td>
                                <td>{{ $Etiqueta->getUser != null ? $Etiqueta->getUser->user_name : null }}</td>
                            </tr>
                            <tr>
                                <td>Fecha Creación</td>
                                <td>{{ $Etiqueta->fechaAlta }}</td>
                            </tr>
                            <tr>
                                <td>Última Actualización</td>
                                <td>{{ $Etiqueta->fechaCambio }}</td>
                            </tr>
                            <tr>
                                <td>Fecha de Baja</td>
                                <td>{{ $Etiqueta->fechaBaja }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </section>
    </main>



@endsection
