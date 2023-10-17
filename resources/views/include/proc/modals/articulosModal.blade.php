{{-- modal articulos--}}
<div class="modal fade" id="modalArticulos" >
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="modal-body p-md-4 p-lg-5">
                <h3 class="title title-md">Catálogo de artículos</h3>
                <div class="row mt-2">
                    <div class="col-lg-12">
                        <div class=" table-responsive">
                            <div class="datatable-container">
                                <table  class="table table-striped table-bordered widthAll datatable" id="tableArticulosModal">
                                    <thead>
                                        <tr>
                                            <th class="table-head">Clave</th>
                                            <th class="table-head">Nombre</th>
                                            <th class="table-head">Tipo</th>
                                            <th class="table-head">Lista de Precio</th>
                                            <th style="display: none">Unidad</th>
                                            <th style="display: none">Iva</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    @foreach ($articulos as $articulo)
                                        <tr>
                                            <td>{{ $articulo->clave }}</td>
                                            <td>{{ $articulo->descripcion }}</td>
                                            <td>{{ $articulo->tipo }}</td>
                                            <td>${{ number_format($articulo->precio, 2) }}</td>
                                            <td style="display: none">{{ $articulo->unidad }}</td>
                                            <td style="display: none">{{ number_format($articulo->IVA, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal" id="agregarArticulos">Agregar</button>
                </div>
            </div>
        </div>
    </div>
</div>