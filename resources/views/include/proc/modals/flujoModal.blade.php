    {{-- modal-flujo --}}
    <div class="modal fade" id="modalFlujo" >
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
                <div class="modal-body p-md-4 p-lg-5">
                    <h3 class="title title-md">Flujo del Movimiento</h3>
                    <div class="row mt-2">
                        <div class="col-lg-12">
                            <input type="hidden" id="inputFlujoPrincipal">
                            <input type="hidden" value="" id="inputMovimientoPosterior">
                            <div class="table-responsive">
                                <div class="datatable-container">
                                    <table class=" table dataTable table-bordered nowrap" id="tablaFlujo">
                                        <thead>
                                            <tr>
                                                <th class="table-head">Origen</th>
                                                <th class="table-head">Módulo</th>
                                                <th class="table-head">----</th>
                                                <th class="table-head">Movimientos Generados</th>
                                                <th class="table-head">Módulo</th>
                                                <th style="display: none">destinoId</th>
                                                <th style="display: none">origenId</th>
                                                <th style="display: none">idEmpresa</th>
                                                <th style="display: none">idSucursal</th>
                                                <th style="display: none">cancelado</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bodyFlujo">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" id="modalFooterFlujo">
                        <div class="col-lg-12 contenedorBtnFlujo">
                            <button class="btn btn-warning" id="btnFlujoPrincipal">
                                <span>Flujo principal <span class="fa fa-sync"></span></span>
                            </button>
                            <button class="btn btn-light" id="btnRetrocederFlujo">
                                <span><i class="fas fa-arrow-left"></i> Anterior</span>
                            </button>
                            <button class="btn btn-primary" id="btnAvanzarFlujo">
                                <span>Siguiente <i class="fas fa-arrow-right"></i></span>
                            </button>
                            <button type="button" class="btn btn-danger " data-dismiss="modal" id="agregarArticulos">
                                <span>cerrar  <span class="fa fa-times"></span></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>