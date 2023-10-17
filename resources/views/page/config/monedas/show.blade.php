@extends('layouts.layout')
@section('title', $Monedas->nombre)

@section('content')

    <main class="ath-body">
        <section class="bg-white">

            <div class="columns is-marginless is-centered is-mobile">
                <div class="column is-11">

                    <div class="columns is-mobile">
                        <div class="column">
                            <h1 class="title is-3">Moneda: {{ $Monedas->nombre }}</h1>
                        </div>
                    </div>
                    <hr class="is-marginless">
                    {{ Breadcrumbs::render('config.monedas.show', $Monedas) }}

                    <div class="columns">
                        <div class="column">
                            <div class="buttons">
                                <ul class="btn-grp gutter-20px gutter-vr-20px">
                                    <li> <a href="{{ route('config.monedas.edit', Crypt::encrypt($Monedas->idMoneda)) }}"
                                            class="badge badge-outline badge-info">Editar Moneda</a></li>
                                    @if($Monedas->estatus == 1)
                                    <li>
                                        <form id="delete-form"
                                            action="{{ route('config.monedas.destroy', Crypt::encrypt($Monedas->idMoneda)) }}"
                                            method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button id="button-delete" type="button"
                                                class="badge badge-outline badge-danger delete2">Baja Moneda</button>
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
                                <td>{{ $Monedas->clave }}</td>
                            </tr>
                            <tr>
                                <td>Nombre</td>
                                <td>{{ $Monedas->nombre }}</td>
                            </tr>
                            <tr>
                                <td>Descripción</td>
                                <td>{{ $Monedas->descripcion }}</td>
                            </tr>
                            <tr>
                                <td>Tipo de Cambio</td>
                                <td>{{ $Monedas->tipoCambio }}</td>
                            </tr>

                            <tr>
                                <td>Estatus</td>
                                <td>{{ $Monedas->estatus == 1 ? 'Alta' : 'Baja' }}</td>
                            </tr>
                            <tr>
                                <td>Usuario que lo Registró</td>
                                <td>{{ $Monedas->userRelation->username }}</td>
                            </tr>
                            <tr>
                                <td>Fecha Creación</td>
                                <td>{{ $Monedas->fechaAlta }}</td>
                            </tr>
                            <tr>
                                <td>Última Actualización</td>
                                <td>{{ $Monedas->fechaCambio }}</td>
                            </tr>
                            <tr>
                                <td>Fecha de Baja</td>
                                <td>{{ $Monedas->fechaBaja }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </section>
    </main>



@endsection
