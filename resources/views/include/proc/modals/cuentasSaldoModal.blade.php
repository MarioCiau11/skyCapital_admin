{{-- modal clientes --}}
<div class="modal fade" id="modalTableCuentasSaldo">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="modal-body p-md-4 p-lg-5">
                <h3 class="title title-md">Cuentas Generales</h3>
                <div class="row mt-2">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="negrita">Cuentas: </label>
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    @php
                                        // dd($monedas[1]);
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
                                                            <table class="table table-striped table-bordered widthAll datatable tableCuentas" name="tableMonedas">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="table-head">Cuenta</th>
                                                                        <th class="table-head">Tipo</th>
                                                                        <th class="table-head">Saldo Inicial</th>
                                                                        <th class="table-head">Saldo</th>
                                                                        <th class="table-head">Empresa</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @php
                                                                // dd($moneda);
                                                                    @endphp

                                                                    @if (count($cuentas) != 0 || $cuentas != [])
                                                                        @foreach ($cuentas as $cuenta)
                                                                       
                                                                        @if ($cuenta->getMonedas->clave == $moneda)
                                                                                @php
                                                                                    // dd($cuenta->moneda);
                                                                                @endphp
                                                                                <tr>
                                                                                    <td>{{ $cuenta->clave }}</td>
                                                                                    <td>{{ $cuenta->tipoCuenta }}</td>
                                                                                    <td>${{ number_format($cuenta->saldoInicial, 2) }}</td>
                                                                                    <td>${{ number_format($cuenta->saldo, 2) }}</td>
                                                                                    <td>{{ $cuenta->getEmpresas->nombreEmpresa }}</td>
                                                                                </tr>

                                                                                
                                                                            @endif
                                                                        @endforeach
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
                <div class="modal-footer">
                 
                </div>
            </div>
        </div>
    </div>
</div>