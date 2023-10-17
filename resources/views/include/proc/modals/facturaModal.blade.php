{{-- modal Factura--}}
<div class="modal fade" id="modalFactura" >
    <div class="modal-dialog ">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="modal-body p-md-4 p-lg-5">
                <h3 class="title title-md">Módulo - Ventas</h3>
                <div class="row mt-2">
                    <div class="col-lg-12">
                        <div class="form-group container-respuesta">
                            <input type="radio" id="generarFactura" name="accionVenta" value="Generar Factura" checked="">
                            <label>Generar Factura</label>

                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="#" onclick="generarFactura()" class="btn btn-dark">Generar</a>
                </div>
            </div>
            
        </div>
    </div>
</div>