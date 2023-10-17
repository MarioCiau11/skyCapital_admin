    {{-- modal-flujo --}}
    
    
    @php
        // dd($venta->getCobro);
    @endphp

    <div class="modal fade" id="cobranzaModal" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
                <div class="modal-body p-md-4 p-lg-5">
                    <h3 class="title title-md"> COBRO-FACTURA</h3>
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="hidden" name="inputIdCobro" value="{{ $venta->getCobro != null ? $venta->getCobro->idCobro : null}}" id="inputIdCobro">
                            <label class="field-label">Importe: </label>
                            <input type="text" class="input-bordered currency" id="inputImporteCobro" value="{{$venta->getCobro == null ? $venta->saldo : $venta->getCobro->importe}}" onkeyup="calculoCobro()">
                        </div>
                        <div class="col-lg-6">
                            <label class="field-label">Forma de Cobro: </label>
                            {!! Form::select('selectFormaCobro', $formasPago, $venta->getCobro != null ? $venta->getCobro->formaCobro : null, ['placeholder' => 'Selecciona uno...', 'id'=>'selectFormaCobro']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <label class="field-label">Cuenta de Dinero:</label>
                            {!! Form::select('selectCuentaDinero', $cuentas, $venta->getCobro != null ? $venta->getCobro->cuentaDinero : null, ['placeholder' => 'Selecciona uno...', 'id'=>'selectCuentaDinero']) !!}
                            <input type="hidden" name="inputTipoBancoCobro" id="inputTipoBancoCobro">
                            <input type="hidden" name="inputTipoBancoCambio" id="inputTipoBancoCambio">
                        </div>
                        <div class="col-lg-6">
                            <label class="field-label">Forma de Cambio: </label>
                            {!! Form::select('selectFormaCambio', $formasPago, $venta->getCobro != null ? $venta->getCobro->formaCambio : null, ['placeholder' => 'Selecciona uno...', 'id'=>'selectFormaCambio']) !!}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <label class="field-label">Información Adicional</label>
                            <input type="text" name="inputInformacionAdicional" class="input-bordered" readonly id="inputInformacionAdicional" value="{{ $venta->getCobro != null ? $venta->getCobro->informacionAdicional : ''}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <label class="field-label">Total Factura: </label>
                            <input type="text" class="input-bordered currency" name="inputTotalFacturaCobro" readonly id="inputTotalFacturaCobro" value="{{$venta->getCobro == null ? $venta->total : $venta->getCobro->totalFactura}}">
                        </div>
                        <div class="col-lg-6">
                            <label class="field-label">Total Cobrado: </label>
                            <input type="text" class="input-bordered currency" name="inputTotalCobradoCobro" readonly id="inputTotalCobradoCobro" value="{{$venta->getCobro == null ? $venta->saldo : $venta->getCobro->totalCobrado}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <label class="field-label">Cambio: </label>
                            <input type="text" class="input-bordered currency" name="inputCambioCobro" readonly id="inputCambioCobro" value="{{ $venta->getCobro != null ? $venta->getCobro->cambio : 0}}">
                        </div>
                        <div class="col-lg-6">
                            <label class="field-label">Saldo: </label>
                            <input type="text" class="input-bordered currency" name="inputSaldoCobro" readonly id="inputSaldoCobro" value="{{ $venta->getCobro != null ? $venta->getCobro->saldo : 0}}">
                        </div>
                    </div>

                    <div class="modal-footer" id="modalFooterCobranza" style="justify-content: center; ">
                        <button class="btn btn-success" id="btnGuardarCobro" onclick="submitCobro()">Guardar</button>
                        <button class="btn btn-primary" id="btnAfectarCobro" onclick="afectarCobro()">Afectar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">cerrar</button>
                    </div>
                </div>
            </div>


        </div>
    </div>