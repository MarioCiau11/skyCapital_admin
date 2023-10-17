{{-- modal articulos--}}
<div class="modal fade" id="modalAnticiposMov" >
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="modal-body p-md-4 p-lg-5">
                <h3 class="title title-md">Lista de Anticipos</h3>
                <div class="row mt-2">
                    <div class="col-lg-12">
                        <div class=" table-responsive">
                            <div class="datatable-container">
                                <table  class="table table-striped table-bordered widthAll datatable" id="tableAnticiposModal">
                                    <thead>
                                        <tr>
                                            <th>Folio</th>
                                            <th>Movimiento</th>
                                            <th>Estatus</th>
                                            <th>Saldo</th>
                                            <th>Cuenta</th>
                                            <th>Moneda</th>
                                            <th>Referencia</th>
                                            <th>idMoneda</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyAnticiposModal">
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="currency"></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>

                                    <tfoot id="tfoodAnticiposModal" style="display: none">
                                        <tr>
                                            <th>Total: </th>
                                            <th></th>
                                            <th></th>
                                            <th class="currency" id="sumaAnticipos"></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal" id="agregarAnticipo">Agregar</button>
                </div>
            </div>
        </div>
    </div>
</div>