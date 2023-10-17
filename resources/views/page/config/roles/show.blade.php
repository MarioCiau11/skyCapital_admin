@extends('layouts.layout')
@section('title', $rol->name)



@section('content')
<main class="ath-body">
<section class="bg-white">

    <div class="columns is-marginless is-centered is-mobile">
      <div class="column is-11">

        <div class="columns is-mobile">
          <div class="column">
            <h1 class="title is-3">Rol: {{$rol->identifier}}</h1>
          </div>
        </div>
        <hr class="is-marginless">
        {{ Breadcrumbs::render('config.roles.show', $rol) }}

        <div class="columns">
          <div class="column">
              <div class="buttons">
                <ul class="btn-grp gutter-20px gutter-vr-20px">
                    <li> <a href="{{route('config.roles.edit', Crypt::encrypt($rol->id))}}" class="badge badge-outline badge-info">Editar rol</a></li>
                    @if($rol->status == 1)
                    <li><form id="delete-form" action="{{ route('config.roles.destroy', Crypt::encrypt($rol->id)) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button id="button-delete" type="button" class="badge badge-outline badge-danger delete2">Baja rol</button>
                      </form></li>
                    @endif
                </ul>

                   
                    

              </div>
          </div>
        </div>

            <table class="table table is-bordered is-striped is-hoverable is-fullwidth">
              <tbody>
                <tr>
                  <td>Identificador</td>
                  <td>{{ $rol->identifier }}</td>
                </tr>
                <tr>
                  <td>Nombre</td>
                  <td>{{ $rol->name }}</td>
                </tr>
                <tr>
                  <td>Estatus</td>
                  <td>{{ ($rol->status) == 1 ? 'Alta':'Baja' }}</td>
                </tr>
                <tr>
                  <td>Creado</td>
                  <td>{{$rol->created_at}}</td>
                </tr>
                <tr>
                  <td>Actualizado</td>
                  <td>{{$rol->updated_at}}</td>
                </tr>
                <tr>
                  <td>Permisos</td>
                  <td>
                    <div class="">
                        <div class="col-sm-8 col-md-12">
                              
                            <!-- Nav tabs -->
                            <ul class="nav tab-nav tab-nav-btn">
                                @if (isset($categorias))
                                    @foreach ($categorias as $categoria)
                                        <li class="{{ $loop->first ? 'active' : '' }}">
                                            <a href="#{{ str_replace(' ', '', $categoria) }}" data-toggle="tab">
                                                {{ $categoria }}
                                            </a>
                                        </li>
                                    @endforeach
                            @endif
                            </ul>
                        
                            <!-- Tab panes -->
                            <div class="tab-content nopadding noborder">
                
                                @if (isset($categorias))
                                    @foreach ($categorias as $categoria)
                                        <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="{{ str_replace(' ', '', $categoria) }}">
                                            <div class="checkbox-container">
                                                @foreach ($categoriasPermisos[$categoria] as $permisosPorCategoria)
                                                    <div class="checkbox">
                                                        {!! Form::checkbox('permisos[]', $permisosPorCategoria['id'], in_array($permisosPorCategoria['id'], $rolePermissions) ? true : false, ['class' => 'setCheckBox', 'disabled' => 'disabled']) !!}
                                                        {!! Form::label($permisosPorCategoria['name']) !!}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                            @endif                            
                        </div><!-- tab-content -->
                          
                        </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>

        </div>
      </div>
    </div>
</section>
</main>



@endsection
