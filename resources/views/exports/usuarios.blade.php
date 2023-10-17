<table>
    <thead>
    <tr>
        <th>Nombre</th>
        <th>Nombre de Usuario</th>
        <th>Email</th>
        <th>Contraseña</th>
        <th>Rol Asignado</th>
        <th>Estatus</th>
        <th>Fecha Creación</th>
        <th>Ultima Actualización</th>

    </tr>
    </thead>
    <tbody>
    @foreach($usuarios as $user)
        <tr>
            <td>{{ $user->user_name }}</td>
            <td>{{ $user->username}}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->password }}</td>
            <td>{{ $user->user_rol}}</td>
            <td>{{ $user->user_status}}</td>
            <td>{{ $user->created_at}}</td>
            <td>{{ $user->updated_at}}</td>
        </tr>
    @endforeach
    </tbody>
</table>