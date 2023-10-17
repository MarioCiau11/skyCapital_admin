<?php
// dd($rol, $categorias, $categoriasPermisos);
?>
<div class="col-md-12">
    <div class="row">
        <div class="col-lg-12">
            <p class="tc-danger mr-auto mb-0"> * = Campos Obligatorios</p>
        </div>
        <div class="col-md-4">
            <div class="ffield-item mt10">
                <label class="field-label">Identificador <span style="color:red">*</span></label>
                <div class="field-wrap">
                    <input name="identificador" type="text" class="input-bordered" id="identificador" required aria-required="true" value="{{ old('identificador') ?? $rol->identifier }}"  @if($rol->identifier) disabled @endif>
                </div>
                @if ($errors->has('identificador'))
                <div class="alert alert-danger">
                    {{ $errors->first('identificador') }}
                </div>
               @endif
            </div>
        </div>
    
    
        <div class="col-md-4">
            <div class="ffield-item mt10">
                <label class="field-label">Nombre <span style="color:red">*</span></label>
                <div class="field-wrap">
                    <input name="name" type="text" class="input-bordered" id="name" required aria-required="true" value="{{ old('name') ?? $rol->name }}">
                </div>
                @if ($errors->has('name'))
                <div class="alert alert-danger">
                    {{ $errors->first('name') }}
                </div>
            @endif
            </div>
        </div>
    
        <div class="col-md-4">
            <div class="field-item">
            <div class="field-wrap">
                {!! Form::label('selectEstatus', 'Estatus', ['class' => 'field-label']) !!}
                {!! Form::select('selectEstatus', ['1' => 'Alta', '0' => 'Baja'], $rol->status , [
                    'id' => 'selectEstatus',
                    'class' => 'widthAll select-status',
                ]) !!}
            </div>
            @if ($errors->has('selectEstatus'))
            <div class="alert alert-danger">
                {{ $errors->first('selectEstatus') }}
            </div>
            @endif
            </div>
        </div>
    
    </div> <!-- row -->

    <div class="row">
        <div class="col-md-12">
            <div class="form-group mt10">
                {!! Form::label('descripcion', 'Descripción', ['class' => 'field-label']) !!}
                {!! Form::textarea('descripcion', $rol->descript, ['class' => 'form-control', 'rows' => 4]) !!}
    
            </div>
            @if ($errors->has('descripcion'))
            <div class="alert alert-danger">
                {{ $errors->first('descripcion') }}
            </div>
            @endif
        </div>
    </div> <!-- row -->
    
    <h3 class="text-black">Permisos </h3>

    <div class="contenedorPermisos">
        <div class="item1">
            <div >
                {!! Form::checkbox('verPermisos', 'Ver permisos', [], ['id' => 'activePermisos']) !!}
                {!! Form::label('verPermiso', 'Ver permisos', '') !!}
            </div>
        </div>
        <div class="item2">
                <h5>Para los movimientos de los módulos: </h5>
                <ul class="ml-2">
                    <li> E = Edición</li> 
                    <li> C= Consulta</li>
                </ul>
        </div>
    </div>
    <div class="contenedorPermisos">
        <div class="item1">
            <div >
                {!! Form::checkbox(
                    'selectPermisos',
                    'Seleccionar todos los permisos',
                    [],
                    ['id' => 'selecPermisos'],
                ) !!}
                {!! Form::label('selectPermisos', 'Seleccionar todos los permisos', '') !!}
            </div>
        </div>
    </div>

    

    <div class="col-md-12"></div>

     <div class="view-permiso">
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
                                        {!! Form::checkbox('permisos[]', $permisosPorCategoria['id'], in_array($permisosPorCategoria['id'], $rolePermissions) ? true : false, ['class' => 'setCheckBox']) !!}
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


</div>
