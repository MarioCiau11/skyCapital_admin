@can('Empresas')
<li class="menu-item"><a class="menu-link nav-link" href="{{ route('cat.empresas.index') }}">Empresas</a></li>
@endcan
@can('Sucursales')
<li class="menu-item"><a class="menu-link nav-link" href="{{ route('cat.sucursales.index') }}">Sucursales</a></li>
@endcan

@if(Auth::user()->can('Cuentas de Dinero') || Auth::user()->can('Instituciones Financieras') )
<li class="menu-item has-sub">
    <a class="menu-link nav-link menu-toggle" href="#">Cuentas de Dinero</a>
    <ul class="menu-sub menu-drop">
        @can('Cuentas de Dinero')
        <li class="menu-item"><a class="menu-link nav-link" href="{{ route('cat.cuentas-dinero.index') }}">Cuentas de Dinero</a></li>
        @endcan
        @can('Instituciones Financieras')
        <li class="menu-item"><a class="menu-link nav-link" href="{{ route('cat.instituciones.index') }}">Instituciones Financieras</a></li>
        @endcan
    </ul>
</li>
@endif

@can('Clientes')
<li class="menu-item"><a class="menu-link nav-link" href="{{ route('cat.clientes.index') }}">Clientes</a></li>
@endcan
@can('Artículos')
<li class="menu-item"><a class="menu-link nav-link" href="{{ route('cat.articulos.index') }}">Artículos</a></li>
@endcan
@can('Agentes de Venta')
<li class="menu-item"><a class="menu-link nav-link" href="{{ route('cat.agentes-venta.index') }}">Asesor Comercial</a></li>
@endcan
@can('Proyectos')
<li class="menu-item"><a class="menu-link nav-link" href="{{ route('cat.proyectos.index') }}">Proyectos</a></li>
@endcan
@can('Módulos')
<li class="menu-item"><a class="menu-link nav-link" href="{{ route('cat.modulos.index') }}">Módulos</a></li>
@endcan
@can('Etiquetas')
<li class="menu-item"><a class="menu-link nav-link" href="{{ route('cat.etiquetas.index') }}">Etiquetas</a></li>
@endcan
@can('Promociones')
<li class="menu-item"><a class="menu-link nav-link" href="{{ route('cat.promociones.index') }}">Promociones</a></li>
@endcan
