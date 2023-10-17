@extends('layouts.layout')
@section('title', $user->user_name)



@section('content')
<main class="ath-body">
<section class="bg-white">

    <div class="columns is-marginless is-centered is-mobile">
      <div class="column is-11">

        <div class="columns is-mobile">
          <div class="column">
            <h1 class="title is-3">Usuario: {{$user->user_name}}</h1>
          </div>
        </div>
        <hr class="is-marginless">
        {{ Breadcrumbs::render('config.usuarios.show', $user) }}

        <div class="columns">
          <div class="column">
              <div class="buttons">
                <ul class="btn-grp gutter-20px gutter-vr-20px">
                    <li> <a href="{{route('config.usuarios.edit', Crypt::encrypt($user->user_id))}}" class="badge badge-outline badge-info">Editar usuario</a></li>
                    @if($user->user_status == 1)
                    <li><form id="delete-form" action="{{ route('config.usuarios.destroy', Crypt::encrypt($user->user_id)) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button id="button-delete" type="button" class="badge badge-outline badge-danger delete2">Baja usuario</button>
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
                  <td>{{ $user->user_name }}</td>
                </tr>
                <tr>
                  <td>Usuario</td>
                  <td>{{ $user->username }}</td>
                </tr>
                <tr>
                  <td>Correo Electrónico</td>
                  <td>{{ $user->email }}</td>
                </tr>
                <tr>
                  <td>Estatus</td>
                  <td>{{ ($user->user_status) == 1 ? 'Alta':'Baja' }}</td>
                </tr>
          
                <tr>
                  <td>Rol</td>
                  <td>{{$user->user_rol}}</td>
                </tr>
                <tr>
                  <td>Empresa(s)</td>
                  <td>
                    <div class="tags">
                      @foreach ($user->company()->get() as $company)
                            <span class="badge badge-light badge-sm">{{$company->nombreEmpresa}}</span>
                      @endforeach
                    </div>
                  </td>
                </tr>
                <tr>
                  <td>Creado</td>
                  <td>{{$user->created_at}}</td>
                </tr>
                <tr>
                  <td>Actualizado</td>
                  <td>{{$user->updated_at}}</td>
                </tr>
              </tbody>
            </table>

        </div>
      </div>
    </div>
</section>
</main>



@endsection
