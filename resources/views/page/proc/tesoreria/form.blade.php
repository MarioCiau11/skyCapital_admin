<div class="row">
    <div class="col-lg-12">
        <p class="tc-danger mr-auto mb-0"> * = Campos Obligatorios</p>
    </div>
    <div class="col-lg-12">
        <h3>Datos Generales</h3>
        <div class="row">
            <div class="col-lg-12">
                <div class="item float-right mb-3">
                    <a class="btn btn-success btn-sm btn-alt btn-outline toggle-tigger" href="#">
                        <span>Menú de opciones</span>
                        <em class="icon fas fa-caret-down fa-1x"></em>
                    </a>
                    <ul class="drop-list drop-list-full drop-list-center toggle-class toggle-drop">
                        @php
                            // dd($movimientos);
                            $user = Auth::user();
                        @endphp
                        @if ($tesoreria->estatus != "CONCLUIDO" && $tesoreria->estatus != "CANCELADO")
                            @if ($tesoreria->movimiento == null && $user->can('Afectar'))
                                <li><a href="#" class="" onclick="afectar()">Afectar <i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                            @else
                                @if ($user->can($tesoreria->movimiento.' E') && $user->can('Afectar'))
                                    <li><a href="#" class="" onclick="afectar()">Afectar <i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                                @endif
                            @endif
                            @if ($tesoreria->estatus != null )
                                <li><a href="#" onclick="eliminar()">Eliminar <i class="fas fa-archive"></i></a></li> 
                            @endif
                        @endif
                        @if ($tesoreria->estatus == "CONCLUIDO")
                        @if($tesoreria->movimiento != 'Depósito' && $tesoreria->movimiento != 'Solicitud Depósito')
                            @if ($user->can('Cancelar'))
                                <li><a href="#" onclick="cancelar()">Cancelar <i class="fas fa-window-close"></i></a></li>
                            @endif
                            {{-- <li><a href="{{route('proc.tesoreria.reporte',['mov' => $tesoreria->movimiento,'idMovimiento' => $tesoreria->idTesoreria])}}">Reporte<i class="fas fa-file-alt"></i></a></li> --}}
                            @endif
                        @endif
                        @if ($tesoreria->estatus != "SIN AFECTAR" && $tesoreria->estatus != null  )
                            <li><a href="{{route('proc.tesoreria.reporte',['mov' => $tesoreria->movimiento,'idMovimiento' => Crypt::encrypt($tesoreria->idTesoreria)])}}">Reporte <i class="fas fa-file-alt"></i></a></li>
                        @endif
                        @if ($tesoreria->estatus != null)
                            <li><a href="{{route('proc.tesoreria.viewAnexo',['tesoreria' => Crypt::encrypt($tesoreria->idTesoreria)])}}">Anexos <i class="fas fa-clipboard-list"></i></a></li>
                        @endif
                    
                        @if ($tesoreria->estatus != "CANCELADO " && $tesoreria->estatus != null && $tesoreria->movimiento != 'Depósito' && $tesoreria->movimiento != 'Solicitud Depósito')
                            <li><a href="#" onclick="copiar()">Copiar <i class="fas fa-copy"></i></a></li>
                        @endif
                        @if ($tesoreria->estatus == "SIN AFECTAR" && $tesoreria->estatus == "CANCELADO")
                            <li><a href="#">Anexos <i class="fas fa-clipboard-list"></a></i></li>
                        @endif
                        <li><a href="{{route('proc.tesoreria.create')}}">Nuevo <i class="fas fa-file"></i></a></li>
                        @if ($tesoreria->estatus != null)
                            <li><a href="#" data-toggle="modal" data-target="#modalFlujo">Ver flujo <i class="fas fa-project-diagram"></i></a></li>
                            <li><a href="#" data-toggle="modal" data-target="#modalTableCuentasSaldo">Inf. Cuentas <i class="fas fa-money-bill"></i></a></li>
                        @endif
                        
                    </ul>
                </div> 
            </div>
        </div>
        {{-- @php
            dd($tesoreria);
        @endphp --}}
        <div class="row form-group">
            <div class="col-lg-4">
                <div>
                    <input type="hidden" name="inputUser" value="{{Auth::user()->user_id}}">
                </div> 
                <label class="field-label">Movimiento </label>
                <div class="field-wrap">
                    {{-- <input type="hidden" name="inpuptIdMovimiento" value="{{isset($tesoreria) ? $tesoreria->idTesoreria : null}}"> --}}
                        
                        @if ($tesoreria->movimiento == 'Depósito' || $tesoreria->movimiento == 'Solicitud Depósito')
                            {!! Form::select(
                                'selectMovimiento',
                                ['Ingreso' => 'Ingreso', 'Egreso' => 'Egreso', 'Transferencia' => 'Transferencia', 'Depósito' => 'Depósito', 'Solicitud Depósito' => 'Solicitud Depósito'],
                                old('selectMovimiento') ?? $tesoreria->movimiento,
                                ['id' => 'selectMovimiento', 'class' => 'marginB-10'],
                            ) !!}
                            <input type="hidden" name="selectMovimiento" value="{{old('selectMovimiento') ?? $tesoreria->movimiento}}">
                            @else
                            {!! Form::select(
                                'selectMovimiento',
                                $movimientos,
                                old('selectMovimiento') ?? $tesoreria->movimiento,
                                ['id' => 'selectMovimiento', 'class' => 'marginB-10','placeholder' => 'Seleccione uno...'],
                            ) !!}
                        @endif
                        {{-- <input type="hidden" name="selectMovimiento" id="selectMovimiento" value="{{$tesoreria->movimiento}}"> --}}
                    @if ($errors->has('selectMovimiento'))
                        <div class="alert alert-danger">
                            {{ $errors->first('selectMovimiento') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-2">
                <div class="field-wrap">
                    <label for="inputFolioMov" class="field-label">Folio</label>
                    <input readonly type="hidden" name="inputID" class="input-bordered input-font" id="inputId" value="{{isset($tesoreria) ? $tesoreria->idTesoreria : null}}">
                    <input readonly id="inputFolioMov" type="text" name="inputFolioMov" class="input-bordered input-font" value="{{ isset($tesoreria->folioMov)?$tesoreria->folioMov : '' }}">
                    @if ($errors->has('inputFolioMov'))
                        <div class="alert alert-danger">
                            {{ $errors->first('inputFolioMov') }}
                        </div>
                    @endif
                </div>
                </div>
                @php
                    // dd($monedas);
                @endphp
            <div class="col-lg-3">
                <label class="field-label">Moneda <span style="color: red">*</span></label>
                <div class="field-wrap">
                    {!! Form::select(
                        'selectMoneda',
                        $monedas,
                        isset($tesoreria) ? $tesoreria['moneda'] : ($parametro ? $parametro->monedaDefault : ''),
                        ['id' => 'selectMoneda', 'class' => 'marginB-10', 'required' => 'required'],
                    ) !!}
                    @if ($errors->has('selectMoneda'))
                        <div class="alert alert-danger">
                            {{$errors->first('selectMoneda')}}
                        </div>
                    @endif
               </div>
            </div>
            <div class="col-lg-3">
                <div class="field-wrap">
                    <label class="field-label">Tipo de cambio </label>
                    <input type="text" name="inputTipoCambio" class="input-bordered input-font currency" id="inputCambio"  readonly value="{{ isset($tesoreria) ? $tesoreria['tipoCambio'] : ($parametro ? $parametro->tipoCambio : '1.0') }}
                    ">
                    @if ($errors->has('inputTipoCambio'))
                        <div class="alert alert-danger">
                            {{ $errors->first('inputTipoCambio') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-6">
                <div class="field-wrap">
                    <label for="inputFechaEmision" class="field-label">Fecha Emisión</label>
                    <input type="date" name="inputFechaEmision" id="inputFechaEmision"  class="input-bordered marginB-10" value="{{ date('Y-m-d') }}" readonly>
                    @if ($errors->has('inputFechaEmision'))
                        <div class="alert alert-danger">
                            {{ $errors->first('inputFechaEmision') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-6">
                <label class="field-label">Forma de pago <span style="color: red">*</span></label>
                {!! Form::select('selectFormaPago', 
                    $formasPago, 
                    isset($tesoreria->getFormaPago) ? $tesoreria['formaPago'] : old('selectFormaPago'), 
                    ['id' => 'selectFormaPago','placeholder' => 'Seleccione una forma de pago', 'class' => 'marginB-10', 'required' => 'required']) !!}

                @if ($errors->has('selectFormaPago'))
                    <div class="alert alert-danger">
                        {{ $errors->first('selectFormaPago') }}
                    </div>
                @endif
            </div>
            <div class="col-lg-4">
                <label class="field-label">Cuenta <span style="color: red">*</span></label>
                <input type="hidden" id="inputIdCuenta" name="inputIdCuenta"  value="">
                <div class="input-group">
                    <input type="text" class="form-control input-bordered input-btn marginB-10" name="inputCuenta" id="inputCuenta"
                    value="{{old('inputCuenta') ?? $tesoreria->cuentaDinero}}" required readonly>
                    <div class="input-group-append"> 
                        <a class="btn btn-auto btn-sm btn-light input-btn marginB-10" data-toggle="modal" data-target="#modalTableCuentas" id="AbrirModalCuentas"><i class="fas fa-search fa-lg"></i></a>
                    </div>
                    @if ($errors->has('inputCuenta'))
                        <div class="alert alert-danger">
                            {{ $errors->first('inputCuenta') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-4">
                <label for="inputNombreCuenta" class="field-label">Nombre</label>
                <input readonly type="text" class="input-bordered" name="inputNombreCuenta" value="{{old('inputNombreCuenta') ?? $tesoreria->nombreCuenta}}" id="inputNombreCuenta">
                <input readonly type="hidden" class="input-bordered" name="inputMonedaCuenta" value="{{old('inputMonedaCuenta') ?? $tesoreria->monedaCuenta}}" id="inputMonedaCuenta">
                @if ($errors->has('inputNombreCuenta'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputNombreCuenta') }}
                    </div>
                @endif
            </div>
            <div class="col-lg-4">
                <label for="inputSaldoCuenta" class="field-label">Saldo cuenta </label>
                <input readonly type="text" class="input-bordered currency marginB-10" name="inputSaldoCuenta" id="inputSaldoCuenta" value="{{old('inputSaldoCuenta') ?? $tesoreria->saldoCuenta}}">
                @if ($errors->has('inputSaldoCuenta'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputSaldoCuenta') }}
                    </div>
                @endif
            </div>
            <div class="col-lg-4 cuentaDestino" style="display: none">
                <label class="field-label">Cuenta Destino<span style="color: red">*</span></label>
                <input type="hidden" id="inputIdCuentaDestino" name="inputIdCuentaDestino"  value="">
                <div class="input-group">
                    <input type="text" class="form-control input-bordered input-btn marginB-10" name="inputCuentaDestino" id="inputCuentaDestino"
                    value="{{old('inputCuentaDestino') ?? $tesoreria->cuentaDineroDestino}}" required readonly>
                    <div class="input-group-append"> 
                        <a class="btn btn-auto btn-sm btn-light input-btn marginB-10" data-toggle="modal" data-target="#modalTableCuentasDestino" id="AbrirModalCuentasDestino"><i class="fas fa-search fa-lg"></i></a>
                    </div>
                    @if ($errors->has('inputCuentaDestino'))
                        <div class="alert alert-danger">
                            {{ $errors->first('inputCuentaDestino') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-4 cuentaDestino" style="display: none">
                <label for="inputNombreCuentaDestino" class="field-label">Nombre</label>
                <input readonly type="text" class="input-bordered marginB-10" name="inputNombreCuentaDestino" id="inputNombreCuentaDestino" value="{{old('inputNombreCuentaDestino') ?? $tesoreria->nombreCuentaDestino}}">
                <input readonly type="hidden" class="input-bordered marginB-10" name="inputMonedaCuentaDestino" id="inputMonedaCuentaDestino" value="{{old('inputMonedaCuentaDestino') ?? $tesoreria->monedaCuentaDestino}}">
                @if ($errors->has('inputNombreCuentaDestino'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputNombreCuentaDestino') }}
                    </div>
                @endif
            </div>
            <div class="col-lg-4">
                <label for="inputImporte" class="field-label">Importe <span style="color: red">*</span></label>
                    <input required type="text" class="input-bordered currency marginB-10" name="inputImporte" id="inputImporte" value="{{old('inputImporte') ?? $tesoreria->importeTotal}}">
                @if ($errors->has('inputImporte'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputImporte') }}
                    </div>
                @endif
            </div>
            <div class="col-lg-4">
                <label for="inputObservaciones" class="field-label">Observaciones</label>
                    <input type="text" class="input-bordered marginB-10" name="inputObservaciones" id="inputObservaciones" value="{{old('inputObservaciones') ?? $tesoreria->observaciones}}">
                @if ($errors->has('inputObservaciones'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputObservaciones') }}
                    </div>
                @endif
            </div>
            <div class="col-lg-4">
                <label for="inputReferencia" class="field-label">Referencia </label>
                    <input type="text" class="input-bordered marginB-10" name="inputReferencia" id="inputReferencia" value="{{old('inputReferencia') ?? $tesoreria->referencia}}">

                @if ($errors->has('inputReferencia'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputReferencia') }}
                    </div>
                @endif
            </div>
        </div>

        @if(count($tesoreria->getDetalle) > 0)
        <div class="row form-group" id="divTabla">
            {{-- <input type="hidden" name="tablaMovimeintosData" id="tablaMovimeintosData"> --}}
            <div class="col-lg-12">
                <div class="table-responsive">
                    <div class="datatable-container">
                        <table class="table table-bordered table-striped text-center" >
                            <thead>
                                <tr>
                                    <th class="text-center">Aplica</th>
                                    <th class="text-center">Aplica Consecutivo</th>
                                    <th class="text-center">Importe</th>
                                    <th class="text-center">Forma Pago</th>
                                </tr>
                            </thead>
                            @php
                            $contador = 0;
                            @endphp
                            <tbody id="tbodyMovimientos">
                                @foreach($tesoreria->getDetalle as $detalle)
                                <tr>
                                    <td>{{ $detalle->aplica}}</td>
                                    <td>{{ $detalle->aplicaConsecutivo}}</td>
                                    <td>${{ number_format($detalle->importe, 2)}}</td>
                                    <td>{{ $detalle->formaPago}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif


    
        <div class="row form-group">
            <div class="col-lg-12">
                @if (isset($tesoreria) ? $tesoreria['estatus'] === 'SIN AFECTAR' : 'SIN AFECTAR')
                    <span class="badge badge-outline badge-light" name="inputEstatus">SIN AFECTAR</span>
                @elseif($tesoreria['estatus'] === 'PENDIENTE')
                    <span class="badge badge-outline badge-warning" name="inputEstatus">PENDIENTE</span>
                @elseif($tesoreria['estatus'] === 'CONCLUIDO')
                    <span class="badge badge-outline badge-success" name="inputEstatus">CONCLUIDO</span>
                @elseif($tesoreria['estatus'] === 'CANCELADO')
                    <span class="badge badge-outline badge-danger" name="inputEstatus">CANCELADO</span>
                @endif
                <input type="hidden" name="inputEstatus" value="{{ $tesoreria['estatus'] == null ? 'SIN AFECTAR' : $tesoreria['estatus'] }}" id="inputEstatus">
                {{-- <input type="hidden" name="inputCobro" id="inputCobro"> --}}
            </div>
        </div>
    </div>
</div>