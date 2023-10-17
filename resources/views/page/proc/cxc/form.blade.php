{{-- <?php
    dd($cxc->cuentaDinero);
?> --}}
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
                            $user = Auth::user();
                            $permissions = $user->getAllPermissions();
                        @endphp
                        @if($cxc->movimiento == 'Factura')
                            @if($cxc->estatus != 'CONCLUIDO' && $cxc->estatus != 'CANCELADO' && $user->can('Afectar'))
                                <li><a href="#" class="" data-toggle="modal" data-target="#modalCobro">Afectar <i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                            @endif
                        @else
                            @if ($cxc->movimiento == null)
                                <li><a href="#" class="" onclick="afectar()">Afectar <i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                            @else
                                @if($cxc->estatus != 'CONCLUIDO' && $cxc->estatus != 'CANCELADO' && $user->can('Afectar') && $user->can($cxc->movimiento.' E'))
                                    @if($cxc->estatus != 'PENDIENTE' )
                                        <li><a href="#" class="" onclick="afectar()">Afectar <i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                                    @else
                                        <li><a href="#" class="" data-toggle="modal" data-target="#modalAplicacion">Afectar <i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
                                    @endif
                                @endif
                            @endif
                        @endif
                        @if ($cxc->estatus == 'CONCLUIDO' || ($cxc->movimiento == 'Anticipo' && $cxc->estatus == 'PENDIENTE'))
                            @if($cxc->movimiento != 'Factura' && $user->can('Cancelar'))
                            <li><a href="#" onclick="cancelar()">Cancelar <i class="fas fa-window-close"></i></a></li>
                            @endif
                        @endif
                        @if($cxc->idCXC)
                            @if($cxc->estatus == 'SIN AFECTAR')
                                <li><a href="#" onclick="eliminar()">Eliminar <i class="fas fa-archive"></i></a></li>
                            @endif
                            @if($cxc->movimiento != 'Factura')
                                <li><a href="#" onclick="copiar()">Copiar <i class="fas fa-copy"></i></a></li>
                            @endif
                        @endif
                        <li><a href="{{ route('proc.cxc.create')}}">Nuevo <i class="fas fa-file"></i></a></li>
                        @if($cxc->idCXC)
                            @if($cxc->estatus != 'SIN AFECTAR')
                                <li><a href="{{route('proc.cxc.viewAnexo', ['cxc' =>Crypt::encrypt($cxc->idCXC)])}}">Anexos <i class="fas fa-clipboard-list"></a></i></li>
                                <li><a href="#" data-toggle="modal" data-target="#modalFlujo">Ver flujo <i class="fas fa-project-diagram"></i></a></li>
                            @endif
                            @if($cxc->estatus == 'SIN AFECTAR')
                                <li><a href="#" data-toggle="modal" data-target="#modalModulo">Inf. módulo <i class="fas fa-info"></i></a></li>
                            @endif
                        @endif
                        @if($cxc->movimiento != null)
                            @if($cxc->movimiento != 'Factura')
                                <li><a href="#" data-toggle="modal" data-target="#modalCliente">Inf. cliente <i class="fas fa-info"></i></a></li>
                            @elseif($cxc->estatus != 'CANCELADO')
                                <li><a href="#" data-toggle="modal" data-target="#modalCliente">Inf. cliente <i class="fas fa-info"></i></a></li>
                            @endif
                            @if($cxc->estatus != 'SIN AFECTAR' ) 
                                @if($cxc->movimiento == 'Factura')
                                    <li><a href="{{route('proc.contrato', ['venta' =>Crypt::encrypt($cxc->origenId)])}}">Reporte factura <i class="fas fa-file-alt"></i></a></li>
                                @elseif($cxc->movimiento != 'Factura')
                                    <li><a href="{{route('proc.cxc.reporte', ['mov' =>strtolower($cxc->movimiento), 'cxc' =>Crypt::encrypt($cxc->idCXC)])}}">Reporte <i class="fas fa-file-alt"></i></a></li>
                                @endif
                            @endif
                        @endif
                    </ul>
                </div> 
            </div>
        </div>
        
        <div class="row form-group">
            <div class="col-lg-3">
                <div>
                    <input type="hidden" name="inputUser" value="{{Auth::user()->user_id}}">
                </div> 
                <label class="field-label">Movimiento </label>
                <div class="field-wrap">
                    <input type="hidden" name="inpuptIdMovimiento" value="{{isset($cxc) ? $cxc->idCXC : null}}">
                    @if ($cxc->movimiento == 'Factura')
                        <select name="selectMovimiento" id="selectMovimiento" class="marginB-10" onchange="showInp()">
                            <option value="{{$cxc->movimiento}}">{{$cxc->movimiento}}</option>
                        </select>
                    @elseif ($cxc->movimiento == 'Anticipo' || $cxc->movimiento == 'Aplicación' || $cxc->movimiento == 'Cobro' ||  $cxc->movimiento == null)
                        {!! Form::select(
                            'selectMovimiento',
                            $movimientos,
                            old('selectMovimiento') ?? $cxc->movimiento,
                            ['id' => 'selectMovimiento', 'class' => 'marginB-10', 'onchange' => 'showInp()','placeholder' => 'Selecciona un movimiento'],
                        ) !!}

                    @endif
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
                    <input readonly type="hidden" name="inputID" class="input-bordered input-font" id="inputIdCxC" value={{$cxc->idCXC}}>
                    <input readonly id="inputFolioMov" type="text" name="inputFolioMov" class="input-bordered input-font" value="{{$cxc->folioMov}}">
                    @if ($errors->has('inputFolioMov'))
                        <div class="alert alert-danger">
                            {{ $errors->first('inputFolioMov') }}
                        </div>
                    @endif
                </div>
                </div>
                <div class="col-lg-3">
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
            <div class="col-lg-2">
                <label class="field-label">Moneda <span style="color: red">*</span></label>
                <div class="field-wrap">

                   
                    {!! Form::select(
                        'selectMoneda',
                        $monedas,
                        isset($cxc) ? $cxc['moneda'] : ($parametro ? $parametro->monedaDefault : ''),
                        ['id' => 'selectMoneda', 'class' => 'marginB-10', 'required' => 'required'],
                    ) !!}
              
                
                    @if ($errors->has('selectMoneda'))
                        <div class="alert alert-danger">
                            {{$errors->first('selectMoneda')}}
                        </div>
                    @endif
               </div>
            </div>
            <div class="col-lg-2">
                <div class="field-wrap">
                    <label class="field-label">Tipo de cambio </label>
                        <input type="text" name="inputTipoCambio" class="input-bordered currency marginB-10" id="inputTipoCambio" value="{{ isset($cxc) ? $cxc['tipoCambio'] : ($parametro ? $parametro->tipoCambio : '1.0') }}">

                    @if ($errors->has('inputTipoCambio'))
                        <div class="alert alert-danger">
                            {{ $errors->first('inputTipoCambio') }}
                        </div>
                    @endif
                </div>
            </div>
          

            <div class="col-lg-2">
                {{-- estructura para un input-btn-group --}}
                <label for="inputCliente" class="field-label ">Cliente <span style="color: red">*</span></label>
                <div class="input-group">
                        <input  type="text" class="form-control input-bordered input-btn marginB-10" name="inputCliente" id="inputCliente" value="{{old('inputCliente') ?? $cxc->cliente}}">
                        <div class="input-group-append">
                            <a class="btn btn-auto btn-sm btn-light input-btn marginB-10" data-toggle="modal" data-target="#modalTableClientes" id="modalClientes"><i class="fas fa-search fa-lg"></i></a>
                        </div>
                </div>
                @if ($errors->has('inputCliente'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputCliente') }}
                    </div>
                @endif
            </div>
            <div class="col-lg-6">
                <label for="inputNombreCliente" class="field-label">Nombre</label>
                <input readonly type="text" class="input-bordered marginB-10" name="inputNombreCliente" id="inputNombreCliente" value="{{old('inputNombreCliente') ?? strtoupper($cxc->getCliente != null ? $cxc->getCliente->razonSocial : '')}}">
                @if ($errors->has('inputNombreCliente'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputNombreCliente') }}
                    </div>
                @endif
            </div>

            <div class="col-lg-12">

            </div>

            <div id="divCuenta" class="col-lg-2" style="display: none;">
                <label for="inputCuenta" class="field-label">Cuenta  <span style="color: red">*</span></label>
                <div class="input-group">

                <input readonly type="text" class="form-control input-bordered input-btn marginB-10" name="inputCuenta" id="inputCuenta"
                value="{{old('inputCuenta') ?? $cxc->cuentaDinero}}">
                <input readonly type="hidden" class="form-control input-bordered input-btn marginB-10" name="inputCuentaMoneda" id="inputCuentaMoneda"
                value="{{old('inputCuentaMoneda') ?? $cxc->cuentaDineroMoneda}}">
                <div class="input-group-append">
                    <a class="btn btn-auto btn-sm btn-light input-btn marginB-10" id = "AbrirModalCuentas"><i class="fas fa-search fa-lg"></i></a>
                </div>

                </div>
                @if ($errors->has('inputCuenta'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputCuenta') }}
                    </div>
                @endif
            </div>
            <div class="col-lg-4">
                <label class="field-label">Forma de pago <span style="color: red">*</span></label>
                    {!! Form::select('selectFormaPago', 
                    $formasPago, 
                    isset($cxc->getFormaPago) ? $cxc['formaPago'] : old('selectFormaPago'), 
                    ['id' => 'selectFormaPago','placeholder' => 'Seleccione una forma de pago', 'class' => 'marginB-10', 'required' => 'required']) !!}

                @if ($errors->has('selectFormaPago'))
                    <div class="alert alert-danger">
                        {{ $errors->first('selectFormaPago') }}
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <label for="inputReferencia" class="field-label">Referencia </label>
                    <input type="text" class="input-bordered marginB-10" name="inputReferencia" id="inputReferencia" value="{{old('inputReferencia') ?? $cxc->referencia}}">

                @if ($errors->has('inputReferencia'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputReferencia') }}
                    </div>
                @endif
            </div>

            <div class="col-lg-3">
                <label for="inputImporte" class="field-label">Importe <span style="color: red">*</span></label>
                    <input type="text" class="input-bordered currency marginB-10" name="inputImporte" id="inputImporte" value="{{old('inputImporte') ?? $cxc->importe}}"  oninput="calcularImporteTotal()" required>
                @if ($errors->has('inputImporte'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputImporte') }}
                    </div>
                @endif
            </div>

            <div class="col-lg-3" id="divImpuestos">
                <label for="inputImpuesto" class="field-label">Impuestos <span style="color: red">*</span></label>
                    <input type="text" class="input-bordered currency marginB-10" name="inputImpuesto" id="inputImpuesto" value="{{old('inputImpuesto') ?? $cxc->impuestos}}" required oninput="calcularImpuestoTotal()">
                @if ($errors->has('inputImpuesto'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputImpuesto') }}
                    </div>
                @endif
            </div>

            <div @if($cxc->movimiento == 'Factura') class="col-lg-3" @else  class="col-lg-3" @endif id="divImporteTotal">
                <label for="inputImporteTotal" class="field-label">Importe total </label>
                <input readonly type="text" class="input-bordered currency marginB-10" name="inputImporteTotal" id="inputImporteTotal" value="{{ old('inputImporteTotal') ?? $cxc->total}}">
                @if ($errors->has('inputImporteTotal'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputImporteTotal') }}
                    </div>
                @endif
            </div>

            <div class="col-lg-12"></div>
            
            <div class="col-lg-2" id="divProyecto">
                <label class="field-label">Proyecto <span style="color: red">*</span></label>
                <input type="hidden" id="inputIdProyecto" name="inputIdProyecto"  value="{{old('inputIdProyecto') ?? $cxc->proyecto}}">
                <div class="input-group">
                    <input type="text" class="form-control input-bordered input-btn marginB-10" name="inputProyecto" id="inputProyecto"
                    value="{{old('inputIdProyecto') ?? $cxc->proyecto}}" required readonly>
                    <div class="input-group-append"> 
                        <a class="btn btn-auto btn-sm btn-light input-btn marginB-10" data-toggle="modal" data-target="#modlaTableProyectos" id="modalProyectos"><i class="fas fa-search fa-lg"></i></a>
                    </div>
                    @if ($errors->has('inputProyecto'))
                        <div class="alert alert-danger">
                            {{ $errors->first('inputProyecto') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-4" id="divNameProyecto">
                <label class="field-label">Nombre proyecto</label>
                <input type="text" class="input-bordered marginB-10" name="inputNombreProyecto" readonly value="{{$cxc->getProyecto != null ? $cxc->getProyecto->nombre : old('inputNombreProyecto')}}" id="inputNombreProyecto">
                @if ($errors->has('inputNombreProyecto'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputNombreProyecto') }}
                    </div>
                @endif
            </div>

            <div class="col-lg-12"></div>

            <div class="col-lg-2" id="divModulo">
                <label class="field-label">Módulo <span style="color: red">*</span></label>
                <div class="input-group">
                    <input type="text" class="form-control input-bordered input-btn marginB-10" name="inputModulo" id="inputModulo"
                    value="{{ old('inputModulo') ?? $cxc->modulo}}"  reuired readonly>
                    <div class="input-group-append"> 
                        <a class="btn btn-auto btn-sm btn-light input-btn marginB-10 abrirModalModulos" id="abrirModalModulos"><i class="fas fa-search fa-lg"></i></a>
                    </div>
                    @if ($errors->has('inputModulo'))
                        <div class="alert alert-danger">
                            {{ $errors->first('inputModulo') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-4" id="divNameModulo">
                <label class="field-label">Nombre módulo</label>
                <input type="text" class="input-bordered marginB-10" name="inputNombreModulo" readonly value="{{$cxc->getModulo != null ? $cxc->getModulo->descripcion : old('inputNombreModulo')}}" id="inputNombreModulo">
                @if ($errors->has('inputNombreModulo'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputNombreModulo') }}
                    </div>
                @endif
            </div>


                <div id="divAnticipo" class="col-lg-4" style="display: none;">
                    <label for="inputAnticipo" class="field-label">Anticipo  <span style="color: red">*</span></label>
                    <div class="input-group">
    
                    <input readonly type="text" class="form-control input-bordered input-btn marginB-10" name="inputAnticipo" id="inputAnticipo"
                    value="{{  old('inputAnticipo') ?? $cxc->anticipo }}">
                    <input type="hidden" name="inputIdAnticipo" id="inputIdAnticipo" value="{{  old('inputIdAnticipo') ?? $cxc->idAnticipo }}">
                    <input type="hidden" name="inputMonedaAnticipo" id="inputMonedaAnticipo" value="{{  old('inputMonedaAnticipo') ?? $cxc->monedaAnticipo }}">
                    <input type="hidden" name="inputAnticipoImporte" id="inputAnticipoImporte"  value="{{  old('inputAnticipoImporte') ?? $cxc->anticipoImporte }}">
                    <div class="input-group-append">
                        <a class="btn btn-auto btn-sm btn-light input-btn" id="AbrirModalAnticipos"><i class="fas fa-search fa-lg"></i></a>
                    </div>
    
                    </div>
                    @if ($errors->has('inputAnticipo'))
                        <div class="alert alert-danger">
                            {{ $errors->first('inputAnticipo') }}
                        </div>
                    @endif
                </div>

            <div class="col-lg-12"></div>

            <div class="col-lg-4">
                <label for="inputObservaciones" class="field-label">Observaciones</label>
                    <input type="text" class="input-bordered marginB-10" name="inputObservaciones" id="inputObservaciones" value="{{old('inputObservaciones') ?? $cxc->observaciones}}">
                @if ($errors->has('inputObservaciones'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputObservaciones') }}
                    </div>
                @endif
            </div>

            <div class="col-lg-3" style="visibility: hidden " id="divSaldoMovimiento">
                <label for="inputSaldo" class="field-label">Saldo movimiento </label>
                <input readonly type="text" class="input-bordered currency marginB-10" name="inputSaldo" id="inputSaldo" value="{{ old('inputSaldo') ?? $cxc->saldo}}">
                @if ($errors->has('inputSaldo'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputSaldo') }}
                    </div>
                @endif
            </div>
            <div class="col-lg-3 ">
                <label for="inputSaldoCliente" class="field-label">Saldo cliente </label>
                <input readonly type="text" class="input-bordered currency marginB-10" name="inputSaldoCliente" id="inputSaldoCliente" value="{{ old('inputSaldoCliente') ?? $cxc->saldoCliente}}">
                @if ($errors->has('inputSaldoCliente'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputSaldoCliente') }}
                    </div>
                @endif
            </div>
           
        </div>

        <div class="row form-group" id="divTabla" style="display: none;">
            <input type="hidden" name="tablaMovimeintosData" id="tablaMovimeintosData">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <div class="datatable-container">
                        <table class="table table-bordered table-striped text-center" >
                            <thead>
                                <tr>
                                    <th class="text-center">Aplica</th>
                                    <th class="text-center">Aplica Consecutivo</th>
                                    <th class="text-center">Importe</th>
                                    <th class="text-center">Diferencia</th>
                                    <th class="text-center">%</th>
                                    <th class="text-center">&nbsp;</th>
                                    <th style="display:none">saldoMovimiento</th>
                                </tr>
                            </thead>
                            @php
                            $contador = 0;
                            @endphp
                            <tbody id="tbodyMovimientos">
                                @if(count($cxc->getDetalle) > 0)
                                <tr id="controlMovimientos" style="display:none">
                                    <td>
                                        <select name="selectTableAplicacion" id="selectTableAplicacion">
                                            <option value="Factura">Factura</option>
                                        </select>
                                    </td>
                                    <td style="display: flex">
                                        <div contenteditable="false" style="width: 80%" id="tdAplicaConsecutivo"></div>
                                        <div>
                                            <a href="" class="btn btn-sm btn-auto btn-info"  data-toggle="modal" data-target="#modalMov"><i class="fas fa-search fa-lg"></i></a>
                                        </div>
                                    </td>
                                    <td contenteditable="true" class="currency" id="tdImporte"></td>
                                    <td id="tdDiferencia"></td>
                                    <td id="tdPorcentaje"></td>
                                    <td>
                                        <i class="fa fa-trash  btn-delete-articulo" aria-hidden="true" style="color: red; font-size: 25px; cursor: pointer;"></i>
                                    </td>
                                    <td style="display: none" id="tdSaldo"></td>
                                    <td style="display: none" id="tdId"></td> 
                                    <td style="display: none" id="tdReferencia"></td> 
                                </tr>
                                @foreach($cxc->getDetalle as $key => $detalle)
                                <tr id="controlMovimientos-{{$key}}">
                                    <td>
                                        <select name="selectTableAplicacion" id="selectTableAplicacion">
                                            <option value="Factura">Factura</option>
                                        </select>
                                    </td>
                                    <td style="display: flex">
                                        <div contenteditable="false" style="width: 80%" id="tdAplicaConsecutivo">{{$detalle->aplicaConsecutivo}}</div>
                                        <div>
                                            <a href="" class="btn btn-sm btn-auto btn-info modalAplica"  data-toggle="modal" data-target="#modalMov"><i class="fas fa-search fa-lg"></i></a>
                                        </div>
                                    </td>
                                    <td contenteditable="true" class="currency" id="tdImporte" oninput="calculoDiferencia(this)">{{$detalle->importe}}</td>
                                    <td id="tdDiferencia" class="currency">{{$detalle->diferencia == null ? 0.0 : $detalle->diferencia}}</td>
                                    <td id="tdPorcentaje" class="percentage">{{$detalle->porcentaje == null ? 0.0 : $detalle->porcentaje}}</td>
                                    <td>
                                        <i onclick="eliminarRenglon(this)" class="fa fa-trash  btn-delete-articulo" aria-hidden="true" style="color: red; font-size: 25px; cursor: pointer;"></i>
                                    </td>
                                    <td style="display: none" id="tdSaldo">{{$detalle->importe}}</td>
                                    <td style="display: none" id="tdId">{{$detalle->idRenglon}}</td>
                                    <td style="display: none" id="tdReferencia">{{$detalle->referencia}}</td> 
                                </tr>
                                @php
                                $contador++;
                                @endphp

                                @endforeach
                                @else
                                <tr id="controlMovimientos">
                                    <td>
                                        <select name="selectTableAplicacion" id="selectTableAplicacion">
                                            <option value="Factura">Factura</option>
                                        </select>
                                    </td>
                                    <td style="display: flex">
                                        <div contenteditable="false" style="width: 80%" id="tdAplicaConsecutivo"></div>
                                        <div>
                                            <a href="" class="btn btn-sm btn-auto btn-info"  data-toggle="modal" data-target="#modalMov"><i class="fas fa-search fa-lg"></i></a>
                                        </div>
                                    </td>
                                    <td contenteditable="true" class="currency" id="tdImporte"></td>
                                    <td id="tdDiferencia"></td>
                                    <td id="tdPorcentaje"></td>
                                    <td>
                                        <i  class="fa fa-trash  btn-delete-articulo" aria-hidden="true" style="color: red; font-size: 25px; cursor: pointer;"></i>
                                    </td>
                                    <td style="display: none" id="tdSaldo"></td>
                                    <td style="display: none" id="tdId"></td>
                                    <td style="display: none" id="tdReferencia"></td> 
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if(count($cxc->getDetalle) > 0)
        @foreach($cxc->getDetalle as $key => $detalle)

        <input type="hidden" name="inputAplicaConsecutivo[]" id="inputAplicaConsecutivo-{{$key}}" value="{{$detalle->aplicaConsecutivo}}">
        @endforeach
        @endif
        <input type="hidden" name="contadorMovimientos" id="contadorMovimientos" value="{{ $contador }}">
        <input type="hidden" name="inputMovimientos" id="inputMovimientos">
        <input type="hidden" name="inputMovimientosDelete" id="inputMovimientosDelete">

        <div class="row form-group" id="divAuxiliar">
            <div class="col-lg-12">
                <div class="input-group col-lg-3" style="float: right; padding: 0;">
                    <div class="input-group-append">
                        <a class="btn-auto btn-sm input-btn marginB-10" style="line-height: 35px; font-size: 15px;">Total</a>
                    </div>
                    <input readonly type="text" class="form-control input-bordered input-btn marginB-10 currency text-right" name="inputImporteCalc" id="inputImporteCalc"
                    value="{{old('inputImporteCalc') ?? $cxc->total}}">
                    <div class="input-group-append">
                        <a onclick="igualarImporte()" class="btn btn-auto btn-sm btn-info input-btn marginB-10" id="EqualImporte">Importe</a>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="row form-group">
            <div class="col-lg-12">
                @php
                    // dd($cxc['estatus']);
                @endphp
                @if (isset($cxc) ? ($cxc['estatus'] === 'SIN AFECTAR' || $cxc['estatus'] === null) : 'SIN AFECTAR')
                    <span class="badge badge-outline badge-light" name="inputEstatus">SIN AFECTAR</span>
                @elseif($cxc['estatus'] === 'PENDIENTE')
                    <span class="badge badge-outline badge-warning" name="inputEstatus">PENDIENTE</span>
                @elseif($cxc['estatus'] === 'CONCLUIDO')
                    <span class="badge badge-outline badge-success" name="inputEstatus">CONCLUIDO</span>
                @elseif($cxc['estatus'] === 'CANCELADO')
                    <span class="badge badge-outline badge-danger" name="inputEstatus">CANCELADO</span>
                @endif
                <input type="hidden" name="inputEstatus" value="{{ $cxc['estatus'] == null ? 'SIN AFECTAR' : $cxc['estatus'] }}" id="inputEstatus">
                <input type="hidden" name="inputCobro" id="inputCobro">
            </div>
        </div>
    </div>
</div>