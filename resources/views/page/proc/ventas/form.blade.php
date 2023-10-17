{{-- @php
    dd($flujo);
@endphp --}}
<div class="col-lg-12 col-sm-12 col-md-12">
    <div class="col-lg-12">
        <p class="tc-danger mr-auto mb-0"> * = Campos Obligatorios</p>
    </div>
    <!-- Nav tabs -->
    <ul class="nav tab-nav tab-nav-line tab-nav-center">
        <li>
            <a href="#tab-1" data-toggle="tab" class="active">Información de contrato</a>
        </li>
        <li>
            <a href="#tab-2" data-toggle="tab">Financiamiento</a>
        </li>
        <li>
            <a href="#tab-3" data-toggle="tab">Comisiones</a>
        </li>
    </ul>
        <!-- Tab panes -->
        <div class="tab-content nopadding noborder">
            <div class="row">
                <div class="col-lg-12" style="">
                    <div class="item float-right mb-3">
                        <a class="btn btn-success btn-sm btn-alt btn-outline toggle-tigger" href="#">
                            <span>Menú de opciones</span>
                            <em class="icon fas fa-caret-down fa-1x"></em>
                        </a>
                        <ul class="drop-list drop-list-full drop-list-center toggle-class toggle-drop">
                            @php
                                $permisson = auth()->user()->getAllPermissions();
                                $user = auth()->user();
                                // dd(Str::substr($venta->movimiento, 0, 11));
                            @endphp

                            @if ($venta->movimiento == null)
                                @if($venta->estatus != 'CONCLUIDO' && $venta->estatus != 'CANCELADO' && $user->can('Afectar'))
                                    <li><a href="#" class="" onclick="afectar()">Afectar <i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                                @endif
                            @else
                                @if($venta->movimiento == 'Contrato' || $venta->movimiento == 'Factura')
                                    @if($venta->estatus != 'CONCLUIDO' && $venta->estatus != 'CANCELADO' && $user->can('Afectar') && $user->can($venta->movimiento.' E'))
                                        <li><a href="#" class="" onclick="afectar()">Afectar <i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                                    @endif
                                @else
                                    @if (strpos($venta->movimiento,'Mensualidad') !== false)
                                    @php
                                        $mov = Str::substr($venta->movimiento, 0, 11);
                                    @endphp
                                        @if($venta->estatus != 'CONCLUIDO' && $venta->estatus != 'CANCELADO' && $user->can('Afectar') && $user->can($mov.'es E'))
                                            <li><a href="#" class="" data-toggle="modal" data-target="#modalFactura">Afectar <i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                                        @endif
                                    @else
                                        @if($venta->estatus != 'CONCLUIDO' && $venta->estatus != 'CANCELADO' && $user->can('Afectar') && $user->can($venta->movimiento.' E'))
                                            <li><a href="#" class="" data-toggle="modal" data-target="#modalFactura">Afectar <i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                                        @endif
                                    @endif
                                    
                                @endif
                            @endif

                            @if($venta->movimiento == 'Contrato' || $venta->movimiento == 'Factura')
                                @if($venta->estatus != 'SIN AFECTAR' && $venta->estatus != 'CANCELADO' && $user->can('Cancelar'))
                                    <li><a href="#" onclick="cancelar()">Cancelar <i class="fas fa-window-close"></i></a></li>
                                @endif
                            @endif
                            @if($venta->idVenta)
                                @if($venta->estatus == 'SIN AFECTAR')
                                    <li><a href="#" onclick="eliminar()">Eliminar <i class="fas fa-archive"></i></a></li>
                                @endif
                                @if($venta->movimiento == 'Contrato')
                                    <li><a href="#" onclick="copiar()">Copiar <i class="fas fa-copy"></i></a></li>
                                @elseif($venta->movimiento == 'Factura')
                                    @if($venta->estatus != 'CANCELADO')
                                        <li><a href="#" onclick="copiar()">Copiar <i class="fas fa-copy"></i></a></li>
                                    @endif
                                @endif
                            @endif
                            <li><a href="{{ route('proc.ventas.create')}}">Nuevo <i class="fas fa-file"></i></a></li>
                            @if($venta->idVenta)
                                @if($venta->estatus != 'SIN AFECTAR')
                                    <li><a href="{{route('proc.venta.viewAnexo', ['venta' =>Crypt::encrypt($venta->idVenta)])}}">Anexos <i class="fas fa-clipboard-list"></a></i></li>
                                    <li><a href="#" data-toggle="modal" data-target="#modalFlujo">Ver flujo <i class="fas fa-project-diagram"></i></a></li>
                                @endif
                            @endif
                            @if($venta->idVenta)
                                @if($venta->movimiento == 'Factura' && $venta->getCondition->tipoCondicion == 'Contado')
                                    <li><a href="#" data-toggle="modal" data-target="#cobranzaModal" id="btnCobro">Cobro <i class="fas fa-copy"></i></a></li>
                                @endif
                                @if($venta->movimiento != 'Contrato' && $venta->movimiento != 'Factura')
                                    @if($venta->estatus == 'CANCELADO')
                                        <li><a href="#" data-toggle="modal" data-target="#modalModulo">Inf. módulo <i class="fas fa-info"></i></a></li>
                                    @endif
                                @endif
                                @if($venta->movimiento == 'Contrato')
                                    @if($venta->estatus != 'CANCELADO')
                                        <li><a href="#" data-toggle="modal" data-target="#modalModulo">Inf. módulo <i class="fas fa-info"></i></a></li>
                                    @endif
                                @endif
                                @if($venta->movimiento == 'Factura')
                                    <li><a href="#" data-toggle="modal" data-target="#modalModulo">Inf. módulo <i class="fas fa-info"></i></a></li>
                                @endif
                                <li><a href="#" data-toggle="modal" data-target="#modalCliente">Inf. cliente <i class="fas fa-info"></i></a></li>
                                @if($venta->estatus != 'SIN AFECTAR') 
                                    @if($venta->estatus != 'SIN AFECTAR')
                                        @if($venta->movimiento == 'Factura')
                                            <li><a href="{{route('proc.contrato', ['venta' =>Crypt::encrypt($venta->idVenta)])}}">Reporte factura <i class="fas fa-file-alt"></i></a></li>
                                        @elseif($venta->movimiento == 'Contrato')
                                            <li><a href="{{route('proc.contrato', ['venta' =>Crypt::encrypt($venta->idVenta)])}}">Reporte contrato <i class="fas fa-file-alt"></i></a></li>
                                            <li><a href="{{route('proc.financ', ['venta' =>Crypt::encrypt($venta->idVenta)])}}">Reporte financiamiento <i class="fas fa-file-alt"></i></a></li>
                                            <li><a href="{{route('proc.comisiones', ['venta' =>Crypt::encrypt($venta->idVenta)])}}">Reporte comisiones <i class="fas fa-file-alt"></i></a></li>
                                        @endif
                                    @endif
                                @endif
                            @endif
                        </ul>
                    </div>   
                </div>
            </div>
            <div class="tab-pane active" id="tab-1">
                {{-- Row1 --}}
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-8 row-1">
                                <div class="form-group row">
                                    {{-- <input type="hidden" name="inputIdVenta" value="{{isset($venta->idVenta) ? $venta->idVenta : null}}" id="inputIdVenta"> --}}
                                    <label class="col-lg-3 col-sm-12 col-form-label field-label">Movimiento<span style="color: red">*</span></label>
                                    <div class="col-lg-3 col-sm-12">
                                        <div class="field-wrap">
                                            @if($venta->movimiento == 'Contrato' || $venta->movimiento == null || $venta->movimiento == 'Factura')
                                            {!! Form::select(
                                                'selectMovimiento',
                                                $movimientos,
                                                $venta->movimiento,
                                                ['id' => 'selectMovimiento', 'class' => 'marginB-10', 'required' => 'required','placeholder' => 'Seleccione uno...'],
                                            ) !!}
                                            @else
                                            {!! Form::select(
                                                'selectMovimiento',
                                                [$venta->movimiento => $venta->movimiento],
                                                $venta->movimiento,
                                                ['id' => 'selectMovimiento', 'class' => 'marginB-10'],
                                            ) !!}
                                            @endif
                                            @if ($errors->has('selectMovimiento'))
                                                <div class="alert alert-danger">
                                                    {{$errors->first('selectMovimiento')}}
                                                </div>
                                            @endif
                                        </div>
                                    </div> 
                                    <div class="col-lg-2 col-sm-12">
                                        <input readonly type="hidden" name="inputID" class="input-bordered input-font" id="inputIdVenta" value={{$venta->idVenta}}>
                                        <input readonly type="text" name="inputFolio" class="input-bordered input-font" id="inputFolio" placeholder="Folio Mov" value={{$venta->folioMov}}>
                                    </div>
                                    <div class="col-lg-2 col-sm-12">
                                        <input readonly type="text" name="inputClave" class="input-bordered input-font" placeholder="Clave Pro" value="{{$venta->claveProyecto}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12 input-correction">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-sm-12 col-form-label field-label">Moneda<span style="color: red">*</span></label>
                                    <div class="col-lg-5 col-sm-12">
                                        <div class="field-wrap">
                                            {!! Form::select(
                                                'selectMoneda',
                                                $monedas,
                                                isset($venta) ? $venta['moneda'] : ($parametro ? $parametro->monedaDefault : ''),
                                                ['id' => 'selectMoneda', 'class' => 'marginB-10', 'required' => 'required'],
                                            ) !!}
                                            @if ($errors->has('selectMoneda'))
                                                <div class="alert alert-danger">
                                                    {{$errors->first('selectMoneda')}}
                                                </div>
                                            @endif
                                        </div>
                                    </div> 
                                    <div class="col-lg-4 col-sm-12">
                                        <input type="text" name="inputTipoCambio" class="input-bordered input-font currency" id="inputCambio"  readonly value="{{ isset($venta) ? $venta['tipoCambio'] : ($parametro ? $parametro->tipoCambio : '1.0') }}
                                        ">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Row2 --}}
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-8 row-1">
                                <div class="form-group row">
                                    <label  class="col-lg-3 col-sm-12 col-form-label field-label">Fecha de emisión</label>
                                    <div class="col-lg-3 col-sm-12">
                                        <input readonly type="date" name="inputFechaEmision" class="input-bordered input-font"  placeholder="$1.00" value="{{ empty($venta->fechaEmision) ? date('Y-m-d') : \Carbon\Carbon::parse($venta->fechaEmision)->format('Y-m-d') }}">
                                    </div>
                                    <div>
                                        <input type="hidden" name="inputUser" value="{{Auth::user()->user_id}}">
                                    </div> 
                                    <label  class="col-lg-2 col-sm-12 col-form-label field-label">Proyecto<span style="color: red">*</span></label>
                                    <div class="col-lg-3 col-sm-12">
                                        <div class="field-wrap">
                                            {!! Form::select(
                                                'selectProyecto',
                                                $proyectos,
                                                $venta->proyecto,
                                                ['id' => 'selectProyecto', 'class' => '', 'placeholder' => 'Selecciona un Proyecto', 'required' => 'required'],
                                            ) !!}
                                            @if ($errors->has('selectProyecto'))
                                                <div class="alert alert-danger">
                                                    {{$errors->first('selectProyecto')}}
                                                </div>
                                            @endif
                                        </div>
                                    </div> 
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12 input-correction">
                                <div class="form-group row">
                                    <label  class="col-lg-3 col-sm-12 col-form-label field-label">Módulo<span style="color: red">*</span></label>
                                    <div class="col-lg-9 col-sm-12">
                                        <div class="field-wrap">
                              
                                            {!! Form::select(
                                                'selectModulo',
                                               [],
                                                old('selectModulo') ?? $venta->modulo,
                                                ['id' => 'selectModulo', 'class' => '', 'placeholder' => 'Clave - Módulo', 'required' => 'required'],
                                            ) !!}

                                            @if ($errors->has('selectModulo'))
                                                <div class="alert alert-danger">
                                                    {{$errors->first('selectModulo')}}
                                                </div>
                                            @endif
                                            <input type="hidden" name="moduloseleccionado" id="moduloSeleccionado" value="{{old('selectModulo') ?? $venta->modulo}}">
                                        </div>
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Row3 --}}
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-8 row-1">
                                <div class="form-group row">
                                    <label  class="col-lg-3 col-sm-12 col-form-label field-label">Observaciones</label>
                                    <div class="col-lg-8 col-sm-12">
                                        <textarea draggable="false" name="inputObservaciones" id="inputObservaciones" cols="30" rows="3" class="form-control input-bordered" style="text-align: left" >{{old('inputObservaciones') ?? $venta->observaciones}}</textarea>
                                        @if ($errors->has('inputObservaciones'))
                                            <div class="alert alert-danger">
                                                {{$errors->first('inputObservaciones')}}
                                            </div>
                                        @endif
                                    </div> 
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12 input-correction">
                                <div class="form-group row">
                                    <div class="col-lg-5 col-sm-12">
                                        <input type="text" name="inputValor" class="input-bordered input-font currency" placeholder="Valor operación" readonly id="inputValor" value="{{old('inputValor') ?? $venta->valorOperacion}}">
                                    </div>
                                    <div class="col-lg-4 col-sm-12">
                                        <input type="text" name="inputNivel" class="input-bordered input-font" placeholder="Nivel/piso" readonly id="inputNivel" value="{{old('inputNivel') ?? $venta->nivelPiso}}">
                                    </div>
                                    <div class="col-lg-3 col-sm-12">
                                        <input type="text" name="inputMT2" class="input-bordered input-font" placeholder="MT2" readonly id="inputMT2" value="{{old('inputMT2') ?? $venta->MT2}}">
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <input type="text" name="inputCajones" class="input-bordered input-font" placeholder="Número de cajones" readonly id="inputCajones" value="{{old('inputCajones') ?? $venta->numCajones}}">
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <input type="text" name="inputTipo" class="input-bordered input-font" placeholder="Tipo de módulo" readonly id="inputTipo" value="{{old('inputTipo') ?? $venta->tipoModulo}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Row4 --}}
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-12 row-1">
                                <div class="form-group row">
                                    <label  class="col-lg-2 col-form-label field-label">Propietario principal<span style="color: red">*</span></label>
                                    <div class="col-lg-6">
                                        <div class="col-sm-12 input-font input-correction">
                                            {!! Form::select(
                                                'selectProp',
                                                $clientes,
                                                $venta->propietarioPrincipal,
                                                ['id' => 'selectProp', 'class' => '', 'placeholder' => 'Seleccione un propietario', 'required' => 'required'],
                                            ) !!}
                                            @if ($errors->has('selectProp'))
                                                <div class="alert alert-danger">
                                                    {{$errors->first('selectProp')}}
                                                </div>
                                            @endif
                                            <input type="text" id="inputClientes" name="inputClientes" value="{{ $clientes }}" readonly hidden />
                                        </div> 
                                    </div>
                                    <div class="col-lg-3 col-sm-12">
                                        <input type="text" name="inputAsignadoPropietario" class="input-bordered input-font currency" placeholder="Importe asignado ($)"   value="{{old('inputAsignadoPropietario') ?? $venta->importeAsignado}}" id="inputAsignadoPropietario">
                                            @if ($errors->has('inputAsignadoPropietario'))
                                                <div class="alert alert-danger">
                                                    {{$errors->first('inputAsignadoPropietario')}}
                                                </div>
                                            @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 row-1">
                                {{-- Copropietario 1 --}}
                                <div class="form-group row" id="nCoprop">
                                    <label  class="col-lg-2 col-form-label field-label">  
                                        <span class="table-add float-right mb-3 mr-2">
                                            {{-- <a id="addInput" href="#!"><i class="fas fa-plus fa-2x" aria-hidden="true"></i></a> --}}
                                            @if ($venta->estatus == 'SIN AFECTAR' || $venta->estatus == 'POR CONFIRMAR' || $venta->estatus == null)
                                                <input type="button" name="" id="addInput" value="+">
                                            @endif
                                        </span>Co-Propietario
                                    </label>
                                    <div class="col-lg-6">
                                        <div class="col-sm-12 input-font input-correction">
                                            {!! Form::select(
                                                'selectCoprop',
                                                $clientes,
                                                old('selectCoprop') ?? $venta->coPropietario,
                                                ['id' => 'selectCoprop', 'class' => '', 'placeholder' => 'Seleccione un co-propietario'],
                                            ) !!}
                                        </div> 
                                    </div>
                                    <input type="hidden" name="inputCoprops" id="inputCoprops">
                   

                                    <div class="col-lg-3 col-sm-12">
                                        <input type="text" name="impAsign" class="input-bordered input-font currency" placeholder="Importe asignado ($)" value="{{old('impAsign') ?? $venta->importeAsignadoCo}}" id="impAsign">
                                            @if ($errors->has('impAsign'))
                                                <div class="alert alert-danger">
                                                    {{$errors->first('impAsign')}}
                                                </div>
                                            @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label  class="col-lg-2 col-sm-12 col-form-label field-label">Condición de pago<span style="color: red">*</span></label>
                                    <div class="col-lg-3 col-sm-12">
                                        {!! Form::select(
                                            'selectCondicion',
                                            $condiciones,
                                            old('selectCondicion') ?? $venta->condicionPago,
                                            ['id' => 'selectCondicion', 'class' => 'marginB-10', 'placeholder' => 'Selecciona una condición', 'required' => 'required'],
                                        ) !!}
                                        <input type="hidden" name="inputTipoCondicion" id="inputTipoCondicion">
                                        @if ($errors->has('selectCondicion'))
                                            <div class="alert alert-danger">
                                                {{$errors->first('selectCondicion')}}
                                            </div>
                                        @endif
                                    </div> 
                                    <div class="col-lg-3 col-sm-12">
                                        <input type="date" name="inputFechaVencimiento" class="input-bordered input-font" placeholder="Fecha vencimiento" readonly id="inputFechaVencimiento" value="{{  \Carbon\Carbon::parse($venta->fechaVencimiento)->format('Y-m-d')}}">
                                    </div>
                                    @if($venta->movimiento != 'Contrato' && $venta->movimiento != null && $venta->movimiento != 'Factura')
                                    <label  class="col-lg-1 col-sm-12 col-form-label field-label">Importe Financiado</label>
                                    <div class="col-lg-2 col-sm-12">
                                        <input type="text" name="inputImporteMensualidad" class="input-bordered input-font currency" placeholder="$ 0.00" readonly id="inputFechaVencimiento" value="{{  $venta->importeMensualidad}}">
                                    </div>
                                    @endif


                                </div>
                               
                                <div class="form-group row">
                                    <label  class="col-lg-2 col-sm-12 col-form-label field-label">Tipo de contrato<span style="color: red">*</span></label>
                                    <div class="col-lg-4 col-sm-12">
                                        <div class="input-radio-2">
                                            <input type="radio" name="radioContrato" class="input-radio input-font radioTipoContrato" value="Venta" required="" aria-required="true" id="radioVenta" onchange="tipoContrato(this)" @if(old('radioContrato') == 'Venta' || $venta->tipoContrato == '1') checked @endif>
                        
                                            <label class="field-label-radio" for="radioVenta">Venta</label>
                                        </div>
                                        <div class="input-radio-2">
                                            <input type="radio" name="radioContrato" class="input-radio input-font radioTipoContrato" value="Renta" required="" aria-required="true" id="radioRenta" onchange="tipoContrato(this)" @if(old('radioContrato') == 'Renta' || $venta->tipoContrato == '0') checked @endif>
                                            <label for = "radioRenta" class="field-label-radio">Renta</label>
                                        </div>
                                        @if ($errors->has('radioContrato'))
                                            <div class="alert alert-danger">
                                                {{$errors->first('radioContrato')}}
                                            </div>
                                        @endif
                                    </div> 
                                    <label class="col-lg-2 col-sm-12 col-form-label field-label">Esquema de pago<span style="color: red">*</span></label>
                                    <div class="col-lg-4 col-sm-12">
                                        <div class="input-radio-2">
                                            <input type="radio" name="radioEsquema" class="input-radio input-font radioEsquema" value="Mensualidad" required="" aria-required="true" id="radioMensualidades" onchange="tipoEsquema(this)" @if(old('radioEsquema') == 'Mensualidad' || $venta->esquemaPago == '1') checked @endif>
                                            <label class="field-label-radio" for="radioMensualidades">Mensualidades</label>
                                        </div>
                                        <div class="input-radio-2">
                                            <input type="radio" name="radioEsquema" class="input-radio input-font radioEsquema" value="Contado" required=""  id="radioContado" aria-required="true" onchange="tipoEsquema(this)" @if(old('radioEsquema') == 'Contado' || $venta->esquemaPago == '0') checked @endif>
                                            <label class="field-label-radio" for="radioContado">Contado</label>
                                        </div>
                                        @if ($errors->has('radioEsquema'))
                                            <div class="alert alert-danger">
                                                {{$errors->first('radioEsquema')}}
                                            </div>
                                        @endif
                                    </div> 
                                </div>
                            </div>

                                <div class="col-lg-12 row-2">
                                    <div class=" table-responsive">
                                        <div class="datatable-container">
                                         
                                                <span class="table-add float-right mb-2 mr-2">
                                                    <a id="btnAgregar"><i class="fas fa-plus fa-2x" data-toggle="modal" data-target="#modalArticulos" style="cursor: pointer"></i></a>
                                                </span>
                                                <table class="table table-bordered table-responsive-md table-striped text-center" id="tableArticulos" border="0" cellpadding="0" cellspacing="0">
                                                    <thead >
                                                        <tr>
                                                            <th class="text-center" style="display: none" id="thAplica">Aplica</th>
                                                            <th class="text-center" style="display: none" id="thConsecutivo">Consecutivo</th>
                                                            <th class="text-center">Artículo</th>
                                                            <th class="text-center">Descripción</th>
                                                            <th class="text-center">Cantidad</th>
                                                            <th class="text-center">Unidad</th>
                                                            <th class="text-center">Precio</th>
                                                            <th class="text-center">Importe</th>
                                                            <th class="text-center">% IVA</th>
                                                            <th class="text-center">Importe IVA</th>
                                                            <th class="text-center">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="itemArticulos">
                                                        
                                                        @if(count($venta->getDetalle) > 0)
                                                        <tr id="controlArticulo" style="display:none">
                                                            <td style="display:none"></td>
                                                            <td style="display:none"></td>
                                                            <td id="keyArticulo" contenteditable="true" oninput="buscadorArticulo(this)"></td>
                                                            <td></td>
                                                            <td contenteditable="true" oninput="calculoImporte(this)" ></td>
                                                            <td></td>
                                                            <td contenteditable="true" oninput="calculoImporte(this)">$0.00</td>
                                                            <td>$0.00</td>
                                                            <td>$0.00</td>
                                                            <td>$0.00</td>
                                                            <td>$0.00</td>
                                                            <td>
                                                                <i onclick="eliminarRenglon(this)" class="fa fa-trash  btn-delete-articulo" aria-hidden="true" style="color: red; font-size: 25px; cursor: pointer;"></i>
                                                            </td>
                                                            <td style="display:none"></td>
                                                        </tr>
                                                     
                
                                                            @foreach($venta->getDetalle as $key => $detalle)
                                                                <tr id="controlArticulo-{{$key}}">
                
                                                                    @if($detalle->origen != null && $detalle->origenId != null)
                                                                    <td>{{$detalle->origen}}</td>
                                                                    <td>{{$detalle->origenId}}</td>
                
                                                                    <script>
                                                                        $(document).ready(function(){
                                                                            $("#thAplica").show();
                                                                            $("#thConsecutivo").show();
                
                                                                            $(".tdAplica").show();
                                                                            $(".tdConsecutivo").show();
                                                                        });
                                                                    </script>
                
                                                                    @else
                                                                    <td style="display:none" class="tdAplica"></td>
                                                                    <td style="display:none" class="tdConsecutivo"></td>
                                                                    @endif
                
                                                                    <td id="keyArticulo" contenteditable="true" oninput="buscadorArticulo(this)">{{$detalle->articulo}}</td>
                                                                    <td>{{$detalle->descripcionArticulo}}</td>
                                                                    <td contenteditable="true" oninput="calculoImporte(this)">{{$detalle->cantidad}}</td>
                                                                    <td>{{$detalle->unidadVenta}}</td>
                                                                    <td contenteditable="true" oninput="calculoImporte(this)">${{number_format($detalle->precioArticulo,2)}}</td>
                                                                    <td>${{number_format($detalle->importe,2)}}</td>
                                                                    <td>{{$detalle->IVA}}%</td>
                                                                    <td>${{number_format($detalle->importeIVA,2)}}</td>
                                                                    <td>${{number_format($detalle->importeTotal,2)}}</td>
                                                                    <td>
                                                                        <i onclick="eliminarRenglon(this)" class="fa fa-trash  btn-delete-articulo" aria-hidden="true" style="color: red; font-size: 25px; cursor: pointer;"></i>
                                                                    </td>
                                                                    <td style="display:none">{{$detalle->idVentaD}}</td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                
                                                        <tr id="controlArticulo">
                                                            <td style="display:none"></td>
                                                            <td style="display:none"></td>
                                                            <td id="keyArticulo" contenteditable="true" oninput="buscadorArticulo(this)"></td>
                                                            <td></td>
                                                            <td contenteditable="true" oninput="calculoImporte(this)" ></td>
                                                            <td></td>
                                                            <td contenteditable="true" oninput="calculoImporte(this)">$0.00</td>
                                                            <td>$0.00</td>
                                                            <td>$0.00</td>
                                                            <td>$0.00</td>
                                                            <td>$0.00</td>
                                                            <td>
                                                                <i onclick="eliminarRenglon(this)" class="fa fa-trash  btn-delete-articulo" aria-hidden="true" style="color: red; font-size: 25px; cursor: pointer;"></i>
                                                            </td>
                                                            <td style="display:none"></td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                             
                                        </div>
                                    </div>
                                </div>
                            
                            <!-- table articles -->
                            
                            <!-- table-articles -->
                            <div class="col-lg-12 col-md-12 col-sm-12 row-1">
                                <div class="row form-group input-report" style="margin: 0px;">

                                    <div class="col-lg-5 col-md-12 col-sm-12" style="">
                                        <div class="form-group row" >
                                            <div class="col-lg-7 col-md-12 col-sm-12" style="">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <input type="text" name="renglones" class="input-bordered input-font input-area" placeholder="# artículos"  id="cantidadArticulos" readonly value="{{old('renglones') ?? $venta->renglones}}">
        
                                                        </div>
                                                        <div class="col-lg-6">
                                                            @if($venta->movimiento != 'Contrato' && $venta->movimiento != 'Factura' && $venta->movimiento != 'Inversión Inicial' && $venta->movimiento != 'Finiquito')
                                                        <input type="text" name="morosidad" class="input-bordered input-font percentage input-area "   id="morosidad" placeholder="% Morosidad" readonly value="{{old('morosidad') ?? $venta->porcentajeMorosidad}}">
                                                        @endif
                                                        <input type="text" name="inputArticles" id="inputArticles" readonly hidden />
                                                        <input type="text" name="inputArticlesDelete" id="inputArticlesDelete" readonly hidden />
                                                        </div>
                                                </div>
                                               
                                            </div>
                                            <div class="col-lg-5 col-md-12 col-sm-12" >
                                                @if($venta->movimiento != 'Contrato' && $venta->movimiento != 'Factura')
                                                    <div class="row">
                                                        <div class="col-lg-4 vertical-center">
                                                            <p>Saldo: </p>
                                                        </div>
                                                        <div class="col-lg-8">
                                                            <input type="text" name="inputSaldo" class="input-bordered input-font currency" placeholder="Saldo"  id="inputSaldo" readonly value="{{old('inputSaldo', $venta->saldo) ?? 0}}"> 
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-7 col-md-12 col-sm-12">
                                        <div class="form-group row" >
                                            <div class="col-lg-4 col-sm-12">
                                                <div class="row">
                                                    <div class="col-lg-4 vertical-center">
                                                        <p>Importe: </p>
                                                    </div>
                                                    <div class="col-lg-8">
                                                        <input type="text" name="inputSubtotal" id="inputSubtotal" class="input-bordered input-font currency input-area" placeholder="Subtotal" readonly value="{{ $venta->subTotal}}">

                                                        <input type="hidden" name="inputSubtotalOld" value="{{$venta->subTotalOld}}">
                                                    </div>
                                                
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-4 col-sm-12">
                                                
                                                <div class="row">
                                                    <div class="col-lg-5 vertical-center">
                                                        <p>Impuestos: </p>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <input type="text" name="inputImpuestos"  id="inputImpuestos" class="input-bordered input-font currency input-area" placeholder="Impuestos" readonly value="{{ $venta->impuestos}}">
                                                        <input type="hidden" name="inputImpuestosOld" value="{{$venta->impuestosOld}}">
                                                    </div>
                                                
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-12">
                                              
                                                <div class="row">
                                                    <div class="col-lg-3 vertical-center">
                                                        <p>Total: </p>
                                                    </div>
                                                    <div class="col-lg-9">
                                                        <input type="text" name="inputTotal"  id="inputTotal" class="input-bordered input-font currency input-area" placeholder="Importe total" readonly value="{{ $venta->total}}">
                                                        <input type="hidden" name="inputTotalOld" value="{{$venta->totalOld}}">
                                                    </div>
                                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- tab-pane -->
            <div class="tab-pane" id="tab-2">
                <div class="row">
                    {{-- Row1 --}}
                    <div class="col-lg-2">
                        <div class="row">
                            <div class="col-lg-10 row-1">
                                <div class="form-group row">
                                    <div class="col-lg-12 col-sm-12">
                                        <input readonly type="text" name="inputModuloV" class="input-bordered input-font" placeholder="Unidad/módulo" id="inputModuloV">
                                    </div>
                                    <div class="col-lg-12 col-sm-12">
                                        <input readonly type="text" name="inputMT2V" class="input-bordered input-font" placeholder="MT2" id="inputMT2V">
                                    </div>
                                    <div class="col-lg-12 col-sm-12">
                                        <input readonly type="text" name="inputValorV" class="input-bordered input-font currency" placeholder="Valor de operación" id="inputValorV">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Row2 --}}
                    <div class="col-lg-5">
                        <div class="row">
                            <div class="col-lg-10 row-1">
                                <div class="form-group row">
                                    <label  class="col-sm-6 col-form-label field-label">Fecha de contrato<span style="color: red">*</span></label>
                                    <div class="col-lg-6 col-sm-12">
                                        <input type="date" name="inputFechaContrato" class="input-bordered input-font" id="inputFechaContrato" required
                                        value="{{old('inputFechaContrato') ?? \Carbon\Carbon::parse($venta->fechaContrato)->format('Y-m-d')}}"
                                        >
                                        @if ($errors->has('inputFechaContrato'))
                                            <div class="alert alert-danger">
                                                {{$errors->first('inputFechaContrato')}}
                                            </div>
                                        @endif
                                    </div> 
                                    <label  class="col-sm-6 col-form-label field-label">Promoción</label>
                                    <div class="col-lg-6 col-sm-12 input-font">
                                        {!! Form::select(
                                            'selectPromo',
                                            $promociones,
                                            old('selectPromo') ?? $venta->promocion,
                                            [ 'class' => '', 'id' => 'selectPromocion', 'placeholder' => 'Selecciona una promoción'],
                                        ) !!}
                                        @if ($errors->has('selectPromo'))
                                            <div class="alert alert-danger">
                                                {{$errors->first('selectPromo')}}
                                            </div>
                                        @endif
                                    </div> 
                                    <label  class="col-sm-6 col-form-label field-label">Periodicidad<span style="color: red">*</span></label>
                                    <div class="col-lg-6 col-sm-12 input-font">
                                        {!! Form::select(
                                            'selectPeriodicidad',
                                            ['Semanal' => 'Semanal', 'Mensual' => 'Mensual', 'Trimestral' => 'Trimestral', 'Bimestral' => 'Bimestral', 'Anual' => 'Anual'],
                                            old('selectPromo', $venta->periocidad) ?? 'Mensual' ,
                                            [ 'class' => '', 'id' => 'selectPeriodicidad', 'required' => 'required'],
                                        ) !!}
                                        @if ($errors->has('selectPeriodicidad'))
                                            <div class="alert alert-danger">
                                                {{$errors->first('selectPeriodicidad')}}
                                            </div>
                                        @endif
                                    </div>
                                    <label  class="col-sm-6 col-form-label field-label">Fecha inicio mensualidad<span style="color: red">*</span></label>
                                    <div class="col-lg-6 col-sm-12">
                                        <input type="date" name="inputFechaIni" class="input-bordered input-font" id="inputFechaIni" required
                                        value="{{old('inputFechaIni') ?? \Carbon\Carbon::parse($venta->fechaInicioMensualidad)->format('Y-m-d')}}"
                                        >
                                        @if ($errors->has('inputFechaIni'))
                                            <div class="alert alert-danger">
                                                {{$errors->first('inputFechaIni')}}
                                            </div>
                                        @endif
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Row3 --}}
                    <div class="col-lg-5">
                        <div class="row">
                            <div class="col-lg-10 row-1">
                                <div class="form-group row">
                                    <label  class="col-sm-6 col-form-label field-label">Mantenimiento anual </label>
                                    <div class="col-lg-6 col-sm-12 input-font">
                                        {!! Form::text('inputAnual', 
                                        old('inputAnual') ?? $venta->mantenimientoAnual,
                                        ['class'=>'input-bordered currency', 'placeholder' => '$0.00', 'id' => 'inputAnual']) !!}
                                        @if ($errors->has('inputAnual'))
                                            <div class="alert alert-danger">
                                                {{$errors->first('inputAnual')}}
                                            </div>
                                        @endif
                                    </div> 
                                    <label  class="col-sm-6 col-form-label field-label">Financiamiento (meses)<span style="color: red">*</span></label>
                                    <div class="col-lg-6 col-sm-12 input-font">
                                        {!! Form::text('inputMeses', 
                                        old('inputMeses') ?? $venta->financiamientoMeses,
                                        ['class'=>'input-bordered', 'placeholder' => '#', 'id' => 'inputMeses', 'required' => 'required']) !!}
                                        @if ($errors->has('inputMeses'))
                                            <div class="alert alert-danger">
                                                {{$errors->first('inputMeses')}}
                                            </div>
                                        @endif
                                    </div> 
                                    <label  class="col-sm-6 col-form-label field-label">Enganche<span style="color: red">*</span></label>
                                    <div class="col-lg-6 col-sm-12 input-font">
                                        {!! Form::text('inputEnganche', 
                                        old('inputEnganche') ?? $venta->enganche,
                                        ['class'=>'input-bordered percentage porcent','placeholder' => '%', 'id' => 'inputEnganche', 'required' => 'required']) !!}
                                        @if ($errors->has('inputEnganche'))
                                            <div class="alert alert-danger">
                                                {{$errors->first('inputEnganche')}}
                                            </div>
                                        @endif  
                                    </div> 
                                    @php
                                        // dd($venta->fechaFinContrato)
                                    @endphp
                                    <label  class="col-sm-6 col-form-label field-label">Finiquito / Fecha final<span style="color: red">*</span></label>
                                    <div class="col-lg-6 col-sm-12">
                                        <input type="date" name="inputFechaFin" class="input-bordered input-font" id="inputFechaFin"
                                        value="{{old('inputFechaFin') ?? \Carbon\Carbon::parse($venta->fechaFinContrato)->format('Y-m-d')}}"
                                        >
                                        @if ($errors->has('inputFechaFin'))
                                            <div class="alert alert-danger">
                                                {{$errors->first('inputFechaFin')}}
                                            </div>
                                        @endif  
                                    </div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Row4 --}}

                <h3>Plan de Venta</h3>
                <div class="row">
                    <div class="col-lg-5" style="">
                        <div class="row" style="">

                            
                            <div class="col-lg-12 mb-3">
                                <a href="#" class="btn btn-sm btn-info float-right" id="btnAnticipos" onclick="getAnticipos()" data-toggle="modal" data-target="#modalAnticiposMov">Ver Anticipos Cliente</a>
                            </div>
                                                                    
                                <!-- table-planventa -->
                                <div class="col-lg-12 col-sm-12" id="table" class="table-editable">
                                    <table class="table table-bordered table-striped text-center" id="tablePlanVenta">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Concepto</th>
                                                <th class="text-center">Importe</th>
                                                <th class="text-center">%</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyPlanVenta">
                                            <tr id="trPrecioInmueble">
                                                <th class="text-center">Precio inmueble</th>
                                                <td id="tdPrecio" name="tdPrecio">$0.00</td>
                                                <td style="display: none"></td>
                                            </tr>
                                            <tr id="trInversionInmueble">
                                                <th class="text-center">Inversión inicial</th>
                                                <td id="tdInversion" name="tdInversion">$0.00</td>
                                                <td contenteditable="true" id="tdInversionP" name="tdInversionP" onkeyup="validarPorcentaje();" class="percentage" oninput="calcularPorcentaje('tdInversionP', 'tdInversion')"></td>
                                            </tr>
                                            <tr id="trMensualidadesInmueble">
                                                <th class="text-center">Mensualidades</th>
                                                <td id="tdMensualidades" name="tdMensualidades">$0.00</td>
                                                <td contenteditable="true" id="tdMensualidadesP" name="tdMensualidadesP" onkeyup="validarPorcentaje();"  class="percentage porcent" oninput="calcularPorcentaje('tdMensualidadesP', 'tdMensualidades')"></td>
                                            </tr>
                                            <tr id="trFiniquitoInmueble">
                                                <th class="text-center">Finiquito</th>
                                                <td id="tdFiniquito" name="tdFiniquito">$0.00</td>
                                                <td contenteditable="true" id="tdFiniquitoP" name="tdFiniquitoP" onkeyup="validarPorcentaje();" class="percentage porcent" oninput="calcularPorcentaje('tdFiniquitoP', 'tdFiniquito')"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <input type="hidden" name="inputTablePlanVenta" id="inputTablaVenta">
                                {{-- <input type="text" name="inputTotalPorcentaje" id="inputTotalPorcentaje"> --}}
                           


                        </div>
                        
                    </div>

                    <div class="col-lg-7" style="">
                        <div class="row mt-5">
                            <!-- table-planventa2 -->
                            <div class="col-lg-12" id="table" class="table-editable">
                                {{-- <table class="table table-bordered table-responsive-md table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th class="text-center"></th>
                                            <th class="text-center">Fecha de pago</th>
                                            <th class="text-center">Monto</th>
                                            <th class="text-center">IVA</th>
                                            <th class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyInversion">
                                        <tr id = "trInversionInicial">
                                            <td style="display:none">1</td>
                                            <td >Inversión Inicial</td>
                                            <td ></td>
                                            <td ></td>
                                            <td ></td>
                                            <td ></td>
                                        </tr>
                                        <tr id = "trFiniquito">
                                            <td style="display:none">1</td>
                                            <td>Finiquito</td>
                                            <td id="tdFiniquito"></td>
                                            <td id="tdFiniquito"></td>
                                            <td id="tdFiniquito"></td>
                                            <td id="tdFiniquito"></td>
                                        </tr>
                                    </tbody>
                                </table> --}}
                            </div>
                       
                        </div>
                    </div>

                    <div class="col-lg-12 mt-3">

                        <div class="row mb-2">
                            <div class="col-lg-8">
                                <table class="table table-bordered table-responsive-md table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th class="text-center"></th>
                                            <th class="text-center">Fecha de pago</th>
                                            <th class="text-center">Monto</th>
                                            <th class="text-center">IVA</th>
                                            <th class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyInversion">
                                        <tr id = "trInversionInicial">
                                            <td style="display:none">1</td>
                                            <td >Inversión Inicial</td>
                                            <td ></td>
                                            <td ></td>
                                            <td ></td>
                                            <td ></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <a href="#" class="btn btn-sm btn-warning float-right" id="btnCorrida">GENERAR CORRIDA</a>
                            </div>
                        </div>

                        <div class="row">
                            <!-- table-planventa2 -->
                            <div class="col-lg-8 row-1" id="table" class="table-editable">


                                <table class="table table-bordered table-responsive-md table-striped text-center">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No. Mensualidad</th>
                                            <th class="text-center">Fecha de pago</th>
                                            <th class="text-center">Monto</th>
                                            <th class="text-center">IVA</th>
                                            <th class="text-center">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableCorrida">
                                     
                                    </tbody>
                                </table>
                                <input type="hidden" name="inputResultadoCorrida" id="inputResultadoCorrida">
                            </div>
                            <!-- table-planventa2 -->
                            <div class="col-lg-8 col-sm-12">
                                <div class="form-group row" >
                                    <div class="col-lg-4 col-sm-12">
                                        <div class="row">
                                            <div class="col-lg-5 vertical-center">
                                                <p>Total monto: </p>
                                            </div>
                                            <div class="col-lg-7">
                                                <input type="text" name="inputMonto" class="input-bordered input-font currency" placeholder="Suma Total Monto" readonly id="inputMonto">
                                                <input type="text" name="inputMontoOld" class="input-bordered input-font currency" placeholder="Suma Total Monto" readonly id="inputMontoOld" style="display: none">
                                            </div>
                                        
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-4 col-sm-12">
                                        
                                        <div class="row">
                                            <div class="col-lg-4 vertical-center">
                                                <p>Total IVA: </p>
                                            </div>
                                            <div class="col-lg-8">
                                                <input type="text" name="inputIVA" class="input-bordered input-font currency" placeholder="Suma Total IVA" readonly id="inputIVA">
                                                <input type="text" name="inputIVAOld" class="input-bordered input-font currency" placeholder="Suma Total IVA" readonly id="inputIVAOld" style="display: none">
                                            </div>
                                        
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-12">
                                      
                                        <div class="row">
                                            <div class="col-lg-3 vertical-center">
                                                <p>Total: </p>
                                            </div>
                                            <div class="col-lg-9">
                                                <input type="text" name="inputTotalTabla" class="input-bordered input-font currency" placeholder="Suma Total General" readonly id="inputTotalTabla">
                                                <input type="text" name="inputTotalTablaOld" class="input-bordered input-font currency" placeholder="Suma Total General" readonly id="inputTotalTablaOld" style="display: none">
                                            </div>
                                        
                                        </div>
                                    </div>
                                </div>

                                <table class="table table-bordered table-responsive-md table-striped text-center">
                                    <tbody id="tbodyInversion2">
                                        <tr id = "trFiniquito">
                                            <td style="display:none">1</td>
                                            <td>Finiquito</td>
                                            <td id="tdFiniquito"></td>
                                            <td id="tdFiniquito"></td>
                                            <td id="tdFiniquito"></td>
                                            <td id="tdFiniquito"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            {{-- <div class="col-lg-8">
                                <div class="col-lg-8 col-sm-12 row" style="float: right;">
                                    <div class="row">
                                        <div class="col-lg-4 col-sm-12">
                                            <input type="text" name="inputMonto" class="input-bordered-2 input-font currency" placeholder="Suma Total Monto" readonly id="inputMonto">
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <input type="text" name="inputIVA" class="input-bordered-2 input-font currency" placeholder="Suma Total IVA" readonly id="inputIVA">
                                        </div>
                                        <div class="col-lg-4 col-sm-12">
                                            <input type="text" name="inputTotalTabla" class="input-bordered-2 input-font currency" placeholder="Suma Total General" readonly id="inputTotalTabla">
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div> 
            <!-- tab-pane -->
            <div class="tab-pane" id="tab-3">
                <div class="nk-block-text">
                    {{-- <h2 class="title">Comisiones</h2> --}}
                    <div class="row">
                        <div class="col-lg-6 row-1">
                            <div class="row form-group">
                                <div class="col-lg-3">
                                    <label for="selectEtiqueta" class="field-label">Etiqueta</label>
                                </div>
                                <div class="col-lg-8">
                                    {!! Form::select('selectEtiqueta', $etiquetas, 
                                    old('selectEtiqueta') ?? $venta->etiqueta, 
                                    ['id' => 'selectEtiqueta', 'placeholder' => 'Seleccione una Etiqueta']) !!}
                                    @if ($errors->has('selectEtiqueta'))
                                        <div class="alert alert-danger">
                                            {{$errors->first('selectEtiqueta')}}
                                        </div>
                                    @endif 
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-lg-3">
                                    <label for="selectVendedor" class="field-label">Vendedor</label>
                                </div>
                                <div class="col-lg-8">
                                    {!! Form::select('selectVendedor', $vendedor, 
                                    old('selectVendedor') ?? '', 
                                    ['id' => 'selectVendedor', 'placeholder' => 'Seleccione un Vendedor']) !!}
                                    @if ($errors->has('selectVendedor'))
                                        <div class="alert alert-danger">
                                            {{$errors->first('selectVendedor')}}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-lg-3">
                                    
                                </div>
                                <div class="col-lg-4">
                                    {!! Form::text('inputValOperacionC', null, 
                                    ['id' => 'inputValOperacionC','class' => 'input-bordered currency','readonly','placeholder' => '$ 0.00','id' => 'inputValOperacionC']) !!}
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-lg-3">
                                    <label for="inputEngancheC" class="field-label">Enganche</label>
                                </div>
                                <div class="col-lg-3 marginB-10">
                                    {!! Form::text('inputEngancheC', old('inputEngancheC') ?? $venta->porcentajeEnganche, ['class' => 'input-bordered percentage','placeholder' => '%','id' => 'inputEngancheC']) !!}
                                </div>
                                <div class="col-lg-6">
                                    {!! Form::text('inputImporteEnganche', null, ['class' => 'input-bordered currency','readonly','id' => 'inputImporteEnganche']) !!}
                                </div>
                                @if ($errors->has('inputEngancheC'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputEngancheC')}}
                                    </div>
                                @endif
                            </div>
                            <div class="row form-group">
                                <div class="col-lg-3">
                                    <label class="field-label">%Comisionable</label>
                                </div>
                                <div class="col-lg-3 marginB-10">
                                    {!! Form::text('inputComisionable', old('inputComisionable') ?? $venta->porcentajeComisionable, ['class' => 'input-bordered percentage','placeholder' => '%','id' => 'inputComisionable']) !!}
                                </div>
                                <div class="col-lg-6 marginB-10">
                                    {!! Form::text('inputMontoComisionable', null, ['class' => 'input-bordered currency','readonly','id' => 'inputMontoComisionable']) !!}
                                </div>
                                @if ($errors->has('inputMontoComisionable'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('inputMontoComisionable')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-6 row-1">
                            <div class="row form-group" >
                                <div class="col-lg-2">
                                    <input class="input-checkbox checksTipo" type="radio" value="1" name="chekTipoComisión" id="checkVenta" required
                                    @if(old('chekTipoComisión') == 1 || $venta->tipoComision == 1) checked @endif
                                    >
                                    <label for="checkVenta" class="field-label">Venta</label>
                                </div>
                                <div class="col-lg-2">
                                    <input class="input-checkbox checksTipo" type="radio" value="2" name="chekTipoComisión" id="checkRenta" required
                                    @if(old('chekTipoComisión') == 2 || $venta->tipoComision == 2) checked @endif
                                    >
                                    <label for="checkRenta" class="field-label">Renta</label>
                                </div>
                                <div class="col-lg-2">
                                    <input class="input-checkbox checksTipo" type="radio" value="3" name="chekTipoComisión" id="checkSocio" required
                                    @if(old('chekTipoComisión') == 3 || $venta->tipoComision == 3) checked @endif
                                    >
                                    <label for="checkSocio" class="field-label">Socios</label>
                                </div>
                                @if ($errors->has('chekTipoComisión'))
                                    <div class="alert alert-danger">
                                        {{$errors->first('chekTipoComisión')}}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <h2 class="title">Importe Comisión:</h2>
                    <div class="row">
                        <div class="col-lg-12 row-1" id="importesContenedor">
                            <div class="row form-group">
                                <div class="col-lg-2">
                                    <div class="marginT"></div>
                                    <label class="field-label" >Asesor:</label>
                                </div>
                                <div class="col-lg-3">
                                    <div class="marginT"></div>
                                    {!! Form::text('inputAsesor', old('inputAsesor') ?? $venta->asesor, ['class' => 'input-bordered ','readOnly','id' => 'inputAsesor']) !!}
                                </div>
                                <div class="col-lg-1">
                                    <div class="marginT"></div>
                                    {!! Form::text('inputPorcentajeAsesor', old('inputPorcentajeAsesor') ?? $venta->porcentajeAsesor, ['class' => 'input-bordered percentage neto ','placeholder' => '%','id' => 'inputPorcentajeAsesor', 'onchange' => 'calculosPorcentaje(inputPorcentajeAsesor, inputNetoAsesor, importeFacAsesor)']) !!}
                                </div>
                                <div class="col-lg-2">
                                    <label  class="field-label">Neto:</label>
                                    {!! Form::text('inputNetoAsesor', old('inputNetoAsesor') ?? $venta->netoAsesor, ['class' => 'input-bordered currency neto','placeholder' => '$','id' => 'inputNetoAsesor', 'onchange' => 'calculosNeto(inputPorcentajeAsesor, inputNetoAsesor, importeFacAsesor)']) !!}
                                </div>
                                <div class="col-lg-2">
                                    <label  class="field-label">Factura:</label>
                                    {!! Form::text('importeFacAsesor', old('importeFacAsesor') ?? $venta->facturaAsesor, ['class' => 'input-bordered currency neto','placeholder' => '$','id' => 'importeFacAsesor', 'onchange' => 'calculosFactura(inputPorcentajeAsesor, inputNetoAsesor, importeFacAsesor)']) !!}
                                </div>
                                <div class="col-lg-2">
                                    <label  class="field-label">Forma de Pago</label>
                                    {!! Form::text('inputFormaPagoAsesor',  old('inputFormaPagoAsesor') ?? $venta->formaPagoAsesor, ['class' => 'input-bordered formasPago','placeholder' => '']) !!}
                                    @if ($errors->has('inputFormaPagoAsesor'))
                                        <div class="alert alert-danger">
                                            {{$errors->first('inputFormaPagoAsesor')}}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-lg-2">
                                    <label  class="field-label" >Referido:</label>
                                </div>
                                <div class="col-lg-3">
                                    {!! Form::text('inputReferido', old('inputReferido') ?? $venta->referido, ['class' => 'input-bordered ','id' => 'inputReferido']) !!}
                                    @if ($errors->has('inputReferido'))
                                        <div class="alert alert-danger">
                                            {{$errors->first('inputReferido')}}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-lg-1">
                                    
                                    <label for="" class="field-label comision-mobile">%</label>
                                    {!! Form::text('inputPorcentajeReferido', old('inputPorcentajeReferido') ?? $venta->porcentajeReferido, ['class' => 'input-bordered percentage neto ','placeholder' => '%','id' => 'inputPorcentajeReferido', 'onchange' => 'calculosPorcentaje(inputPorcentajeReferido, inputNetoReferido, inputFacReferido)']) !!}
                                </div>
                                <div class="col-lg-2">
                                    <label for="" class="field-label comision-mobile">Neto:</label>
                                    {!! Form::text('inputNetoReferido', old('inputNetoReferido') ?? $venta->netoReferido, ['class' => 'input-bordered currency neto ','placeholder' => '$','id' => 'inputNetoReferido', 'onchange' => 'calculosNeto(inputPorcentajeReferido, inputNetoReferido, inputFacReferido)']) !!}
                                </div>
                                <div class="col-lg-2">
                                    <label for="" class="field-label comision-mobile">Factura:</label>
                                    {!! Form::text('inputFacReferido', old('inputFacReferido') ?? $venta->facturaReferido, ['class' => 'input-bordered currency neto ','placeholder' => '$', 'id' => 'inputFacReferido', 'onchange' => 'calculosFactura(inputPorcentajeReferido, inputNetoReferido, inputFacReferido)']) !!}
                                </div>
                                <div class="col-lg-2">
                                    <label for="" class="field-label comision-mobile">Forma de Pago</label>
                                    {!! Form::text('inputFormaPagoReferido', old('inputFormaPagoReferido') ?? $venta->formaPagoReferido, ['class' => 'input-bordered formasPago ','placeholder' => '']) !!}
                                    @if ($errors->has('inputFormaPagoReferido'))
                                        <div class="alert alert-danger">
                                            {{$errors->first('inputFormaPagoReferido')}}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-lg-2">
                                    <label  class="field-label" >Broker:</label>
                                </div>
                                <div class="col-lg-3">
                                    {!! Form::text('inputBroker', old('inputBroker') ?? $venta->broker, ['class' => 'input-bordered ','id' => 'inputBroker']) !!}
                                    @if ($errors->has('inputBroker'))
                                        <div class="alert alert-danger">
                                            {{$errors->first('inputBroker')}}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-lg-1">
                                    <label for="" class="field-label comision-mobile">%</label>
                                    {!! Form::text('inputPorcentajeBroker', old('inputPorcentajeBroker') ?? $venta->porcentajeBroker, ['class' => 'input-bordered percentage neto ','placeholder' => '%', 'id' => 'inputPorcentajeBroker', 'onchange' => 'calculosPorcentaje(inputPorcentajeBroker, inputNetoBroker, inputFacBroker)']) !!}
                                </div>
                                <div class="col-lg-2">
                                    <label for="" class="field-label comision-mobile">Neto:</label>
                                    {!! Form::text('inputNetoBroker', old('inputNetoBroker') ?? $venta->netoBroker, ['class' => 'input-bordered currency neto ' ,'placeholder' => '$','id' => 'inputNetoBroker', 'onchange' => 'calculosNeto(inputPorcentajeBroker, inputNetoBroker, inputFacBroker)']) !!}
                                </div>
                                <div class="col-lg-2">
                                    <label for="" class="field-label comision-mobile">Factura:</label>
                                    {!! Form::text('inputFacBroker', old('inputFacBroker') ?? $venta->facturaBroker, ['class' => 'input-bordered currency neto ' ,'placeholder' => '$','id' => 'inputFacBroker', 'onchange' => 'calculosFactura(inputPorcentajeBroker, inputNetoBroker, inputFacBroker)']) !!}
                                </div>
                                <div class="col-lg-2">
                                    <label for="" class="field-label comision-mobile">Forma de Pago</label>
                                    {!! Form::text('inputFormaPagoBroker', old('inputFormaPagoBroker') ?? $venta->formaPagoBroker, ['class' => 'input-bordered formasPago ' ,'placeholder' => '']) !!}
                                    @if ($errors->has('inputFormaPagoBroker'))
                                        <div class="alert alert-danger">
                                            {{$errors->first('inputFormaPagoBroker')}}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-lg-6"></div>
                                <div class="col-lg-2">
                                    <label for="" class="field-label comision-mobile">Total Neto</label>
                                    {!! Form::text('inputSumaNeto', old('inputSumaNeto') ?? $venta->totalNeto, ['class' => 'input-bordered currency ','readonly','placeholder' => '$ 0.00','id' => 'inputSumaNeto']) !!}
                                </div>
                                <div class="col-lg-2">
                                    <label for="" class="field-label comision-mobile">Total Factura</label>
                                    {!! Form::text('inputSumaImpFac', old('inputSumaImpFac') ?? $venta->totalFactura, ['class' => 'input-bordered currency ','readonly','placeholder' => '$ 0.00','id' => 'inputSumaImpFac']) !!}
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-lg-6"></div>
                                <div class="col-lg-2">
                                    <label for="" class="field-label comision-mobile">Retención</label>
                                    {!! Form::text('inputRetencion', old('inputRetencion') ?? $venta->retencion, ['class' => 'input-bordered currency neto ','placeholder' => '$ Retención','id' => 'inputRetencion', 'onchange'=>'calculoTotalComision()']) !!}
                                    @if ($errors->has('inputRetencion'))
                                        <div class="alert alert-danger">
                                            {{$errors->first('inputRetencion')}}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-lg-5"></div>
                                <div class="col-lg-1">
                                    <label for="inputTotalNeto" class="field-label">Total a pagar: </label>
                                </div>
                                <div class="col-lg-2">
                                    {!! Form::text('inputTotalNeto', old('inputTotalNeto') ?? $venta->totalPago, ['class' => 'input-bordered currency','placeholder' => '$ 0.00','readonly','id' => 'inputTotalNeto']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- tab-pane -->
        </div>
        
        
        <div class="row">
            <div class="col-lg-12 mb-5">
         

            @if (isset($venta) ? $venta['estatus'] === 'SIN AFECTAR' : 'SIN AFECTAR')
                <span class="badge badge-outline badge-light" name="inputEstatus">SIN AFECTAR</span>
            @elseif($venta['estatus'] === 'PENDIENTE')
                <span class="badge badge-outline badge-warning" name="inputEstatus">PENDIENTE</span>
            @elseif($venta['estatus'] === 'POR CONFIRMAR')
                <span class="badge badge-outline badge-warning" name="inputEstatus">POR CONFIRMAR</span>
            @elseif($venta['estatus'] === 'CONCLUIDO')
                <span class="badge badge-outline badge-success" name="inputEstatus">CONCLUIDO</span>
            @elseif($venta['estatus'] === 'CANCELADO')
                <span class="badge badge-outline badge-danger" name="inputEstatus">CANCELADO</span>
            @endif

            <input type="hidden" name="inputEstatus" value="{{ $venta['estatus'] == null ? 'SIN AFECTAR' : $venta['estatus'] }}" id="inputEstatus">
            <input type="hidden" name="inputCobro" id="inputCobro">
            </div>
        </div>

        </div><!-- tab-content -->
    </div>
</div><!-- .col -->
