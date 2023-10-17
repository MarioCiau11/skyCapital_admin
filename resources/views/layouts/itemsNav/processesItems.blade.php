@php
   $usuario = auth()->user();
   $permisosComercial = $usuario->getAllPermissions()->where('categoria', '=', 'Ventas')->pluck('name')->toArray();
   $permisosCuentasPorCobrar = $usuario->getAllPermissions()->where('categoria', '=', 'Cuentas por cobrar')->pluck('name')->toArray();
   $permisosTesoreria = $usuario->getAllPermissions()->where('categoria', '=', 'Tesorería')->pluck('name')->toArray();
@endphp

@if($permisosComercial != null)
<li class="menu-item has-sub">
    <a class="menu-link nav-link menu-toggle" href="#">Comercial</a>
    <ul class="menu-sub menu-drop">
        <li class="menu-item"><a class="menu-link nav-link" href="{{ route('proc.ventas.index')}}">Ventas</a></li>
    </ul>
</li>
@endif

@if($permisosCuentasPorCobrar != null || $permisosTesoreria != null)
<li class="menu-item has-sub">
    <a class="menu-link nav-link menu-toggle" href="#">Gestión y finanzas</a>
    <ul class="menu-sub menu-drop">
        @if($permisosCuentasPorCobrar != null)
        <li class="menu-item"><a class="menu-link nav-link" href="{{ route('proc.cxc.index')}}">Cuentas por Cobrar</a></li>
        @endif
        @if($permisosTesoreria != null)
        <li class="menu-item"><a class="menu-link nav-link" href="{{ route('proc.tesoreria.index')}}">Tesorería</a></li>
        @endif
    </ul>
</li>
@endif