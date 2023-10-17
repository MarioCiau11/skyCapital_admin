
@extends('layouts.layout')
@section('title', $formasPago->nombre)

@section('content')
<main class="ath-body">
<section class="bg-white">

    <div class="columns is-marginless is-centered is-mobile">
      <div class="column is-11">

        <div class="columns is-mobile">
          <div class="column">
            <h1 class="title is-3">Forma de Pago: {{$formasPago->nombre}}</h1>
          </div>
        </div>
        <hr class="is-marginless">
        {{ Breadcrumbs::render('config.formas-pago.show', $formasPago) }}

        <div class="columns">
          <div class="column">
              <div class="buttons">
                <ul class="btn-grp gutter-20px gutter-vr-20px">
                    <li> <a href="{{route('config.formas-pago.edit', Crypt::encrypt($formasPago->idFormaspc))}}" class="badge badge-outline badge-info">Editar Forma de pago</a></li>
                    @if($formasPago->estatus == 1)
                    <li><form id="delete-form" action="{{ route('config.formas-pago.destroy', Crypt::encrypt($formasPago->idFormaspc)) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button id="button-delete" type="button" class="badge badge-outline badge-danger delete2">Baja forma de pago</button>
                      </form></li>
                      @endif
                </ul>
              </div>
          </div>
        </div>
            <table class="table table is-bordered is-striped is-hoverable is-fullwidth">
              <tbody>
                <tr>
                  <td>Clave</td>
                  <td>{{ $formasPago->clave }}</td>
                </tr>
                <tr>
                  <td>Nombre</td>
                  <td>{{ $formasPago->nombre }}</td>
                </tr>
                <tr>
                  <td>Descripción</td>
                  <td>{{ $formasPago->descripcion }}</td>
                </tr>
                <tr>
                    <td>Moneda</td>
                    <td>{{ $formasPago->monedaSat}}</td>
                </tr>
                <tr>
                  <td>Estatus</td>
                  <td>{{ ($formasPago->estatus) == 1 ? 'Alta':'Baja' }}</td>
                </tr>
                <tr>
                    <td>Usuario que lo Registró</td>
                    <td>{{ $formasPago->userRelation->username}}</td>
                </tr>
                <tr>
                  <td>fecha Creación</td>
                  <td>{{$formasPago->fecha_Alta}}</td>
                </tr>
                <tr>
                  <td>Última Actualización</td>
                  <td>{{$formasPago->fecha_Cambio}}</td>
                </tr>
                <tr>
                  <td>Fecha Baja</td>
                  <td>{{$formasPago->fecha_Baja}}</td>
                </tr>
              </tbody>
            </table>
        </div>
      </div>
    </div>
</section>
</main>



@endsection
