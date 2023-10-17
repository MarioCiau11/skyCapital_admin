{{-- modal inf.modulo--}}
@php
    $cuenta = '';
    $clabe = '';
    if (isset($venta)) {
        $acc = mb_str_split($venta->getModulo != null ? $venta->getModulo->cuenta : '', 4);
        $clb = mb_str_split($venta->getModulo != null ? $venta->getModulo->clabe : '', 4);
    }
    elseif (isset($cxc)) {
        $acc = mb_str_split($cxc->getModulo != null ? $cxc->getModulo->cuenta : '', 4);
        $clb = mb_str_split($cxc->getModulo != null ? $cxc->getModulo->clabe : '', 4);
    }
    
    foreach($acc as $value){
        $cuenta .= $value . ' ';
    }
    foreach($clb as $value){
        $clabe .= $value . ' ';
    }
    // dd($cuenta, $clabe);
@endphp
<div class="modal fade" id="modalModulo" >
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="modal-body p-md-4 p-lg-5">
                <h3 class="title title-md">Información del módulo</h3>
                <ul class="nav nav-tabs nav-line" style="margin: 0px !important">
                    <li><strong>Datos generales</strong></li>
                </ul>
                <div class="col-lg-12 col-sm-12">
                    <div class="row col-lg-12 repair">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="negrita"># Módulo: </label>
                                <input type="text" readonly="" class="form-control input-sm" value="<?php
                                if (isset($venta)) echo $venta->getModulo != null ? strtoupper($venta->getModulo->clave) : '';
                                elseif(isset($cxc)) echo $cxc->getModulo != null ? strtoupper($cxc->getModulo->clave) : ''; ?>" 
                                name="infClave" id="infClave">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="negrita">Tipo: </label>
                                <input type="text" readonly="" class="form-control input-sm" value="<?php
                                if (isset($venta)) echo $venta->getModulo != null ? strtoupper($venta->getModulo->tipo) : '';
                                elseif(isset($cxc)) echo $cxc->getModulo != null ? strtoupper($cxc->getModulo->tipo) : ''; ?>"
                                name="infTipo" id="infTipo">
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-group">
                                <label class="negrita">Descripción: </label>
                                <input type="text" readonly="" class="form-control input-sm" value="<?php
                                if (isset($venta)) echo $venta->getModulo != null ? strtoupper($venta->getModulo->descripcion) : '';
                                elseif(isset($cxc)) echo $cxc->getModulo != null ? strtoupper($cxc->getModulo->descripcion) : ''; ?>"
                                name="infDesc" id="infDesc">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="negrita">MT2: </label>
                                <input type="text" readonly="" class="form-control input-sm" value="<?php
                                if (isset($venta)) echo $venta->getModulo != null ? strtoupper($venta->getModulo->mt2) : '';
                                elseif(isset($cxc)) echo $cxc->getModulo != null ? strtoupper($cxc->getModulo->mt2) : ''; ?>"
                                name="infMt2" id="infMt2">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="negrita">Nivel/Piso: </label>
                                <input type="text" readonly="" class="form-control input-sm" value="<?php
                                if (isset($venta)) echo $venta->getModulo != null ? strtoupper($venta->getModulo->nivelPiso) : '';
                                elseif(isset($cxc)) echo $cxc->getModulo != null ? strtoupper($cxc->getModulo->nivelPiso) : ''; ?>"
                                name="infNivel" id="infNivel">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="negrita"># Cajones: </label>
                                <input type="text" readonly="" class="form-control input-sm" value="<?php
                                if (isset($venta)) echo $venta->getModulo != null ? strtoupper($venta->getModulo->numCajones) : '';
                                elseif(isset($cxc)) echo $cxc->getModulo != null ? strtoupper($cxc->getModulo->numCajones) : ''; ?>"
                                name="infCaj" id="infCaj">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="negrita">Valor de operación: </label>
                                <input type="text" readonly="" class="form-control input-sm" value="<?php
                                if (isset($venta)) echo '$ ', $venta->getModulo != null ? number_format($venta->getModulo->valorOperacion, 2) : '';
                                elseif(isset($cxc)) echo '$ ', $cxc->getModulo != null ? number_format($cxc->getModulo->valorOperacion, 2) : ''; ?>"
                                name="infVal" id="infVal">
                            </div>
                        </div>
                    </div>
                </div>
                <ul class="nav nav-tabs nav-line" style="margin: 0px !important">
                    <li><strong>Datos bancarios</strong></li>
                </ul>
                <div class="col-lg-12 col-sm-12">
                    <div class="row col-lg-12 repair">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="negrita">Banco: </label>
                                <input type="text" readonly="" class="form-control input-sm" value="<?php
                                if (isset($venta)) 
                                    if($venta->getModulo != null)
                                        if($venta->getModulo->getBank == null)
                                            echo '';
                                        else
                                            echo strtoupper($venta->getModulo->getBank->nombre);
                                if (isset($cxc))
                                    if($cxc->getModulo != null)
                                        if($cxc->getModulo->getBank == null)
                                            echo '';
                                        else
                                            echo strtoupper($cxc->getModulo->getBank->nombre);
                                ?>"
                                name="infBanco" id="infBanco">
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group">
                                <label class="negrita">Número de cuenta: </label>
                                <input type="text" readonly="" class="form-control input-sm" value="<?php
                                if (isset($venta)) echo $cuenta;
                                elseif(isset($cxc)) echo $cuenta; ?>"
                                name="infCuenta" id="infCuenta">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="negrita">Clabe interbancaria: </label>
                                <input type="text" readonly="" class="form-control input-sm" value="<?php
                                if (isset($venta)) echo $clabe;
                                elseif(isset($cxc)) echo $clabe; ?>"
                                name="infClabe" id="infClabe">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>