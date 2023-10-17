
@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/catalogos/modulos.css') }}">
@endsection
<div class="col-lg-12 col-sm-12 col-md-12">
    <div class="col-lg-12">
        <p class="tc-danger mr-auto mb-0"> * = Campos Obligatorios</p>
    </div>
    <!-- Nav tabs -->
    <ul class="nav tab-nav tab-nav-line tab-nav-center">
        <li>
            <a href="#tab-1" data-toggle="tab" class="active">Información</a>
        </li>
        <li>
            <a href="#tab-2" data-toggle="tab">Anexos</a>
        </li>
    </ul>
        <!-- Tab panes -->
        <div class="tab-content nopadding noborder">
            <div class="tab-pane active" id="tab-1">
                <div class="nk-block-text">
                    <h3 class="text-black">Datos generales</h3>
                </div>
                <div class="row">
                    <div class="col-lg-8">
                    
                        <div class="form-group row">
                            <label for="inputModulo" class="col-sm-2 col-form-label field-label">Módulo<span style="color: red">*</span></label>
                            <div class="col-sm-4">
                              <input type="text" class="input-bordered" id="inputModulo" placeholder="Modulo" name="inputModulo" required value="{{ old('inputModulo', $Modulo->clave) ?? $Modulo->getNextID() }}" @if($Modulo->clave != null) readonly @endif>
                            </div>
                        @if ($errors->has('inputModulo'))
                        <div class="alert alert-danger">
                            {{ $errors->first('inputModulo') }}
                        </div>
                        @endif
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputDescripción" class="col-sm-2 col-form-label field-label">Descripción</label>
                            <div class="col-sm-10">
                              <textarea name="inputDescripción" id="" cols="30" rows="5" class="form-control input-bordered" value="">{{ old('inputDescripción') ?? $Modulo->descripcion }}</textarea>
                            </div>
                            @if ($errors->has('inputDescripción'))
                            <div class="alert alert-danger">
                                {{ $errors->first('inputDescripción') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label for="selectTipo" class="col-sm-2 col-form-label field-label">Tipo<span style="color: red">*</span></label>
                            <div class="col-sm-6">
                              {!! Form::select('selectTipo', ['Local' => 'Local', 'Consultorio' => 'Consultorio', 'Oficina' => 'Oficina', 'Departamento' => 'Departamento', 'Lote Comercial' => 'Lote Comercial', 'Lote Nave' => 'Lote Nave'], $Modulo->tipo, [
                                'id' => 'selectTipo',
                                'class' => '',
                                'placeholder' => 'Seleccione uno...',
                                'required' => 'required'
                            ]) !!}
                            </div>
                            @if ($errors->has('selectTipo'))
                            <div class="alert alert-danger">
                                {{ $errors->first('selectTipo') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label for="selectProyecto" class="col-sm-2 col-form-label field-label">Proyecto<span style="color: red">*</span></label>
                            <div class="col-sm-6">
                              {!! Form::select('selectProyecto', $proyectos, $Modulo->proyecto, [
                                'id' => 'selectProyecto',
                                'class' => '',
                                'placeholder' => 'Seleccione uno...',
                                'required' => 'required'
                            ]) !!}
                            </div>
                            @if ($errors->has('selectProyecto'))
                            <div class="alert alert-danger">
                                {{ $errors->first('selectProyecto') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label for="inputMT2" class="col-sm-2 col-form-label field-label">MT2<span style="color: red">*</span></label>
                            <div class="col-sm-2">
                              <input type="number" class="input-bordered" id="inputMT2" placeholder="0.0" name="inputMT2" required value="{{ old('inputMT2') ?? $Modulo->mt2 }}">
                            @if ($errors->has('inputMT2'))
                            <div class="alert alert-danger">
                                {{ $errors->first('inputMT2') }}
                            </div>
                            @endif
                            </div>
                            <label for="inputValor" class="col-sm-3 col-form-label field-label">Valor Operación<span style="color: red">*</span></label>
                            <div class="col-sm-5">
                              <input type="text" class="input-bordered currency" id="inputValor" placeholder="$ 0.0" name="inputValor" required value="{{ old('inputValor') ?? $Modulo->valorOperacion }}">
                            @if ($errors->has('inputValor'))
                            <div class="alert alert-danger">
                                {{ $errors->first('inputValor') }}
                            </div>
                            @endif
                            </div>
                        </div>

                        
                        <div class="form-group row">
                            <label for="inputNivel" class="col-sm-2 col-form-label field-label">Nivel/Piso<span style="color: red">*</span></label>
                            <div class="col-sm-2">
                              <input type="number" class="input-bordered" id="inputNivel" placeholder="0" name="inputNivel" required value="{{ old('inputNivel') ?? $Modulo->nivelPiso }}">
                            @if ($errors->has('inputNivel'))
                            <div class="alert alert-danger">
                                {{ $errors->first('inputNivel') }}
                            </div>
                            @endif
                            </div>
                            <label for="inputCajones" class="col-sm-4 col-form-label field-label"># Cajones Estacionamiento</label>
                            <div class="col-sm-4">
                              <input type="number" class="input-bordered" id="inputCajones" placeholder="0" name="inputCajones" value="{{ old('inputCajones') ?? $Modulo->numCajones }}">
                            @if ($errors->has('inputCajones'))
                            <div class="alert alert-danger">
                                {{ $errors->first('inputCajones') }}
                            </div>
                            @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="selectEstatus" class="col-sm-2 col-form-label field-label">Estatus<span style="color: red">*</span></label>
                            <div class="col-sm-4">
                              {!! Form::select('selectEstatus', ['Disponible' => 'Disponible', 'No disponible' => 'No disponible', 'Apartado' => 'Apartado', 'Vendido' => 'Vendido', 'Baja' => 'Baja'], $Modulo->estatus, [
                                'id' => 'selectEstatus',
                                'class' => '',
                            ]) !!}
                            </div>
                            @if ($errors->has('selectEstatus'))
                            <div class="alert alert-danger">
                                {{ $errors->first('selectEstatus') }}
                            </div>
                            @endif
                        </div>
                        

                        <div class="form-group row">
                            <label for="selectBanco" class="col-sm-2 col-form-label field-label">Banco</label>
                            <div class="col-sm-4">
                              {!! Form::select('selectBanco', $bancos, $Modulo->banco, [
                                'id' => 'selectBanco',
                                'class' => '',
                                'placeholder' => 'Seleccione uno...',
                            ]) !!}
                            </div>
                            @if ($errors->has('selectBanco'))
                            <div class="alert alert-danger">
                                {{ $errors->first('selectBanco') }}
                            </div>
                            @endif
                        </div>

                        
                        <div class="form-group row">
                            <label for="inputCuenta" class="col-sm-2 col-form-label field-label">Cuenta</label>
                            <div class="col-sm-4">
                              <input type="text" class="input-bordered" id="inputCuenta"  name="inputCuenta"  value="{{ old('inputCuenta') ?? $Modulo->cuenta }}">
                            </div>
                        @if ($errors->has('inputCuenta'))
                        <div class="alert alert-danger">
                            {{ $errors->first('inputCuenta') }}
                        </div>
                        @endif
                        </div>

                        <div class="form-group row">
                            <label for="inputClabe" class="col-sm-2 col-form-label field-label">Clabe</label>
                            <div class="col-sm-4">
                              <input type="text" class="input-bordered" id="inputClabe"  name="inputClabe"  value="{{ old('inputClabe') ?? $Modulo->clabe }}">
                            </div>
                        @if ($errors->has('inputClabe'))
                        <div class="alert alert-danger">
                            {{ $errors->first('inputClabe') }}
                        </div>
                        @endif
                        </div>


                        
                     

                        
                    </div>
                    <div class="col-lg-4">
                      {{-- subir imagen --}}

                    {{-- muestra imagen --}}
                    <div class="team-photo">
                        <img @if(empty($Modulo->imagenPrincipal)) src="{{ asset('images/team/default.png') }}" @else src="{{$imgPrincipal}}" @endif alt="team">
                        <a href="#team-popup-10" class="team-show content-popup"></a>
                   

                    </div>

                    <div class="">
                        <input type="file" name="inputImgPrincipal" id="inputImgPrincipal" class="input-bordered mt-2" @if(empty($Modulo->imagenPrincipal)) required @endif>
                        @if ($errors->has('inputImgPrincipal'))
                        <div class="alert alert-danger">
                            Este campo solo debe ser imagen
                            {{-- {{ $errors->first('inputImgPrincipal') }} --}}
                        </div>
                        @endif
                    </div>
                    {{-- div contenedor  --}}
                    <div id="team-popup-10" class="team-popup mfp-hide">
                        <a title="Close" class="mfp-close">×</a>
                        <div class="row align-items-start">
                            <div class="col-md-6">
                                <div class="team-photo">
                                    <img @if(empty($Modulo->imagenPrincipal)) src="{{ asset('images/team/default.png') }}" @else src="{{$imgPrincipal}}" @endif alt="team">
                                </div>
                                <div class="token-info bg-white">
                                    <table class="table table-token" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                <td class="table-head">Descripción</td>
                                            </tr>
                                            <tr>
                                                <td class="table-des">{{ $Modulo->descripcion}}</td>
                                            </tr>
                                        
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- .col  -->
                            <div class="col-md-6">
                                <div class="team-popup-info pl-md-3">
                                    <div class="token-info bg-white">
                                        <h4 class="title title-md mb-2 text-sm-center">Módulo Información</h4>
                                        <table class="table table-token" cellspacing="0" cellpadding="0">
                                            <tbody>
                                                <tr>
                                                    <td class="table-head">Tipo</td>
                                                    <td class="table-des">{{ $Modulo->tipo}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="table-head">Proyecto</td>
                                                    <td class="table-des">{{ $Modulo->proyecto}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="table-head">MT2</td>
                                                    <td class="table-des">{{ $Modulo->mt2}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="table-head">Valor Operación</td>
                                                    <td class="table-des">${{ number_format($Modulo->valorOperacion, 2)}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="table-head">Nivel/Piso</td>
                                                    <td class="table-des">{{ $Modulo->nivelPiso}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="table-head"># Cajones Estacionamiento</td>
                                                    <td class="table-des">{{ $Modulo->numCajones}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="table-head">Estatus</td>
                                                    <td class="table-des">{{ $Modulo->estatus}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                </div>
                            </div><!-- .col  -->
                        </div><!-- .row  -->
                    </div><!-- .team-popup  -->

                    
                    </div>
                </div>

            </div> <!-- tab-pane -->
            <div class="tab-pane" id="tab-2">
                <div class="nk-block-text">
                    <h3 class="text-black">Imágenes</h3>
                </div>
                <div class="imgCont">
                    <div class="container-input">
                        <input type="file" class="inputfile inputfile-5"
                            data-multiple-caption="{count} archivos seleccionados" name="files[]"
                            id="file-5" accept="image/*" multiple>

                        <label for="file-5">
                            <figure>
                                <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile"
                                width="20" height="17" viewBox="0 0 20 17">
                                <path
                                    d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z">
                                </path>
                            </svg>
                            </figure>
                            <span class="iborrainputfile">Seleccionar archivo</span>
                        </label>
                    </div>

                    <div class="gallery">
                        @if (isset($articulosImg) && $articulosImg != null)
                        @foreach ($articulosImg as $articuloImg)
                            <?php
                            $src = "";
                            $FileArray = explode('/', $articuloImg['file']);
                            $longitudFile = count($FileArray);
                            $file = $FileArray[$longitudFile - 1];
                            $rutaFin = str_replace(['//', '///', '////'], '/', $articuloImg['path']);
                            // dd($rutaFin);
                            if (Storage::disk('public')->exists($rutaFin)) {
                                $contents = Storage::disk('public')->get($rutaFin);
                                $contents = base64_encode($contents);
                                $src = 'data: '.mime_content_type('../storage/app/public/'.$rutaFin).';base64,'.$contents;
                            }
                            
                            ?>
                            <div class='imgContenedorPreview' id="{{ $articuloImg['idImg'] }}">
                                <input type="button" class="imgBtn"
                                    onclick="eliminarImg('{{ $articuloImg['idImg'] }}')"
                                    value="X">
                                <a data-fancybox='demo' data-src='{{$src}}'>
                                    <img src="{{$src}}" class="imgPreview">
                                </a>
                            </div>
                        @endforeach
                    @endif
                    </div>
                </div>

                <div class="col-md-12">
                    <h2 class="text-black">Documentos</h2>
                </div>

                @if(isset($docsProyectos) && $docsProyectos != null)
                @foreach ($docsProyectos as $document)
                <?php
                $srcDoc = "";
                // nombre de los files
                $pathFileArray = explode('/', $document['path']);
                $patch = explode('-', $document['path'])[0];
                $longitudPath = count($pathFileArray);
                $nameFileArray = explode('-', $pathFileArray[$longitudPath - 1]);
                $nameFile = $nameFileArray[count($nameFileArray) - 1];
                
                //nameFiles de los documentos digitales
                $FileArray = explode('/', $document['file']);
                $longitudFile = count($FileArray);
                $file = $FileArray[$longitudFile - 1];
                ?>

                <div class="form-group">
                    <div class="col-md-12">
                        <div class="field_wrapper_edit">
                            <div>
                                <div class='col-md-12 mt10'>
                                    <label class='negrita'>Nombre del documento</label>
                                    <input class="form-control"
                                        id="{{ $document['idFile'] . '-' . 'nombre' }}"
                                        name="{{ $document['idFile'] }}-nombre" type='text'
                                        value="{{ $nameFile }}" disabled />
                                </div>

                                <div class='col-md-12 mt10'>
                                    {!! Form::label('nombreActualArchivo', 'Nombre del archivo actual', ['class' => 'negrita']) !!}
                                    {!! Form::text('nombreActualArchivo', $file, ['class' => 'form-control', 'disabled']) !!}
                                </div>

                                <div class='col-md-7 mt10'>
                                    <input type='file' name="{{ $document['idFile'] }}-file"
                                        id="{{ $document['idFile'] . '-' . 'file' }}" disabled />
                                </div>

                                <div class="row">
                                    <div class="col-md-2 mt10">
                                        {!! Form::label('editNameDoc', 'Editar Campos', ['class' => 'negrita']) !!}
                                        {!! Form::checkbox('docsEdit[]', $document['idFile'], null, [
                                            'id' => $document['idFile'] . '-all',
                                        ]) !!}
                                    </div>
                                    <div class="col-md-8 mt10">
                                      
                                        <a href="{{route('descargar.doc', [$document->idFile])}}" class="badge badge-light">Descargar</a>  
                    <button class="badge badge-danger"  onclick="eliminarDoc('{{ $document['idFile'] }}')"> Eliminar </button>
                                    
                                    </div>

                                   
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    $("#{{ $document['idFile'] }}-all").click(function() {
                        if ($("#{{ $document['idFile'] }}-all").is(':checked')) {
                            $("#{{ $document['idFile'] }}-nombre").prop('disabled', false);
                            $("#{{ $document['idFile'] }}-file").prop('disabled', false);
                        } else {
                            $("#{{ $document['idFile'] }}-nombre").prop('disabled', true);
                            $("#{{ $document['idFile'] }}-file").prop('disabled', true);
                        }

                    });
                </script>
            @endforeach

            <div class="form-group">
                <div class="col-md-12">
                    <div class="field_wrapper">

                    </div>
                </div>
            </div>
            @else
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="field_wrapper">
                            <div>
                                <div class="col-md-11">
                                    {!! Form::label('nombreDocumento', 'Nombre del documento', ['class' => 'field-label']) !!}

                                    {!! Form::text('nombreDocumento[]', null, ['class' => 'form-control', 'id' => 'nameDoc1']) !!}
                                </div>

                                <div class="col-md-12 mt10">
                                    <input type="file" name="field_name[]" id="fileDoc" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-lg-12 col-sm-12" style="text-align: center;">
                    <a href="javascript:void(0);" class="add_button btn btn-sm btn-primary" title="Add field"><i
                        class="fa fa-plus" aria-hidden="true"></i>&nbsp;Agregar otro archivo</a>
                </div>

            </div> <!-- tab-pane -->
           
        </div>
            
            </div>
        </div><!-- tab-content -->
</div><!-- .col -->
<br>