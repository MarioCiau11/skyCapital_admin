{{-- modal clientes --}}
<div class="modal fade" id="modlaTableProyectos">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="modal-body p-md-4 p-lg-5">
                <h3 class="title title-md">Catalogo de Proyectos</h3>
                <div class="row mt-2">
                    <div class="col-lg-12">
                        <div class=" table-responsive">
                            <div class="datatable-container">
                                <table class="table table-striped table-bordered widthAll datatable" id="tablaProyectosSelect">
                                    <thead>
                                        <tr>
                                            <th>Clave</th>
                                            <th>Nombre del Proyecto</th>
                                            <th style="display: none"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($proyectos as $proyecto)
                                            <tr>
                                                <td>{{ $proyecto->clave }}</td>
                                                <td>{{ $proyecto->nombre }}</td>
                                                <td style="display: none">{{$proyecto->idProyecto}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal" id="btnAddProyecto">Agregar</button>
                </div>
            </div>
        </div>
    </div>
</div>