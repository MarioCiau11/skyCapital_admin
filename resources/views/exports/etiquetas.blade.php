<table>
    <thead>
    <tr>
        <th>Clave</th>
        <th>Nombre</th>
        <th>Estatus</th>
        <th>Fecha Creación</th>
        <th>Ultima Actualización</th>
        <th>Fecha Baja</th>
    </tr>
    </thead>
    <tbody>
    @foreach($etiquetas as $etiqueta)
        <tr>
            <td>{{ $etiqueta->clave }}</td>
            <td>{{ $etiqueta->nombre}}</td>
            <td>{{ $etiqueta->estatus == 1 ? 'Alta' : 'Baja' }}</td>
            <td>{{ $etiqueta->fechaAlta}}</td>
            <td>{{ $etiqueta->fechaCambio}}</td>
            <td>{{ $etiqueta->fechaBaja}}</td>
        </tr>
    @endforeach
    </tbody>
</table>