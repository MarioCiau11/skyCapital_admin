
@extends('layouts.layout')
@section('title', $ConceptosMod->nombreConcepto)


@section('content')
<main class="ath-body">
<section class="bg-white">

    <div class="columns is-marginless is-centered is-mobile">
      <div class="column is-11">

        <div class="columns is-mobile">
          <div class="column">
            <h1 class="title is-3">Concepto: {{$ConceptosMod->nombreConcepto}}</h1>
          </div>
        </div>
        <hr class="is-marginless">
        {{ Breadcrumbs::render('config.conceptos-modulos.show', $ConceptosMod) }}

        <div class="columns">
          <div class="column">
              <div class="buttons">
                <ul class="btn-grp gutter-20px gutter-vr-20px">
                    <li> <a href="{{route('config.conceptos-modulos.edit', Crypt::encrypt($ConceptosMod->idConceptosm))}}" class="badge badge-outline badge-info">Editar concepto</a></li>
                    <li><form id="delete-form" action="{{ route('config.conceptos-modulos.destroy', Crypt::encrypt($ConceptosMod->idConceptosm)) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button id="button-delete" type="button" class="badge badge-outline badge-danger delete2">Eliminar Concepto</button>
                      </form></li>
                </ul>
              </div>
          </div>
        </div>
            <table class="table table is-bordered is-striped is-hoverable is-fullwidth">
              <tbody>
                <tr>
                  <td>Nombre del Concepto</td>
                  <td>{{ $ConceptosMod->nombreConcepto }}</td>
                </tr>
                <tr>
                  <td>Módulo</td>
                  <td>{{ $ConceptosMod->modulo }}</td>
                </tr>
                <tr>
                    <td>Movimiento</td>
                    <td>{{ $ConceptosMod->movimiento }}</td>
                </tr>
                <tr>
                    <td>clave de servicio o producto</td>
                    <td>{{ $ConceptosMod->claveServProd }}</td>
                </tr>
                <tr>
                  <td>Estatus</td>
                  <td>{{ ($ConceptosMod->estatus) == 1 ? 'Alta':'Baja' }}</td>
                </tr>
                <tr>
                    <td>Usuario</td>
                    <td>{{ $ConceptosMod->user_id}}</td>
                </tr>
                <tr>
                  <td>Fecha creación</td>
                  <td>{{$ConceptosMod->fechaAlta}}</td>
                </tr>
                <tr>
                  <td>última actualización</td>
                  <td>{{$ConceptosMod->fechaCambio}}</td>
                </tr>
                <tr>
                  <td>Fecha de Baja</td>
                  <td>{{$ConceptosMod->fechaBaja}}</td>
                </tr>
              </tbody>
            </table>
        </div>
      </div>
    </div>
</section>
</main>
@endsection

@section('extra-js')
<script src="{{asset('js/config/conceptos_modulos.js')}}"></script>
@endsection
