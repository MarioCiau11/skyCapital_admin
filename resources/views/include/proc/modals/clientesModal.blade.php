{{-- modal clientes --}}
<div class="modal fade" id="modalTableClientes">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="modal-body p-md-4 p-lg-5">
                <h3 class="title title-md">Catálogo de clientes</h3>
                <div class="row mt-2">
                    <div class="col-lg-12">
                        <div class=" table-responsive">
                            <div class="datatable-container">
                                    <table  class="table table-striped table-bordered widthAll datatable" id="tablaClientesSelect">
                                    <thead>
                                        <tr>
                                            <th>Clave</th>
                                            <th>Nombre</th>
                                            <th>RFC</th>
                                            <th>id</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($clientes as $cliente)
                                            <tr>
                                                <td>{{ $cliente->idCliente }}</td>
                                                <td>{{ $cliente->razonSocial }}</td>
                                                <td>{{ $cliente->RFC }}</td>
                                                <td>{{$cliente->idCliente}}</td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal" id="btnAddClient">Agregar</button>
                </div>
            </div>
        </div>
    </div>
</div>