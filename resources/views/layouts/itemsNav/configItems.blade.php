@can('Párametros Generales')
<li class="menu-item"><a class="menu-link nav-link" href="{{route('config.parametros-generales.index')}}">Parametros Generales</a></li>
@endcan

@if (Auth::user()->can('Roles y Usuarios'))
<li class="menu-item has-sub"> 
    <a class="menu-link nav-link menu-toggle" href="#">Roles y Usuarios</a>
    <ul class="menu-sub menu-drop">
        <li class="menu-item"><a class="menu-link nav-link" href="{{route('config.roles.index')}}">Roles</a></li>
        <li class="menu-item"><a class="menu-link nav-link" href="{{route('config.usuarios.index')}}">Usuarios</a></li>
    </ul>
</li>
@endif

@can('Monedas')
<li class="menu-item"><a class="menu-link nav-link" href="{{route('config.monedas.index')}}">Monedas</a></li>
@endcan

@can('Formas Cobro/Pago')
<li class="menu-item"><a class="menu-link nav-link" href="{{route('config.formas-pago.index')}}">Formas de Pago</a></li>
@endcan

@can('Condiciones de Crédito')
<li class="menu-item"><a class="menu-link nav-link" href="{{route('config.condiciones-credito.index')}}">Condiciones Crédito</a></li>
@endcan

@can('Unidades')
<li class="menu-item"><a class="menu-link nav-link" href="{{route('config.unidades.index')}}">Unidades</a></li>
@endcan


{{-- @can('Conceptos de Módulos')
    <li class="menu-item"><a href="{{route('config.conceptos-modulos.index')}}" class="menu-link nav-link">Conceptos de Módulos</a></li>
@endcan --}}

{{-- @can('Párametros Generales')
<li class="menu-item"><a class="menu-link nav-link" href="{{route('config.autorizaciones.index')}}">Control Autorizaciones</a></li>
@endcan --}}