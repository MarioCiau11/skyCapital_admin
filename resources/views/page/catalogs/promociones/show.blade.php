@extends('layouts.layout')
@section('title', $Promocion->nombre)

@section('content')

    <main class="ath-body">
        <section class="bg-white">

            <div class="columns is-marginless is-centered is-mobile">
                <div class="column is-11">

                    <div class="columns is-mobile">
                        <div class="column">
                            <h1 class="title is-3">Promoción: {{ $Promocion->nombre }}</h1>
                        </div>
                    </div>
                    <hr class="is-marginless">
                    {{ Breadcrumbs::render('cat.promociones.show', $Promocion) }}

                    <div class="columns">
                        <div class="column">
                            <div class="buttons">
                                <ul class="btn-grp gutter-20px gutter-vr-20px">
                                    <li> <a href="{{ route('cat.promociones.edit', Crypt::encrypt($Promocion->idPromocion)) }}"
                                            class="badge badge-outline badge-info">Editar promoción</a></li>
                                    @if($Promocion->estatus == 1)        
                                    <li>
                                        <form id="delete-form"
                                            action="{{ route('cat.promociones.destroy', Crypt::encrypt($Promocion->idPromocion)) }}"
                                            method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button id="button-delete" type="button"
                                                class="badge badge-outline badge-danger delete2">Baja promoción</button>
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
                                <td>{{ $Promocion->clave }}</td>
                            </tr>
                            <tr>
                                <td>Nombre</td>
                                <td>{{ $Promocion->nombre }}</td>
                            </tr>
                            <tr>
                                <td>Estatus</td>
                                <td>{{ $Promocion->estatus == 1 ? 'Alta' : 'Baja' }}</td>
                            </tr>
                            <tr>
                                <td>Usuario que lo Registró</td>
                                <td>{{ $Promocion->getUser != null ? $Promocion->getUser->user_name : null }}</td>
                            </tr>
                            <tr>
                                <td>Fecha Creación</td>
                                <td>{{ $Promocion->fechaAlta }}</td>
                            </tr>
                            <tr>
                                <td>Última Actualización</td>
                                <td>{{ $Promocion->fechaCambio }}</td>
                            </tr>
                            <tr>
                                <td>Fecha de Baja</td>
                                <td>{{ $Promocion->fechaBaja }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </section>
    </main>



@endsection
