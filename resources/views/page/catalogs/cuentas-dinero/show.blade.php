@extends('layouts.layout')

@section('content')
<section class="bg-white">

    <div class="columns is-marginless is-centered is-mobile">
        <div class="column is-11">

        <div class="columns is-mobile">
            <div class="column">
            <h1 class="title is-3">Cuenta de Dinero: {{$CuentasDinero->clave}}</h1>
            </div>
        </div>
        <hr class="is-marginless">
        {{ Breadcrumbs::render('cat.cuentas-dinero.show', $CuentasDinero) }}

        <div class="columns">
            <div class="column">
                <div class="buttons">
                <ul class="btn-grp gutter-20px gutter-vr-20px">
                    <li> <a href="{{route('cat.cuentas-dinero.edit', Crypt::encrypt($CuentasDinero->idCuentasDinero))}}" class="badge badge-outline badge-info">Editar Cuenta</a></li>
                    <li><form id="delete-form" action="{{ route('cat.cuentas-dinero.destroy', Crypt::encrypt($CuentasDinero->idCuentasDinero)) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        @if ($CuentasDinero->estatus != 0)
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
                    <td>{{ $CuentasDinero->clave }}</td>
                </tr>
                <tr>
                    <td>Banco</td>
                    <td >{{$CuentasDinero->idInstitucionf != null ?$CuentasDinero->getInstituciones->nombre : null }}</td>
                </tr>
                <tr>
                    <td>No. de Cuenta</td>
                    <td>{{ $CuentasDinero->noCuenta }}</td>
                </tr>
                <tr>
                    <td>Cuenta Clave</td>
                    <td>{{ $CuentasDinero->cuentaClave }}</td>
                </tr>
                <tr>
                    <td>Referencia del Banco</td>
                    <td>{{ $CuentasDinero->referenciaBanco }}</td>
                </tr>
                <tr>
                    <td>Convenio del Banco</td>
                    <td>{{ $CuentasDinero->convenioBanco }}</td>
                </tr>
                <tr>
                    <td>Tipo de Cuenta</td>
                    <td>{{ $CuentasDinero->tipoCuenta }}</td>
                </tr>
                <tr>
                    <td>Moneda</td>
                    <td>{{ $CuentasDinero->idMoneda != null ? $CuentasDinero->getMonedas->clave : null }}</td>
                </tr>
                <tr>
                    <td>Empresa</td>
                    <td >{{$CuentasDinero->idEmpresa != null ? $CuentasDinero->getEmpresas->nombreEmpresa : null}} </td>
                </tr>
                <tr>
                    <td>Usuario</td>
                    {{-- <td>{{ $CuentasDinero->getUser->username}}</td> --}}
                    <td>{{ $CuentasDinero->user_id}}</td>
                </tr>
                <tr>
                    <td>Estatus</td>
                    <td>{{ ($CuentasDinero->estatus) == 1 ? 'Alta':'Baja' }}</td>
                </tr>
                <tr>
                    <td>Fecha Creación</td>
                    <td>{{$CuentasDinero->fechaAlta}}</td>
                </tr>
                <tr>
                    <td>Última Actualización</td>
                    <td>{{$CuentasDinero->fechaCambio}}</td>
                </tr>
                <tr>
                    <td>Fecha de Baja</td>
                    <td>{{$CuentasDinero->fechaBaja}}</td>
                </tr>
                </tbody>
            </table>
        </div>
        </div>
    </div>
</section>
@endsection