@php
    $usuario = auth()->user();

    $permisosHerramientas = $usuario->getAllPermissions()->where('categoria', '=', 'Herramientas')->pluck('name')->toArray();

@endphp
@if($permisosHerramientas != null)
<li class="menu-item"><a href="{{ route('tools.facturacion') }}">Facturacion Masiva</a></li>
@endif
