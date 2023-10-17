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
                    <div class="col-lg-9">
                    
                        <div class="form-group row">
                            <label for="inputClave" class="col-lg-2 col-sm-2 col-form-label field-label">Clave<span style="color: red">*</span></label>
                            <div class="col-lg-4 col-sm-10">
                              <input type="text" class="input-bordered" id="inputClave" placeholder="Clave" name="inputClave" required value="{{ old('inputClave') ?? $Proyecto->clave }}" >
                            </div>
                        @if ($errors->has('inputClave'))
                        <div class="alert alert-danger">
                            {{ $errors->first('inputClave') }}
                        </div>
                        @endif
                        </div>

                        <div class="form-group row">
                            <label for="inputNombre" class="col-lg-2 col-sm-2 col-form-label field-label">Nombre<span style="color: red">*</span></label>
                            <div class="col-lg-10 col-sm-10">
                                <input type="text" class="input-bordered" id="inputNombre" placeholder="Nombre" name="inputNombre" required value="{{ old('inputNombre') ?? $Proyecto->nombre }}">
                            </div>
                            @if ($errors->has('inputNombre'))
                            <div class="alert alert-danger">
                                {{ $errors->first('inputNombre') }}
                            </div>
                            @endif
                        </div>
                        
                        <div class="form-group row">
                            <label for="inputDescripcion" class="col-lg-2 col-sm-3 col-form-label field-label">Descripción</label>
                            <div class="col-lg-10 col-sm-12">
                              {{-- <input type="email" class=" id="inputDescripción" placeholder="Descripción"> --}}
                              <textarea name="inputDescripcion" id="" cols="30" rows="5" class="input-bordered" value="{{ old('inputDescripción') ?? $Proyecto->descripcion }}"> {{ old('inputDescripción') ?? $Proyecto->descripcion }}</textarea>
                            </div>
                            @if ($errors->has('inputDescripcion'))
                            <div class="alert alert-danger">
                                {{ $errors->first('inputDescripcion') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label for="inputPresupuesto" class="col-lg-2 col-sm-3 col-form-label field-label ">Presupuesto</label>
                            <div class="col-lg-6 col-sm-12">
                                <input type="text" class="input-bordered currency" id="inputPresupuesto" placeholder="$ 0.0" name="inputPresupuesto" value="{{ old('inputPresupuesto') ?? $Proyecto->presupuesto }}" >
                            </div>
                            @if ($errors->has('inputPresupuesto'))
                            <div class="alert alert-danger">
                                {{ $errors->first('inputPresupuesto') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label for="inputCajones" class="col-lg-5 col-sm-8 col-form-label field-label"># Cajones de Estacionamiento</label>
                            <div class="col-lg-3 col-sm-4">
                                <input type="number" class="input-bordered" id="inputCajones" placeholder="#" name="inputCajones" value="{{ old('inputCajones') ?? $Proyecto->numCajones }}">
                            </div>
                            @if ($errors->has('inputCajones'))
                            <div class="alert alert-danger">
                                {{ $errors->first('inputCajones') }}
                            </div>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label for="inputCajonesRestantes" class="col-lg-5 col-sm-8 col-form-label field-label"># Cajones de Estacionamiento Restantes</label>
                            <div class="col-lg-3 col-sm-4">
                                <input type="text" class="input-bordered" id="inputCajonesRestantes" placeholder="#" name="inputCajonesRestantes" value="{{ old('inputCajonesRestantes') ?? $Proyecto->numCajonesRestantes }}" readonly>
                            </div>
                            @if ($errors->has('inputCajonesRestantes'))
                            <div class="alert alert-danger">
                                {{ $errors->first('inputCajonesRestantes') }}
                            </div>
                            @endif
                        </div>
          

                        <div class="form-group row">
                            <label for="inputInicio" class="col-lg-2 col-sm-2 col-form-label field-label">Fecha Inicio</label>
                            <div class="col-lg-3 col-sm-10">
                              <input type="date" class="input-bordered" id="inputInicio" placeholder="" name="inputInicio" value="{{ old('inputInicio') ?? $Proyecto->fechaIniProyecto }}">
                            @if ($errors->has('inputInicio'))
                            <div class="alert alert-danger">
                                {{ $errors->first('inputInicio') }}
                            </div>
                            @endif
                            </div>

                            <label for="inputFin" class="col-lg-1 col-sm-2 col-form-label field-label">Fecha Fin</label>
                            <div class="col-lg-3 col-sm-10">
                              <input type="date" class="input-bordered" id="inputFin" placeholder="" name="inputFin" value="{{ old('inputFin') ?? $Proyecto->fechaFinProyecto }}">
                            @if ($errors->has('inputFin'))
                            <div class="alert alert-danger">
                                {{ $errors->first('inputFin') }}
                            </div>
                            @endif
                            </div>

                            <label for="inputAño" class="col-lg-1 col-sm-2 col-form-label field-label mTop">Año Inicio<span style="color: red">*</span></label>
                            <div class="col-lg-2 col-sm-4 mTop">
                              <input type="text" class="input-bordered" id="inputAño" placeholder="2023" name="inputAño" required value="{{ old('inputAño') ?? $Proyecto->añoFinProyecto }}">
                            @if ($errors->has('inputAño'))
                            <div class="alert alert-danger">
                                {{ $errors->first('inputAño') }}
                            </div>
                            @endif
                            </div>
                         
                        </div>

                        <div class="form-group row">
                            <label for="inputNiveles" class="col-lg-2 col-sm-2 col-form-label field-label">Niveles Totales</label>
                            <div class="col-lg-4 col-sm-4">
                              <input type="text" class="input-bordered" id="inputNiveles" placeholder="0.0" name="inputNiveles" value="{{ old('inputNiveles') ?? $Proyecto->nivelesTotales }}">
                            @if ($errors->has('inputNiveles'))
                            <div class="alert alert-danger">
                                {{ $errors->first('inputNiveles') }}
                            </div>
                            @endif
                            </div>

                            <label for="inputMT2Totales" class="col-lg-2 col-sm-2 col-form-label field-label">MT2 Totales</label>
                            <div class="col-lg-4 col-sm-4">
                              <input type="text" class="input-bordered" id="inputMT2Totales" placeholder="0.0" name="inputMT2Totales" value="{{ old('inputMT2Totales') ?? $Proyecto->mt2 }}">
                            @if ($errors->has('inputMT2Totales'))
                            <div class="alert alert-danger">
                                {{ $errors->first('inputMT2Totales') }}
                            </div>
                            @endif
                            </div>
                         
                        </div>

                        <div class="form-group row">
                            <label for="inputDireccion" class="col-lg-2 col-sm-2 col-form-label field-label">Dirección</label>
                            <div class="col-lg-10 col-sm-12">
                              <input type="text" class="input-bordered" id="inputDireccion" placeholder="" name="inputDireccion" value="{{ old('inputDireccion') ?? $Proyecto->direccion }}">
                            @if ($errors->has('inputDireccion'))
                            <div class="alert alert-danger">
                                {{ $errors->first('inputDireccion') }}
                            </div>
                            @endif
                            </div>
                        </div>

                        
                        <div class="form-group row">
                            <label for="inputColonia" class="col-lg-2 col-sm-2 col-form-label field-label">Colonia</label>
                            <div class="col-lg-4 col-sm-12">
                              <input type="text" class="input-bordered" id="inputColonia" placeholder="" name="inputColonia" value="{{ old('inputColonia') ?? $Proyecto->colonia }}">
                            @if ($errors->has('inputColonia'))
                            <div class="alert alert-danger">
                                {{ $errors->first('inputColonia') }}
                            </div>
                            @endif
                            </div>

                            <label for="inputCiudad" class="col-lg-2 col-sm-2 col-form-label field-label">Ciudad</label>
                            <div class="col-lg-4 col-sm-12">
                              <input type="text" class="input-bordered" id="inputCiudad" placeholder="" name="inputCiudad" value="{{ old('inputCiudad') ?? $Proyecto->ciudadMunicipio }}">
                            @if ($errors->has('inputCiudad'))
                            <div class="alert alert-danger">
                                {{ $errors->first('inputCiudad') }}
                            </div>
                            @endif
                            </div>
                         
                        </div>


                        <div class="form-group row">

                            <label for="inputEstado" class="col-lg-2 col-sm-2 col-form-label field-label">Estado</label>
                            <div class="col-lg-4 col-sm-12">
                              <input type="text" class="input-bordered" id="inputEstado" placeholder="" name="inputEstado" value="{{ old('inputEstado') ?? $Proyecto->estado }}">
                            @if ($errors->has('inputEstado'))
                            <div class="alert alert-danger">
                                {{ $errors->first('inputEstado') }}
                            </div>
                            @endif
                            </div>

                            <label for="inputPais" class="col-lg-2 col-sm-2 col-form-label field-label">País</label>
                            <div class="col-lg-4 col-sm-12">
                              <input type="text" class="input-bordered" id="inputPais" placeholder="" name="inputPais" value="{{ old('inputPais') ?? $Proyecto->pais }}">
                            @if ($errors->has('inputPais'))
                            <div class="alert alert-danger">
                                {{ $errors->first('inputPais') }}
                            </div>
                            @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="inputCP" class="col-lg-2 col-sm-2 col-form-label field-label">CP</label>
                            <div class="col-lg-4 col-sm-12">
                              <input type="text" class="input-bordered" id="inputCP" placeholder="" name="inputCP" value="{{ old('inputCP') ?? $Proyecto->cp }}">
                            @if ($errors->has('inputCP'))
                            <div class="alert alert-danger">
                                {{ $errors->first('inputCP') }}
                            </div>
                            @endif
                            </div>

                            <label for="selectEstatus" class="col-lg-2 col-sm-2 col-form-label field-label">Estatus<span style="color: red">*</span></label>
                            <div class="col-lg-4 col-sm-12">
                              {!! Form::select('selectEstatus', ['1' => 'Alta', '0' => 'Baja'], $Proyecto->estatus, [
                                'id' => 'selectEstatus',
                                'class' => '',
                            ]) !!}
                            @if ($errors->has('selectEstatus'))
                            <div class="alert alert-danger">
                                {{ $errors->first('selectEstatus') }}
                            </div>
                            @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="selectCategoria" class="col-lg-2 col-sm-2 col-form-label field-label mTop">Categoría</label>
                            <div class="col-lg-4 col-sm-12" >
                                {!! Form::select(
                                    'selectCategoria',
                                    $categoria,
                                    old('selectCategoria') ?? $Proyecto->categoria,
                                    ['id' => 'selectCategoria', 'placeholder' => 'Selecciona una Categoría'],
                                ) !!}
                            @if ($errors->has('selectCategoria'))
                            <div class="alert alert-danger">
                                {{ $errors->first('selectCategoria') }}
                            </div>
                            @endif
                            </div>

                            <label for="selectGrupo" class="col-lg-2 col-sm-2 col-form-label field-label mTop">Grupo</label>
                            <div class="col-lg-4 col-sm-12">
                                {!! Form::select(
                                    'selectGrupo',
                                    $grupo,
                                    old('selectGrupo') ?? $Proyecto->grupo,
                                    ['id' => 'selectGrupo', 'placeholder' => 'Selecciona un Grupo'],
                                ) !!}
                            @if ($errors->has('selectGrupo'))
                            <div class="alert alert-danger">
                                {{ $errors->first('selectGrupo') }}
                            </div>
                            @endif
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-lg-3">
                      {{-- subir imagen --}}

                    {{-- muestra imagen --}}
                    <div class="team-photo">
                        <img @if(empty($Proyecto->imagenPrincipal)) src="{{ asset('images/team/default.png') }}" @else src="{{$imgPrincipal}}" @endif alt="team">
                        <a href="#team-popup-10" class="team-show content-popup"></a>
                   

                    </div>

                    <div class="">
                        <input type="file" name="inputImgPrincipal" id="inputImgPrincipal" class="input-bordered mt-2" @if(empty($Proyecto->imagenPrincipal)) required @endif>
                        @if ($errors->has('inputImgPrincipal'))
                        <div class="alert alert-danger">
                            {{ $errors->first('inputImgPrincipal') }}
                        </div>
                        @endif
                    </div>
                    {{-- div contenedor  --}}
                    <div id="team-popup-10" class="team-popup mfp-hide">
                        <a title="Close" class="mfp-close">×</a>
                        <div class="row align-items-start">
                            <div class="col-md-6">
                                <div class="team-photo">
                                    <img @if(empty($Proyecto->imagenPrincipal)) src="{{ asset('images/team/default.png') }}" @else src="{{$imgPrincipal}}" @endif alt="team">
                                </div>
                                <div class="token-info bg-white mt-3">
                                    <table class="table table-token" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                <td class="table-head">Descripción</td>
                                            </tr>
                                            <tr>
                                                <td class="table-des">{{ $Proyecto->descripcion}}</td>
                                            </tr>
                                        
                                        </tbody>
                                    </table>
                                </div>
                            </div><!-- .col  -->
                            <div class="col-md-6">
                                <div class="team-popup-info pl-md-3">
                                    <div class="token-info bg-white">
                                        <h4 class="title title-md mb-2 text-sm-center">Proyecto Información</h4>
                                        <table class="table table-token" cellspacing="0" cellpadding="0">
                                            <tbody>
                                                <tr>
                                                    <td class="table-head">Tipo</td>
                                                    <td class="table-des">{{ $Proyecto->tipo}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="table-head">Proyecto</td>
                                                    <td class="table-des">{{ $Proyecto->proyecto}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="table-head">MT2</td>
                                                    <td class="table-des">{{ $Proyecto->mt2}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="table-head">Valor Operación</td>
                                                    <td class="table-des">${{ number_format($Proyecto->valorOperacion, 2)}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="table-head">Nivel/Piso</td>
                                                    <td class="table-des">{{ $Proyecto->nivelPiso}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="table-head"># Cajones Estacionamiento</td>
                                                    <td class="table-des">{{ $Proyecto->numCajones}}</td>
                                                </tr>
                                                <tr>
                                                    <td class="table-head">Estatus</td>
                                                    <td class="table-des">{{ $Proyecto->estatus == 1 ? 'Alta' : 'Baja'}}</td>
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
                    <h3 class="text-black">Links</h3>
                </div>


                    <div class="form-group row">
                        <label for="inputLink1" class="col-sm-2 col-form-label field-label mt-2">Link 1:</label>
                        <div class="col-sm-2">
                          <input type="text" name="inputTituloLink1" class="input-bordered mt-2" id="inputTituloLink1" placeholder="Titulo" value="{{ old('inputTituloLink1') ?? $Proyecto->titulo1 }}">
                        </div>

                        <div class="col-sm-8">
                            <input type="text" name="inputLink1" class="input-bordered mt-2" id="inputLink1" placeholder="Https://" value="{{ old('inputLink1') ?? $Proyecto->link1 }}">
                          </div>
                        @if ($errors->has('inputLink1'))
                        <div class="alert alert-danger">
                            {{ $errors->first('inputLink1') }}
                        </div>
                        @endif
                    </div>
                    <div class="form-group row">
                        <label for="inputLink2" class="col-sm-2 col-form-label field-label mt-2">Link 2:</label>
                        <div class="col-sm-2">
                          <input type="text" name="inputTituloLink2" class="input-bordered mt-2" id="inputTituloLink2" placeholder="Titulo" value="{{ old('inputTituloLink2') ?? $Proyecto->titulo3 }}">
                        </div>

                        <div class="col-sm-8">
                            <input type="text" name="inputLink2" class="input-bordered mt-2" id="inputLink2" placeholder="Https://" value="{{ old('inputLink2') ?? $Proyecto->link2 }}">
                          </div>
                        @if ($errors->has('inputLink2'))
                        <div class="alert alert-danger">
                            {{ $errors->first('inputLink2') }}
                        </div>
                        @endif
                    </div>
                    <div class="form-group row">
                        <label for="inputLink3" class="col-sm-2 col-form-label field-label mt-2">Link 3:</label>
                        <div class="col-sm-2">
                          <input type="text" name="inputTituloLink3" class="input-bordered mt-2" id="inputTituloLink3" placeholder="Titulo" value="{{ old('inputTituloLink3') ?? $Proyecto->titulo3 }}">
                        </div>

                        <div class="col-sm-8">
                            <input type="text" name="inputLink3" class="input-bordered mt-2" id="inputLink3" placeholder="Https://" value="{{ old('inputLink3') ?? $Proyecto->link3 }}">
                          </div>
                        @if ($errors->has('inputLink3'))
                        <div class="alert alert-danger">
                            {{ $errors->first('inputLink3') }}
                        </div>
                        @endif
                    </div>
     



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
                <div class="form-group" style="">
                    <div class="col-md-12" style="">
                        <div class="field_wrapper" style="">
                            <div>
                                <div class="col-md-12">
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