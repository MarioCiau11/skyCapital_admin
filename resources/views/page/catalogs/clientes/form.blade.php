
<div class="row justify-content-center">
    <div class="col-lg-12">
        <p class="tc-danger mr-auto mb-0"> * = Campos Obligatorios</p>
    </div>
    <div class="col-lg-12">
        <ul class="nav tab-nav tab-nav-line tab-nav-center">
            <li><a class="active" data-toggle="tab" href="#tab-4-1">Datos Generales</a></li>
            <li><a data-toggle="tab" href="#tab-4-2">Datos Fiscales</a></li>
            <li><a data-toggle="tab" href="#tab-4-3">Datos Digitales</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-4-1">
                <h3 class="title title-md">Datos Generales</h3>

                <div class="row">
                    <div class="col-lg-3">
                        <div class="field-item">
                            <label class="field-label">Clave</label>
                            <div class="field-wrap">
                                <input name="inputClave" @if($Clientes->clave != null) disabled @endif value="{{old('inputClave',$Clientes->clave) ?? $Clave }}" type="text" class="input-bordered" readonly>
                                @if ($Clientes->clave != null)
                                    <input type="hidden" name="inputClave" value="{{$Clientes->clave}}">
                                @endif
                                @if ($errors->has('inputClave'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputClave')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <label for="" class="field-label">Tipo de Persona <span style="color: red">*</span></label>
                        <ul class="d-flex flex-wrap gutter-30px" id="personascontainer">
                            <li class="mt-1">
                                <div class="field-wrap">
                                    <input value="personaFisica" class="input-checkbox" id="checkPersonaF" name="checkPersona" type="checkbox" required
                                    @if(old('checkPersona') == 'personaFisica' ) checked @endif 
                                    >
                                    <label for="checkPersonaF">Persona Física</label>
                                </div>
                            </li>
                            <li class="mt-1">
                                <div class="field-wrap">
                                    <input value="personaMoral" class="input-checkbox" id="checkPersonaM" name="checkPersona" type="checkbox" required
                                    @if(old('checkPersona') == 'personaMoral' ) checked @endif
                                    >
                                    <label for="checkPersonaM">Persona Moral</label>
                                </div>
                            </li>
                        </ul>
                        <div class="field-wrap">
                            @if ($errors->has('checkPersona'))
                                <div class="alert alert-danger">
                                    {{$errors->first('checkPersona')}}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-5">
                        <div class="field-item">
                            <label class="field-label">Nombre/Razón Social <span style="color: red">*</span></label>
                            <div class="field-wrap">
                                <input type="text" value="{{old('inputRazon',$Clientes->razonSocial)}}" class="input-bordered" id="inputRazon" name="inputRazon" required>
                                @if ($errors->has('inputRazon'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputRazon')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="field-item">
                            <label class="field-label">RFC <span style="color: red">*</span></label>
                            <div class="field-wrap">
                                <input value="{{old('inputRFC',$Clientes->RFC)}}" name="inputRFC" required type="text" class="input-bordered">
                                @if ($errors->has('inputRFC'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputRFC')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="field-item">
                            <label class="field-label">CURP <span class="requiredpF" style="color: red">*</span></label>
                            <div class="field-wrap">
                                <input value="{{old('inputCURP',$Clientes->CURP)}}" name="inputCURP" id="inputCURP" type="text" class="input-bordered" >
                                @if ($errors->has('inputCURP'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputCURP')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    
                </div>

                <h3 class="title title-md">Información de representante Legal</h3>

                <div class="row">
                    <div class="col-lg-4">
                        <div class="field-item">
                            <label for="inputNombre" class="field-label">Nombre(s) <span class="requiredpM" style="color: red">*</span></label>
                            <div class="field-wrap">
                                <input name="inputNombre" value="{{old('inputNombre',$Clientes->nombres)}}" type="text" id="inputNombre" class="input-bordered">
                                @if ($errors->has('inputNombre'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputNombre')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="field-item">
                            <label for="inputApellidoP" class="field-label">Apellido Paterno <span class="requiredpM" style="color: red">*</span></label>
                            <div class="field-wrap">
                                <input name="inputApellidoP" value="{{old('inputApellidoP',$Clientes->apellidoPaterno)}}" type="text" id="inputApellidoP" class="input-bordered">
                                @if ($errors->has('inputApellidoP'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputApellidoP')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="field-item">
                            <label for="inputApellidoM" class="field-label">Apellido Materno <span class="requiredpM" style="color: red">*</span></label>
                            <div class="field-wrap">
                                <input name="inputApellidoM" value="{{old('inputApellidoM',$Clientes->apellidoMaterno)}}" type="text" id="inputApellidoM" class="input-bordered">
                                @if ($errors->has('inputApellidoM'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputApellidoM')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-lg-3">
                        <div class="field-item">
                            <label for="inputTelefonoC" class="field-label">Teléfono Celular</label>
                            <div class="field-wrap">
                                <input name="inputTelefonoC" value="{{old('inputTelefonoC',$Clientes->telefonoCelular)}}" type="text" class="input-bordered">
                                @if ($errors->has('inputTelefonoC'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputTelefonoC')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="field-item">
                            <label for="inputCorreo" class="field-label">Correo Electrónico</label>
                            <div class="field-wrap">
                                <input name="inputCorreo" value="{{old('inputCorreo',$Clientes->correoElectronico)}}" type="text" class="input-bordered">
                                @if ($errors->has('inputCorreo'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputCorreo')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="title title-md">Información del Cliente</h3>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="field-item">
                            <label for="inputDireccion" class="field-label">Dirección <span style="color: red">*</span></label>
                            <div class="field-wrap">
                                <input name="inputDireccion" value="{{old('inputDireccion',$Clientes->direccion)}}" type="text" class="input-bordered" required>
                                @if ($errors->has('inputDireccion'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputDireccion')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="field-item">
                            <label for="inputVialidades" class="field-label">Entre Vialidades <span style="color: red">*</span></label>
                            <div class="field-wrap">
                                <input value="{{old('inputVialidades',$Clientes->vialidades)}}" name="inputVialidades" value="{{old('inputVialidades',$Clientes->vialidades)}}" type="text" class="input-bordered" required>
                                @if ($errors->has('inputVialidades'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputVialidades')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="field-item">
                            <label for="inputNoInterior" class="field-label">Número Interior <span style="color: red">*</span></label>
                            <div class="field-wrap">
                                <input name="inputNoInterior" value="{{old('inputNoInterior',$Clientes->noInterior)}}" type="text" class="input-bordered" required>
                                @if ($errors->has('inputNoInterior'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputNoInterior')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg 4">
                        <div class="field-item">
                            <label for="inputNoExterior" class="field-label">Número Exterior <span style="color: red">*</span></label>
                            <div class="field-wrap">
                                <input name="inputNoExterior" value="{{old('inputNoExterior',$Clientes->noExterior)}}" type="text" class="input-bordered" required>
                                @if ($errors->has('inputNoExterior'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputNoExterior')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="field-item">
                            <label for="inputColonia" class="field-label">Colonia/Fraccionamiento <span style="color: red">*</span></label>
                            <div class="field-wrap">
                                <input name="inputColonia" value="{{old('inputColonia',$Clientes->coloniaFracc)}}" type="text" class="input-bordered" required>
                                @if ($errors->has('inputColonia'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputColonia')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="field-item">
                            <label for="inputLocalidad" class="field-label">Localidad/Municipio <span style="color: red">*</span></label>
                            <div class="field-wrap">
                                <input name="inputLocalidad" value="{{old('inputLocalidad',$Clientes->localidadMunicipio)}}" type="text" class="input-bordered" required>
                                @if ($errors->has('inputLocalidad'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputLocalidad')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg 4">
                        <div class="field-item">
                            <label for="inputEstado" class="field-label">Estado <span style="color: red">*</span></label>
                            <div class="field-wrap">
                                <input name="inputEstado" value="{{old('inputEstado',$Clientes->estado)}}" type="text" class="input-bordered" required>
                                @if ($errors->has('inputEstado'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputEstado')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="field-item">
                            <label for="inputPais" class="field-label">País <span style="color: red">*</span></label>
                            <div class="field-wrap">
                                <input name="inputPais" value="{{old('inputPais',$Clientes->pais)}}" type="text" class="input-bordered" required>
                                @if ($errors->has('inputPais'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputPais')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2">
                        <div class="field-item">
                            <label for="inputCodigoP" class="field-label">Código Postal <span style="color: red">*</span></label>
                            <div class="field-wrap">
                                <input name="inputCodigoP" value="{{old('inputCodigoP',$Clientes->codigoPostal)}}" type="text" class="input-bordered" required>
                                @if ($errors->has('inputCodigoP'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputCodigoP')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-1"></div>
                    <div class="col-lg-3">
                        <div class="field-item">
                            <label for="inputTelOf1" class="field-label">Teléfono Oficina 1</label>
                            <div class="field-wrap">
                                <input name="inputTelOf1" value="{{old('inputTelOf1',$Clientes->telefono1)}}" type="text" class="input-bordered">
                                @if ($errors->has('inputTelOf1'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputTelOf1')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="field-item">
                            <label for="inputTelOf2" class="field-label">Teléfono Oficina 2</label>
                            <div class="field-wrap">
                                <input name="inputTelOf2" value="{{old('inputTelOf2',$Clientes->telefono2)}}" type="text" class="input-bordered">
                                @if ($errors->has('inputTelOf2'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputTelOf2')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="field-item">
                            <label for="inputRepTelCel" class="field-label">Teléfono Celular</label>
                            <div class="field-wrap">
                                <input name="inputRepTelCel" value="{{old('inputRepTelCel',$Clientes->telCelular)}}" type="text" class="input-bordered">
                                @if ($errors->has('inputRepTelCel'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputRepTelCel')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="field-item">
                            <label for="inputContacto1" class="field-label">Contacto 1</label>
                            <div class="field-wrap">
                                <input name="inputContacto1" value="{{old('inputContacto1',$Clientes->contacto1)}}" type="text" class="input-bordered">
                                @if ($errors->has('inputContacto1'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputContacto1')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="field-item">
                            <label for="inputCorreoElct1" class="field-label">Correo Electrónico 1 (Principal / Acceso Portal) <span style="color: red">*</span></label>
                            <div class="field-wrap">
                                <input name="inputCorreoElct1" value="{{old('inputCorreoElct1',$Clientes->correoElectronico1)}}" type="text" class="input-bordered" required>
                                @if ($errors->has('inputCorreoElct1'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputCorreoElct1')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="field-item">
                            <label for="inputContacto2" class="field-label">Contacto 2</label>
                            <div class="field-wrap">
                                <input name="inputContacto2" value="{{old('inputContacto2',$Clientes->contacto2)}}" type="text" class="input-bordered">
                                @if ($errors->has('inputContacto2'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputContacto2')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="field-item">
                            <label for="inputCorreoElct2" class="field-label">Correo Electrónico 2 </label>
                            <div class="field-wrap">
                                <input name="inputCorreoElct2" value="{{old('inputCorreoElct2',$Clientes->correoElectronico2)}}" type="text" class="input-bordered">
                                @if ($errors->has('inputCorreoElct2'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputCorreoElct2')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6"></div>
                    <div class="col-lg-6">
                        <div class="field-item">
                            <label for="inputcontraseñaPortal" class="field-label">Constraseña Portal</label>
                            <div class="field-wrap field-wrap-documents">
                                <input name="inputcontraseñaPortal" value="{{old('inputcontraseñaPortal',$contraseñaPortal)}}" type="password" class="input-bordered" id="contraseña" readonly>
                                <a id="ver" style="text-decoration: none;  color: inherit;" href="javascript:void(0)">
                                    <i id="ojoP" class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                                @if ($errors->has('inputcontraseñaPortal'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputcontraseñaPortal')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="field-item">
                            <label for="textareaObservaciones" class="field-label">Observaciones/Comentarios</label>
                            <div class="field-wrap">
                                <textarea  value="{{old('textareaObservaciones',$Clientes->observaciones)}}" class="input-bordered input-textarea" name="textareaObservaciones" id="textArea" cols="30" rows="10"></textarea>
                                @if ($errors->has('textareaObservaciones'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('textareaObservaciones')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="field-item">
                            <label for="inputOcupacion" class="field-label">Ocupación</label>
                            <div class="field-wrap">
                                <input value="{{old('inputOcupacion',$Clientes->ocupacion)}}" class="input-bordered" name="inputOcupacion" id="">
                                @if ($errors->has('inputOcupacion'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputOcupacion')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5">
                        <div class="field-item">
                            <label for="selectGrupo" class="field-label">Grupo</label>
                            <div class="field-wrap">
                                {!! Form::select('selectGrupo', $grupo,
                                 old('selectGrupo',$Clientes->grupo)
                                 , ['id' => 'selectGrupo','placeholder' => 'Seleccione un Grupo']) !!}
                                @if ($errors->has('selectGrupo'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('selectGrupo')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="field-item">
                            <label for="selectCategoria" class="field-label">Categoría</label>
                            <div class="field-wrap">
                                {!! Form::select('selectCategoria',$categoria,
                                 old('selectCategoria',$Clientes->categoria), 
                                 ['id' => 'selectCategoria','placeholder' => 'Seleccione una Categoría']) !!}
                                @if ($errors->has('selectCategoria'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('selectCategoria')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="field-item">
                            {!! Form::label('selectEstatus', 'Estatus', ['class' => 'field-label']) !!}
                            <div class="field-wrap">
                                {!! Form::select('selectEstatus', [1 => 'Alta', 0 => 'Baja'], old('selectEstatus', $Clientes->estatus ?? 1), [
                                    'id' => 'selectEstatus',
                                ]) !!}
                                @if ($errors->has('selectEstatus'))
                                    <div class="aler alert-danger">
                                        {{ $errors->first('selectEstatus') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="title title-md">Condiciones Comerciales</h3>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="field-item">
                            <label for="selectCondicion" class="field-label">Condición de Pago</label>
                            <div class="field-wrap">
                                {!! Form::select('selectCondicion',$condiciones, 
                                old('selectCondicion',$Clientes->condicionPago),
                                ['id' => 'selectCondicion','placeholder' => 'Seleccione una Condición']) !!}
                                @if ($errors->has('selectCondicion'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('selectCondicion')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-4-2">
                <h3 class="title title-md">Datos Fiscales</h3>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="field-item">
                            <label for="" class="field-label">Régimen Fiscal</label>
                            <div class="field-wrap">
                                <input value="{{old('inputRegimen',$Clientes->regimenFisc)}}" class="input-bordered" name="inputRegimen" type="text">
                                @if ($errors->has('inputRegimen'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputRegimen')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-4-3">
                <h3 class="title title-md">Datos Digitales</h3>
                
                @if (isset($documentos) && !empty($documentos))
                  
                    @foreach ($documentos as $document)
                        @php
                            $srcDoc = "";
                            //nombre de los files 
                            $pathFileArray = explode('/', $document['path']);
                            $patch = explode('-', $document['path'])[0];
                            $longitudPath = count($pathFileArray);
                            $nameFileArray = explode('-', $pathFileArray[$longitudPath - 1]);
                            $nameFile = $nameFileArray[count($nameFileArray) - 1];

                            //nameFiles de los documentos Digitles
                            $FileArray = explode('/', $document['file']);
                            $longitudFile = count($FileArray);
                            $file = $FileArray[$longitudFile - 1];
                        @endphp

                        <div class="row">
                            <div class="col-lg-12" >
                                <div class="">
                                    <div class="field-item">
                                        <label for="" class="field-label">Nombre del Documento</label>
                                        <div class="field-wrap" id="contenedorFile">
                                            <input 
                                            id="{{$document['idFile'].'-'.'nombre'}}"
                                            value="{{$nameFile}}" 
                                            class="input-bordered" 
                                            name="{{$document['idFile']}}-nombre" 
                                            type="text"
                                            disabled
                                            >
                                            <div>
                                                {!! Form::label('nombreActualArchivo', 'Nombre del archivo actual', ['class' => 'field-label']) !!}
                                                {!! Form::text('nombreActualArchivo', $file, ['class' => 'form-control', 'disabled']) !!}
                                            </div>
                                            <div class="mt-3">
                                                <input 
                                                id="{{ $document['idFile'] . '-' . 'file' }}"
                                                type="file" 
                                                name="{{$document['idFile']}}-file"
                                                disabled
                                                >
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
                    <div class="row">
                        <div class="col-lg-12" >
                            <div class="field_wrapper" id="contenedorFile">
                                <div class="field-item">
                                    <label for="" class="field-label">Nombre del Documento</label>
                                    <div class="field-wrap">
                                        <input value="" class="input-bordered" name="inputDocumento[]" type="text">
                                        <input name="archivos[]"  type="file" name="archivo">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-lg-12">
                        <div class="field-item">
                            <a href="javascript:void(0)" class="add_button btn btn-sm btn-primary" title="Add field">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                Agregar otro archivo 
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>