<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Módulo</th>
            <th>Movimiento</th>
            <th>Clave de producto o servicio</th>
            <th>Estatus</th>
            <th>Usuario que lo registró</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($conceptosMod as $concepto)
        <tr>
            <td>{{$concepto->nombreConcepto}}</td>
            <td>{{$concepto->modulo}}</td>
            <td>{{$concepto->movimiento}}</td>
            <td>{{$concepto->estatus == 1 ? 'Alta' : 'Baja'}}</td>
            <td>{{$concepto->user_id}}</td>
        </tr>
        @endforeach
    </tbody>
</table>