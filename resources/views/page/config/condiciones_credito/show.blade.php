@extends('layouts.layout')
@section('title', $condicion->nombreCondicion)



@section('content')
<main class="ath-body">
<section class="bg-white">

    <div class="columns is-marginless is-centered is-mobile">
      <div class="column is-11">

        <div class="columns is-mobile">
          <div class="column">
            <h1 class="title is-3">Condición: {{$condicion->nombreCondicion}}</h1>
          </div>
        </div>
        <hr class="is-marginless">
        {{ Breadcrumbs::render('config.condiciones-credito.show', $condicion) }}

        <div class="columns">
          <div class="column">
              <div class="buttons">
                <ul class="btn-grp gutter-20px gutter-vr-20px">
                    <li> <a href="{{route('config.condiciones-credito.edit', Crypt::encrypt($condicion->idCondicionesc))}}" class="badge badge-outline badge-info">Editar condición</a></li>
                    @if($condicion->estatus == 1)
                    <li><form id="delete-form" action="{{ route('config.condiciones-credito.destroy', Crypt::encrypt($condicion->idCondicionesc)) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button id="button-delete" type="button" class="badge badge-outline badge-danger delete2">Baja Condición</button>
                      </form></li>
                    @endif
                </ul>
              </div>
          </div>
        </div>
            <table class="table table is-bordered is-striped is-hoverable is-fullwidth">
              <tbody>
                <tr>
                  <td>Nombre</td>
                  <td>{{ $condicion->nombreCondicion }}</td>
                </tr>
                <tr>
                  <td>Tipo de Condición</td>
                  <td>{{ $condicion->tipoCondicion }}</td>
                </tr>
                <tr>
                    <td>Días de Vencimiento</td>
                    <td>{{ $condicion->diasVencimiento }}</td>
                </tr>
                <tr>
                    <td>Tipo de Días</td>
                    <td>{{ $condicion->tipoDias }}</td>
                </tr>
                <tr>
                    <td>Días Hábiles</td>
                    <td>{{ $condicion->diasHabiles }}</td>
                </tr>
                {{-- <tr>
                    <td>Método de pago SAT</td>
                    <td >{{$condicion->metodoPago == null 
                      ? $condicion->metodoPago : $condicion->getMetodoPago->descripcion}} </td>
                </tr> --}}
                <tr>
                  <td>Estatus</td>
                  <td>{{ ($condicion->estatus) == 1 ? 'Alta':'Baja' }}</td>
                </tr>
                <tr>
                    <td>Usuario</td>
                    <td>{{ $condicion->user_id == null ? $condicion->user_id : $condicion->getUser->user_name }}</td>
                </tr>
                <tr>
                  <td>Fecha Creación</td>
                  <td>{{$condicion->fecha_Alta}}</td>
                </tr>
                <tr>
                  <td>Última Actualización</td>
                  <td>{{$condicion->fecha_Cambio}}</td>
                </tr>
                <tr>
                  <td>Fecha de Baja</td>
                  <td>{{$condicion->fecha_Baja}}</td>
                </tr>
              </tbody>
            </table>
        </div>
      </div>
    </div>
</section>
</main>



@endsection

