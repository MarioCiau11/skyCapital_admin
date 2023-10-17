@php
    $usuario = auth()->user();

    $permisosReportesTesoreria = $usuario->getAllPermissions()->where('tipoReporte', '=', 'Reportes de Tesorería')->pluck('name')->toArray();
    $permisosReportesVentas = $usuario->getAllPermissions()->where('tipoReporte', '=', 'Reportes de Ventas')->pluck('name')->toArray();
    $permisosReportesCxc = $usuario->getAllPermissions()->where('tipoReporte', '=', 'Reportes de Cuentas por Cobrar')->pluck('name')->toArray();

@endphp

@if($permisosReportesVentas != null)
<li class="menu-item has-sub">
    <a class="menu-link nav-link menu-toggle" href="#">Ventas</a>
    <ul class="menu-sub menu-drop">
        @can('Información de Módulos')
        <li class="menu-item"><a class="menu-link nav-link" href="{{ route('report.ventas.info') }}">Información de Módulos</a></li>
        @endcan
        @can('Ventas de Módulos')
        <li class="menu-item"><a class="menu-link nav-link" href="{{ route('report.ventas.venta') }}">Ventas de Módulos</a></li>
        @endcan
    </ul>
</li>
@endif
@if($permisosReportesCxc != null)
<li class="menu-item has-sub">

    <a class="menu-link nav-link menu-toggle" href="#">Cuentas por Cobrar</a>
    <ul class="menu-sub menu-drop">
        @can('Antigüedad de Saldos')
        <li class="menu-item"><a class="menu-link nav-link" href="{{ route('report.cxc.saldos') }}">Antigüedad de Saldos</a></li>
        @endcan
        @can('Estado de Cuenta')
        <li class="menu-item"><a class="menu-link nav-link" href="{{ route('report.cxc.estado') }}">Estado de Cuenta</a></li>
        @endcan
        @can('Ingresos por Proyecto')
        <li class="menu-item"><a class="menu-link nav-link" href="{{ route('report.cxc.ingresos') }}">Ingresos por Proyecto</a></li>
        @endcan
    </ul>
</li>
@endif
@if($permisosReportesTesoreria != null)
<li class="menu-item has-sub">
    <a class="menu-link nav-link menu-toggle" href="#">Tesorería</a>
    <ul class="menu-sub menu-drop">
        @can('Auxiliares por Cuenta de Dinero Nivel - Concentrado')
        <li class="menu-item"><a class="menu-link nav-link" href="{{ route('report.tesoreria.conc') }}">Auxiliares por Cuenta de Dinero Nivel - Concentrado</a></li>
        @endcan
        @can('Auxiliares por Cuenta de Dinero Nivel - Desglosado')
        <li class="menu-item"><a class="menu-link nav-link" href="{{ route('report.tesoreria.desg') }}">Auxiliares por Cuenta de Dinero Nivel - Desglosado</a></li>
        @endcan
    </ul>
</li>
@endif