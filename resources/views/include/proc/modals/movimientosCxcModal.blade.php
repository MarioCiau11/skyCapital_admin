{{-- modal articulos--}}
<div class="modal fade" id="modalMov" >
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="modal-body p-md-4 p-lg-5">
                <h3 class="title title-md">Lista de Movimientos</h3>
                <div class="row mt-2">
                    <div class="col-lg-12">
                        <div class=" table-responsive">
                            <div class="datatable-container">
                                <table  class="table table-striped table-bordered widthAll datatable" id="tableMovimientosModal">
                                    <thead>
                                        <tr>
                                            <th>Movimiento</th>
                                            <th>Consecutivo</th>
                                            <th>Fecha de Vencimiento</th>
                                            <th>Importe Total</th>
                                            <th>Saldo</th>
                                            <th>Moneda</th>
                                            <th>Sucursal</th>
                                            <th>Referencia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="currency"></td>
                                            <td class="currency"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal" id="agregarMovimiento">Agregar</button>
                </div>
            </div>
        </div>
    </div>
</div>