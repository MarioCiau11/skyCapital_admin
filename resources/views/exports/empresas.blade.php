<table>
    <thead>
    <tr>
        <th>Clave</th>
        <th>Nombre</th>
        <th>Nombre Corto</th>
        <th>Descripción</th>
        <th>Estatus</th>
        <th>Dirección</th>
        <th>Colonia</th>
        <th>CP</th>
        <th>Ciudad</th>
        <th>Estado</th>
        <th>País</th>
        <th>Teléfono 1</th>
        <th>Teléfono 2</th>
        <th>Correo</th>
        <th>RFC</th>
        <th>Régimen Fiscal</th>
        <th>Registro Patronal</th>
        {{-- <th>Ruta Llave</th>
        <th>Ruta Certificado</th> --}}
        <th>Ruta Documentos</th>
        <th>Fecha Creación</th>
        <th>Ultima Actualización</th>
        <th>Fecha Baja</th>

    </tr>
    </thead>
    <tbody>
    @foreach($empresas as $empresa)
        <tr>
            <td>{{ $empresa->clave }}</td>
            <td>{{ $empresa->nombreEmpresa}}</td>
            <td>{{ $empresa->nombreCorto}}</td>
            <td>{{ $empresa->descripcion }}</td>
            <td>{{ $empresa->estatus }}</td>
            <td>{{ $empresa->direccion}}</td>
            <td>{{ $empresa->colonia}}</td>
            <td>{{ $empresa->codigoPostal}}</td>
            <td>{{ $empresa->ciudad}}</td>
            <td>{{ $empresa->estado}}</td>
            <td>{{ $empresa->pais}}</td>
            <td>{{ $empresa->telefono1}}</td>
            <td>{{ $empresa->telefono2}}</td>
            <td>{{ $empresa->correoElectronico}}</td>
            <td>{{ $empresa->RFC}}</td>
            <td>{{ $empresa->regimenFiscal}}</td>
            <td>{{ $empresa->registroPatronal}}</td>
            {{-- <td>{{ $empresa->rutaLlave}}</td>
            <td>{{ $empresa->rutaCertificado}}</td> --}}
            <td>{{ $empresa->rutaDocumentos}}</td>
            <td>{{ $empresa->fechaAlta}}</td>
            <td>{{ $empresa->fechaCambio}}</td>
            <td>{{ $empresa->fechaBaja}}</td>
        </tr>
    @endforeach
    </tbody>
</table>