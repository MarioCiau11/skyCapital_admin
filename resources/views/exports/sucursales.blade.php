<table>
    <thead>
    <tr>
        <th>Clave</th>
        <th>Nombre</th>
        <th>Estatus</th>
        <th>Dirección</th>
        <th>Colonia</th>
        <th>CP</th>
        <th>Ciudad</th>
        <th>Estado</th>
        <th>Pais</th>
        <th>Fecha Creación</th>
        <th>Ultima Actualización</th>
        <th>Fecha Baja</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sucursales as $sucursal)
        <tr>
            <td>{{ $sucursal->clave }}</td>
            <td>{{ $sucursal->nombre}}</td>
            <td>{{ $sucursal->estatus == 1 ? 'Alta' : 'Baja' }}</td>
            <td>{{ $sucursal->direccion}}</td>
            <td>{{ $sucursal->colonia}}</td>
            <td>{{ $sucursal->codigoPostal}}</td>
            <td>{{ $sucursal->ciudad}}</td>
            <td>{{ $sucursal->estado}}</td>
            <td>{{ $sucursal->pais}}</td>
            <td>{{ $sucursal->fechaAlta}}</td>
            <td>{{ $sucursal->fechaCambio}}</td>
            <td>{{ $sucursal->fechaBaja}}</td>
        </tr>
    @endforeach
    </tbody>
</table>