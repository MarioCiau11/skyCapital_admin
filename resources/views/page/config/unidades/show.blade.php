@extends('layouts.layout')
@section('title', $Unidades->unidad)

@section('content')
    <main class="ath-body">
        <section class="bg-white">

            <div class="columns is-marginless is-centered is-mobile">
                <div class="column is-11">

                    <div class="columns is-mobile">
                        <div class="column">
                            <h1 class="title is-3">Unidad: {{ $Unidades->unidad }}</h1>
                        </div>
                    </div>
                    <hr class="is-marginless">

                    {{ Breadcrumbs::render('config.unidades.show', $Unidades) }}

                    <div class="columns">
                        <div class="column">
                            <div class="buttons">
                                <ul class="btn-grp gutter-20px gutter-vr-20px">
                                    <li> <a href="{{ route('config.unidades.edit', Crypt::encrypt($Unidades->idUnidades)) }}"
                                        class="badge badge-outline badge-info">Editar Unidad</a></li>
                                    @if ($Unidades->estatus == 1)
                                    <li>
                                        <form id="delete-form"
                                            action="{{ route('config.unidades.destroy', Crypt::encrypt($Unidades->idUnidades)) }}"
                                            method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button id="button-delete" type="button"
                                                class="badge badge-outline badge-danger delete2">Baja Unidad</button>
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
                                <td>Unidad</td>
                                <td>{{ $Unidades->unidad }}</td>
                            </tr>
                            <tr>
                                <td>Decimal Válida</td>
                                <td>{{ $Unidades->decimalValida }}</td>
                            </tr>
                            <tr>
                                <td>Estatus</td>
                                <td>{{ $Unidades->estatus == 1 ? 'Alta' : 'Baja' }}</td>
                            </tr>
                            <tr>
                                <td>Usuario que lo Registró</td>
                                <td>{{ $Unidades->userRelation->username}}</td>
                            </tr>
                            <tr>
                                <td>Fecha Creación</td>
                                <td>{{ $Unidades->fechaAlta }}</td>
                            </tr>
                            <tr>
                                <td>Última Actualización</td>
                                <td>{{ $Unidades->fechaCambio }}</td>
                            </tr>
                            <tr>
                                <td>Fecha de Baja</td>
                                <td>{{ $Unidades->fecha_Baja }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </section>
    </main>



@endsection
