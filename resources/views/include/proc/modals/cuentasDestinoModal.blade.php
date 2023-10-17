{{-- modal clientes --}}
<div class="modal fade" id="modalTableCuentasDestino">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="modal-body p-md-4 p-lg-5">
                <h3 class="title title-md">Cuentas Bancarias</h3>
                <div class="row mt-2">
                    <div class="col-lg-12">
                        <div class=" table-responsive">
                            <div class="datatable-container">
                                <table class="table table-striped table-bordered widthAll datatable" id="tablaCuentasDestinoSelect">
                                    <thead>
                                        <tr>
                                            <th>Clave</th>
                                            {{-- <th>institución Financiera</th> --}}
                                            <th>Tipo de cuenta</th>
                                            <th>Nombre</th>
                                            <th>Moneda</th>
                                            <th>idCuenta</th>
                                            <th>idMoneda</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // dd($cuentas);
                                        @endphp
                                        @foreach ($cuentas as $cuenta)
                                            <tr>
                                                <td>{{ $cuenta->clave }}</td>
                                                {{-- <td>{{ $cuenta->idInstitucionf }}</td> --}}
                                                <td> {{ $cuenta->tipoCuenta }}</td>
                                                <td> {{ isset($cuenta->getInstituciones->clave) ? $cuenta->getInstituciones->clave.'-'.$cuenta->noCuenta : $cuenta->idInstitucionf.'-'.$cuenta->noCuenta }}</td>
                                                <td> {{ isset($cuenta->getMonedas->clave)? $cuenta->getMonedas->clave :$cuenta->idMoneda}}</td>
                                                <td> {{$cuenta->idCuentasDinero}} </td>
                                                <td> {{$cuenta->idMoneda}} </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal" id="btnAddCuentaDestino">Agregar</button>
                </div>
            </div>
        </div>
    </div>
</div>