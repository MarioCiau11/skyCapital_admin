@extends('layouts.layout')
@section('title', $Sucursal->nombre)

@section('content')

    <main class="ath-body">
        <section class="bg-white">

            <div class="columns is-marginless is-centered is-mobile">
                <div class="column is-11">

                    <div class="columns is-mobile">
                        <div class="column">
                            <h1 class="title is-3">Sucursal: {{ $Sucursal->nombre }}</h1>
                        </div>
                    </div>
                    <hr class="is-marginless">
                    {{ Breadcrumbs::render('cat.sucursales.show', $Sucursal) }}

                    <div class="columns">
                        <div class="column">
                            <div class="buttons">
                                <ul class="btn-grp gutter-20px gutter-vr-20px">
                                    <li> <a href="{{ route('cat.sucursales.edit', Crypt::encrypt($Sucursal->idSucursal)) }}"
                                            class="badge badge-outline badge-info">Editar Sucursal</a></li>
                                    @if($Sucursal->estatus == 1)
                                    <li>
                                        <form id="delete-form"
                                            action="{{ route('cat.sucursales.destroy', Crypt::encrypt($Sucursal->idSucursal)) }}"
                                            method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button id="button-delete" type="button"
                                                class="badge badge-outline badge-danger delete2">Baja Sucursal</button>
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
                                <td>{{ $Sucursal->clave }}</td>
                            </tr>
                            <tr>
                                <td>Nombre</td>
                                <td>{{ $Sucursal->nombre }}</td>
                            </tr>
                            <tr>
                                <td>Estatus</td>
                                <td>{{ $Sucursal->estatus == 1 ? 'Alta' : 'Baja' }}</td>
                            </tr>
                            <tr>
                                <td>Usuario que lo Registró</td>
                                <td>{{ $Sucursal->getUser != null ? $Sucursal->getUser->user_name : null }}</td>
                            </tr>
                            <tr>
                                <td>Dirección</td>
                                <td>{{ $Sucursal->direccion }}</td>
                            </tr>
                            <tr>
                                <td>Colonia</td>
                                <td>{{ $Sucursal->colonia }}</td>
                            </tr>
                            <tr>
                                <td>Código Postal</td>
                                <td>{{ $Sucursal->codigoPostal }}</td>
                            </tr>
                            <tr>
                                <td>Ciudad</td>
                                <td>{{ $Sucursal->ciudad }}</td>
                            </tr>
                            <tr>
                                <td>Estado</td>
                                <td>{{ $Sucursal->estado }}</td>
                            </tr>
                            <tr>
                                <td>País</td>
                                <td>{{ $Sucursal->pais }}</td>
                            </tr>
                            <tr>
                                <td>Fecha Creación</td>
                                <td>{{ $Sucursal->fechaAlta }}</td>
                            </tr>
                            <tr>
                                <td>Última Actualización</td>
                                <td>{{ $Sucursal->fechaCambio }}</td>
                            </tr>
                            <tr>
                                <td>Fecha de Baja</td>
                                <td>{{ $Sucursal->fechaBaja }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </section>
    </main>



@endsection
