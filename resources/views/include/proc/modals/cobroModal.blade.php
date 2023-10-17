{{-- cobro Factura CXC--}}
<div class="modal fade" id="modalCobro" >
    <div class="modal-dialog ">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="modal-body p-md-4 p-lg-5">
                <h3 class="title title-md">Módulo - CxC</h3>
                <div class="row mt-2">
                    <div class="col-lg-12">
                        <div class="form-group container-respuesta">
                            <input type="radio" id="generarCobro" name="accionCobro" value="Generar Cobro" checked="">
                            <label>Generar Cobro</label>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="#" onclick="generarCobro()" class="btn btn-dark">Generar</a>
                </div>
            </div>
            
        </div>
    </div>
</div>

<div class="modal fade" id="modalAplicacion" >
    <div class="modal-dialog ">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="modal-body p-md-4 p-lg-5">
                <h3 class="title title-md">Módulo - CxC</h3>
                <div class="row mt-2">
                    <div class="col-lg-12">
                        <div class="form-group container-respuesta">
                            <input type="radio" id="generarAplicacion" name="accionAplicacion" value="Generar Aplicación" checked="">
                            <label>Generar Aplicación</label>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="#" onclick="generarAplicacion()" class="btn btn-dark">Generar</a>
                </div>
            </div>
            
        </div>
    </div>
</div>