<table>
    <thead>
    <tr>
        <th>Módulo</th>
        <th>Descripción</th>
        <th>Tipo</th>
        <th>Proyecto</th>
        <th>MT2</th>
        <th>Valor Operación</th>
        <th>Nivel/Piso</th>
        <th># Cajones estacionamiento</th>
        <th>Banco</th>
        <th>Cuenta</th>
        <th>Clabe</th>
        <th>Estatus</th>
        <th>Fecha Creación</th>
        <th>Ultima Actualización</th>
        <th>Fecha Baja</th>
    </tr>
    </thead>
    <tbody>
    @foreach($modulos as $modulo)
        <tr>
            <td>{{ $modulo->clave }}</td>
            <td>{{ $modulo->descripcion}}</td>
            <td>{{ $modulo->tipo}}</td>
            <td>{{ $modulo->proyecto}}</td>
            <td>{{ $modulo->mt2}}</td>
            <td>{{ $modulo->valorOperacion}}</td>
            <td>{{ $modulo->nivelPiso}}</td>
            <td>{{ $modulo->numCajones}}</td>
            <td>{{ $modulo->getBank != null ? $modulo->getBank->clave : ''}}</td>
            <td>{{ $modulo->cuenta }}</td>
            <td>{{ $modulo->clabe }}</td>
            <td>{{ $modulo->estatus }}</td>
            <td>{{ $modulo->fechaAlta}}</td>
            <td>{{ $modulo->fechaCambio}}</td>
            <td>{{ $modulo->fechaBaja}}</td>
        </tr>
    @endforeach
    </tbody>
</table>