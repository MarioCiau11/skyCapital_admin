<table>
    <thead>
        <tr>
            <th>Clave</th>
            <th>Tipo</th>
            <th>Estatus</th>
            <th>Descripción</th>
            <th>Unida Venta</th>
            <th>Categoria</th>
            <th>Grupo</th>
            <th>IVA</th>
            <th>Usuario que lo Registró</th>
            <th>Lista de Precio</th>
            <th>Fecha de Alta</th>
            <th>Última Actualización</th>
            <th>Fecha de Baja</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($Articulos as $articulo)
        <tr>
            <td >{{$articulo->clave}}</td>
            <td >{{$articulo->tipo}}</td>
            <td >{{$articulo->estatus == 1 ? 'Alta' : 'Baja'}}</td>
            <td >{{$articulo->descripcion}} </td>
            <td >{{$articulo->unidadesRelation->unidad}} </td>
            <td >{{$articulo->getCategoria != null ? $articulo->getCategoria->nombre : ''}} </td>
            <td >{{$articulo->getGrupo != null ? $articulo->getGrupo->nombre : ''}} </td>
            <td >{{number_format($articulo->IVA,2)}} %</td>
            <td >{{$articulo->user_id != null ?$articulo->getUser->username : null}}</td>
            <td >{{$articulo->precio}}</td>
            <td >{{$articulo->fechaAlta}}</td>
            <td >{{$articulo->fechaCambio}}</td>
            <td >{{$articulo->fechaBaja}}</td>
        </tr>
        @endforeach
    </tbody>
</table>