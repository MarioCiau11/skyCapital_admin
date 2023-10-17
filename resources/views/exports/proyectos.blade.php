<table>
    <thead>
    <tr>
        <th>Clave</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Presupuesto</th>
        <th># Cajones estacionamiento</th>
        <th># Cajones estacionamiento restantes</th>
        <th>Fecha Inicio</th>
        <th>Fecha Fin</th>
        <th>Año Fin Proyecto</th>
        <th>MT2</th>
        <th>Niveles Totales</th>
        <th>Dirección</th>
        <th>Colonia</th>
        <th>Ciudad</th>
        <th>Estado</th>
        <th>País</th>
        <th>CP</th>
        <th>Categoría</th>
        <th>Grupo</th>
        <th>Estatus</th>
        <th>Fecha Creación</th>
        <th>Ultima Actualización</th>
        <th>Fecha Baja</th>
    </tr>
    </thead>
    <tbody>
    @foreach($proyectos as $proyecto)
        <tr>
            <td>{{ $proyecto->clave }}</td>
            <td>{{ $proyecto->nombre }}</td>
            <td>{{ $proyecto->descripcion}}</td>
            <td>{{ $proyecto->presupuesto}}</td>
            <td>{{ $proyecto->numCajones}}</td>
            <td>{{ $proyecto->numCajonesRestantes}}</td>
            <td>{{ $proyecto->fechaIniProyecto}}</td>
            <td>{{ $proyecto->fechaFinProyecto}}</td>
            <td>{{ $proyecto->añoFinProyecto}}</td>
            <td>{{ $proyecto->mt2 }}</td>
            <td>{{ $proyecto->nivelesTotales }}</td>
            <td>{{ $proyecto->direccion }}</td>
            <td>{{ $proyecto->colonia }}</td>
            <td>{{ $proyecto->ciudadMunicipio }}</td>
            <td>{{ $proyecto->estado }}</td>
            <td>{{ $proyecto->pais }}</td>
            <td>{{ $proyecto->cp }}</td>
            <td >{{$proyecto->getCategoria != null ? $proyecto->getCategoria->nombre : ''}} </td>
            <td >{{$proyecto->getGrupo != null ? $proyecto->getGrupo->nombre : ''}} </td>
            <td>{{ $proyecto->estatus == 1 ? 'Alta' : 'Baja' }}</td>
            <td>{{ $proyecto->fechaAlta}}</td>
            <td>{{ $proyecto->fechaCambio}}</td>
            <td>{{ $proyecto->fechaBaja}}</td>
        </tr>
    @endforeach
    </tbody>
</table>