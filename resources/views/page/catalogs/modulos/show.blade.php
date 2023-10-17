@extends('layouts.layout')
@section('title', $Modulo->clave)

@php
// dd($Modulo->getBank);
@endphp

@section('content')

    <main class="ath-body">
        <section class="bg-white">

            <div class="columns is-marginless is-centered is-mobile">
                <div class="column is-11">

                    <div class="columns is-mobile">
                        <div class="column">
                            <h1 class="title is-3">Módulo: {{ $Modulo->clave }}</h1>
                        </div>
                    </div>
                    <hr class="is-marginless">
                    {{ Breadcrumbs::render('cat.modulos.show', $Modulo) }}

                    <div class="columns">
                        <div class="column">
                            <div class="buttons">
                                <ul class="btn-grp gutter-20px gutter-vr-20px">
                                    <li> <a href="{{ route('cat.modulos.edit', Crypt::encrypt($Modulo->idModulo)) }}"
                                            class="badge badge-outline badge-info">Editar Módulo</a></li>
                                    @if($Modulo->estatus != 'Baja')
                                    <li>
                                        <form id="delete-form"
                                            action="{{ route('cat.modulos.destroy', Crypt::encrypt($Modulo->idModulo)) }}"
                                            method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button id="button-delete" type="button"
                                                class="badge badge-outline badge-danger delete2">Baja Módulo</button>
                                        </form>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mg-center">
                        <table class="table table is-bordered is-striped is-hoverable is-fullwidth" style="border-collapse: collapse;">
                            <tbody>
                                <tr>
                                    <td>Módulo</td>
                                    <td>{{ $Modulo->clave }}</td>
                                </tr>
                                <tr>
                                    <td>Descripción</td>
                                    <td>{{ $Modulo->descripcion }}</td>
                                </tr>
                                <tr>
                                    <td>Tipo</td>
                                    <td>{{ $Modulo->tipo }}</td>
                                </tr>
                                <tr>
                                    <td>Proyecto</td>
                                    <td>{{ $Modulo->proyecto ? $Modulo->getProyectos->nombre : null}}</td>
                                </tr>
                                <tr>
                                    <td>MT2</td>
                                    <td>{{ number_format($Modulo->mt2, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Valor Operación</td>
                                    <td>${{ number_format($Modulo->valorOperacion, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Nivel/Piso</td>
                                    <td>{{ number_format($Modulo->nivelPiso,0) }}</td>
                                </tr>
                                <tr>
                                    <td># Cajones de Estacionamiento</td>
                                    <td>{{ number_format($Modulo->numCajones,0) }}</td>
                                </tr>
                                <tr>
                                    <td>Estatus</td>
                                    <td>{{ $Modulo->estatus }}</td>
                                </tr>
                                <tr>
                                    <td>Banco</td>
                                    <td>{{ $Modulo->getBank != null ? $Modulo->getBank->clave : ''}}</td>
                                </tr>
                                <tr>
                                    <td>Cuenta</td>
                                    <td>{{ $Modulo->cuenta }}</td>
                                </tr>
                                <tr>
                                    <td>Clabe</td>
                                    <td>{{ $Modulo->clabe }}</td>
                                </tr>
                                <tr>
                                    <td>Usuario que lo Registró</td>
                                    <td>{{ $Modulo->getUser != null ? $Modulo->getUser->user_name : null }}</td>
                                </tr>
                                <tr>
                                    <td>Fecha Creación</td>
                                    <td>{{ $Modulo->fechaAlta }}</td>
                                </tr>
                                <tr>
                                    <td>Última Actualización</td>
                                    <td>{{ $Modulo->fechaCambio }}</td>
                                </tr>
                                <tr>
                                    <td>Fecha de Baja</td>
                                    <td>{{ $Modulo->fechaBaja }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
              
                </div>
            </div>
            </div>
        </section>
    </main>



@endsection
