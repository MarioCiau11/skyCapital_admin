{{-- modal inf.cliente --}}
<div class="modal fade" id="modalCliente" >
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="modal-body p-md-4 p-lg-5">
                <h3 class="title title-md">Información del cliente</h3>
                <ul class="nav nav-tabs nav-line" style="margin: 0px !important">
                    <li><strong>Datos generales</strong></li>
                </ul>
                <div class="row col-lg-12 col-sm-12">
                    <div class="col-lg-5 repair">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="negrita">Nombre/Razón social: </label>
                                <input type="text" readonly="" class="form-control input-sm" value="<?php
                                if (isset($venta)) echo $venta->getCliente != null ? strtoupper($venta->getCliente->razonSocial) : '';
                                elseif(isset($cxc)) echo $cxc->getCliente != null ? strtoupper($cxc->getCliente->razonSocial) : ''; ?>"
                                name="infRazonSocial" id="infRazonSocial">
                            </div>
                        </div>
                        <div class="row col-lg-12 file-item">
                            <div class="col-lg-12 file-item">
                                <div class="form-group">
                                    <label class="negrita">Dirección: </label>
                                    <input type="text" readonly="" class="form-control input-sm" value="<?php
                                    if (isset($venta)) echo
                                            $venta->getCliente != null ? strtoupper($venta->getCliente->direccion) : '', ' ',
                                            $venta->getCliente != null ? strtoupper($venta->getCliente->vialidades) : '', ' NO.EXT. ',
                                            $venta->getCliente != null ? strtoupper($venta->getCliente->noExterior) : '', ' COL. ',
                                            $venta->getCliente != null ? strtoupper($venta->getCliente->coloniaFracc) : '', ' ',
                                            $venta->getCliente != null ? strtoupper($venta->getCliente->localidadMunicipio) : '', '-',
                                            $venta->getCliente != null ? strtoupper($venta->getCliente->codigoPostal) : '';
                                    elseif(isset($cxc)) echo
                                            $cxc->getCliente != null ? strtoupper($cxc->getCliente->direccion) : '', ' ',
                                            $cxc->getCliente != null ? strtoupper($cxc->getCliente->vialidades) : '', ' NO.EXT. ',
                                            $cxc->getCliente != null ? strtoupper($cxc->getCliente->noExterior) : '', ' COL. ',
                                            $cxc->getCliente != null ? strtoupper($cxc->getCliente->coloniaFracc) : '', ' ',
                                            $cxc->getCliente != null ? strtoupper($cxc->getCliente->localidadMunicipio) : '', '-',
                                            $cxc->getCliente != null ? strtoupper($cxc->getCliente->codigoPostal) : ''; ?>"
                                            name="infDireccion" id="infDireccion">
                                </div>
                            </div>
                        </div>
                        <div class="row col-lg-12 file-item">
                            <div class="col-lg-6 file-item">
                                <div class="form-group">
                                    <label class="negrita">Teléfono: </label>
                                    <input type="text" readonly="" class="form-control input-sm" value="<?php
                                    if (isset($venta)) echo $venta->getCliente != null ? strtoupper($venta->getCliente->telefonCelular) : '';
                                    elseif(isset($cxc)) echo $cxc->getCliente != null ? strtoupper($cxc->getCliente->telefonCelular) : ''; ?>"
                                    name="infTel" id="infTel">
                                </div>
                            </div>
                            <div class="col-lg-6 file-item">
                                <div class="form-group">
                                    <label class="negrita">RFC: </label>
                                    <input type="text" readonly="" class="form-control input-sm" value="<?php
                                    if (isset($venta)) echo $venta->getCliente != null ? strtoupper($venta->getCliente->RFC) : '';
                                    elseif(isset($cxc)) echo $cxc->getCliente != null ? strtoupper($cxc->getCliente->RFC) : ''; ?>"
                                    name="infRFC" id="infRFC">
                                </div>
                            </div>
                        </div>
                        <div class="row col-lg-12 file-item">
                            <div class="col-lg-6 file-item">
                                <div class="form-group">
                                    <label class="negrita">Categoría: </label>
                                    <input type="text" readonly="" class="form-control input-sm" value="<?php
                                    if (isset($venta)) 
                                        if($venta->getCliente != null)
                                            if($venta->getCliente->getCategoria == null)
                                                echo '';
                                            else
                                                echo strtoupper($venta->getCliente->getCategoria->nombre);  
                                    if(isset($cxc))
                                        if($cxc->getCliente != null)
                                            if($cxc->getCliente->getCategoria == null)
                                                echo '';
                                            else 
                                                echo strtoupper($cxc->getCliente->getCategoria->nombre);
                                    ?>"
                                    name="infCat" id="infCat">
                                </div>
                            </div>
                            <div class="col-lg-6 file-item">
                                <div class="form-group">
                                    <label class="negrita">Grupo: </label>
                                    <input type="text" readonly="" class="form-control input-sm" value="<?php
                                    if (isset($venta)) 
                                        if($venta->getCliente != null)
                                            if($venta->getCliente->getGrupo == null)
                                                echo '';
                                            else
                                                echo strtoupper($venta->getCliente->getGrupo->nombre);  
                                    if(isset($cxc))
                                        if($cxc->getCliente != null)
                                            if($cxc->getCliente->getGrupo == null)
                                                echo '';
                                            else 
                                                echo strtoupper($cxc->getCliente->getGrupo->nombre);
                                    ?>"
                                    name="infGrupo" id="infGrupo">
                                </div>
                            </div>
                        </div>
                        <div class="row col-lg-12 file-item">
                            <div class="col-lg-6 file-item">
                                <div class="form-group">
                                    <label class="negrita">Tipo de persona: </label>
                                    <input type="text" readonly="" class="form-control input-sm" value="<?php
                                    if (isset($venta)) echo $venta->getCliente != null ? $venta->getCliente->tipoPersona == '1' ? 'FÍSICA' : 'MORAL' : '';
                                    elseif(isset($cxc)) echo $cxc->getCliente != null ? $cxc->getCliente->tipoPersona == '1' ? 'FÍSICA' : 'MORAL' : ''; ?>"
                                    name="infTipo" id="infTipo">
                                </div>
                            </div>
                            <div class="col-lg-6 file-item">
                                <div class="form-group">
                                    <label class="negrita">Condición de pago: </label>
                                    <input type="text" readonly="" class="form-control input-sm" value="<?php
                                    if (isset($venta)) echo $venta->getCliente != null ? $venta->getCliente->getCondiciones == null ? '' : mb_strtoupper($venta->getCliente->getCondiciones->nombreCondicion) : '';
                                    elseif(isset($cxc)) echo $cxc->getCliente != null ? $cxc->getCliente->getCondiciones == null ? '' : mb_strtoupper($cxc->getCliente->getCondiciones->nombreCondicion) : ''; ?>"
                                    name="infCond" id="infCond">
                                </div>
                            </div>
                            @php
                                // dd($saldoGlobalCliente);
                            @endphp
                        </div>
                        @if (isset($saldoGlobalCliente[1]))
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="negrita">Saldo Global:</label>
                                <input type="text" readonly="" class="form-control input-sm currency" value="{{$saldoGlobalCliente[1]}}" name="infSaldoTotal" id="infSaldoTotal">
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="col-lg-7 repair">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="negrita">Movimientos: </label>
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        @php
                                            // dd($monedas);
                                        @endphp
                                        @foreach ($monedas as $key => $moneda)
                                                <a class="nav-item nav-link {{ $key == 1 ? 'active' : '' }}" 
                                                id="{{ $key == 1 ? 'nav-home-tab' : 'nav-profile-tab' }}" 
                                                data-toggle="tab" href="#{{ $moneda }}" role="tab" 
                                                aria-controls="{{ $key == 1 ? 'nav-home' : 'nav-profile' }}" 
                                                aria-selected="{{ $key == 1 ? 'true' : 'false' }}">{{mb_strtoupper($moneda)}}</a>
                                        @endforeach
                                    </div>
                                </nav>
                                <div class="tab-content" id="nav-tabContent">
                                    @foreach ($monedas as $key => $moneda)
                                            <div class="tab-pane fade {{ $key == 1 ? 'active show' : '' }}" id="{{$moneda}}" role="tabpanel" aria-labelledby="nav-profile-tab">
                                                <div class="row mt-2 table-mov">
                                                    <div class="col-lg-12">
                                                        <div class="table-responsive">
                                                            <div class="datatable-container">
                                                                {{-- {{$moneda}} --}}
                                                                <table class="table table-striped table-bordered widthAll datatable tableMov" id="TableMovimiento" name="tableMonedas">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="table-head">Mov+Folio</th>
                                                                            <th class="table-head">Referencia</th>
                                                                            <th class="table-head">Días</th>
                                                                            <th class="table-head">Saldo</th>
                                                                            <th class="table-head">Fecha</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @if (isset($saldoGlobalCliente[1]))
                                                                        @if (count($saldoGlobalCliente[0]) != 0 || $saldoGlobalCliente[0] != [])
                                                                            @foreach ($saldoGlobalCliente[0] as $movimientos)
                                                                           
                                                                                @if ($movimientos->getMoneda->clave == $moneda)
                                                                                    @php
                                                                                        // dd($movimientos->moneda);
                                                                                    @endphp
                                                                                    <tr>
                                                                                        <td>{{ $movimientos->movimiento.' '.$movimientos->folioMov }}</td>
                                                                                        <td>{{ $movimientos->referencia }}</td>
                                                                                        <td>{{ $movimientos->diasMoratorios }}</td>
                                                                                        <td>${{ number_format($movimientos->saldo, 2) }}</td>
                                                                                        <td>{{ \Carbon\Carbon::parse($movimientos->fechaEmision)->format('Y-m-d') }}</td>
                                                                                    </tr>
                                                                                @else
                                                                                    
                                                                                @endif
                                                                            @endforeach
                                                                        @endif
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                  
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>