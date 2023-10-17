@php
    $usuario = auth()->user();

    $permisosReportes = $usuario->getAllPermissions()->where('categoria', '=', 'Reportes Módulos')->pluck('name')->toArray();
    $permisosHerramientas = $usuario->getAllPermissions()->where('categoria', '=', 'Herramientas')->pluck('name')->toArray();


@endphp

<nav class="header-menu" id="example-menu-04" >
    <ul class="menu menu-s2 animated" data-animate="fadeInDown" data-delay=".75">
        <li class="menu-item"><a class="menu-link nav-link"href="/">Home</a></li>
        <li class="menu-item has-sub">
            <a class="menu-link nav-link menu-toggle" href="#">Configuración</a>
            <ul class="menu-sub menu-drop">
                   @include('layouts.itemsNav.configItems')
            </ul>
        </li>
        <li class="menu-item has-sub">
            <a class="menu-link nav-link menu-toggle" href="#">Catálogos</a>
            <ul class="menu-sub menu-drop">
                @include('layouts.itemsNav.catalogsItems')
            </ul>
        </li>
        <li class="menu-item has-sub">
            <a class="menu-link nav-link menu-toggle" href="#">Procesos</a>
            <ul class="menu-sub menu-drop">
                @include('layouts.itemsNav.processesItems')
            </ul>
        </li>
        @if($permisosReportes != null)
        <li class="menu-item has-sub">
            <a class="menu-link nav-link menu-toggle" href="#">Reportes</a>
            <ul class="menu-sub menu-drop">
                @include('layouts.itemsNav.reportsItems')
            </ul>
        </li>
        @endif
        @if($permisosHerramientas != null)
        <li class="menu-item has-sub">
            <a class="menu-link nav-link menu-toggle" href="#">Herramientas</a>
            <ul class="menu-sub menu-drop">
                @include('layouts.itemsNav.toolsItems')
            </ul>
        </li>
        @endif
    </ul>
    @php
        // dd(Auth::user());
        // dd(session('sucursal'), session('company')->logo);
    @endphp
    <ul class="menu-btns animated" data-animate="fadeInDown" data-delay=".85">
        <li class="toggle-wrap toggle-wrap-nav">
            <a href="#" class="toggle-tigger active"><div class="text-thumb text-thumb-xs " style="text-transform: capitalize; background-color: #AF865C">{{ substr(Auth::user()->username, 0,1) }}</div></a>
            <div class="toggle-class toggle-class-lg toggle-drop toggle-drop-right">
           
                <ul class="drop-list" style=" min-width: 250px ; max-width:250px auto;">
                    <li>
                        <a><div class="user-dropdown-head">
                        <h6 class="user-dropdown-name"><i class="fas fa-user"></i> {{Auth::user()->user_name }} <span>({{Auth::user()->username}})</span></h6>
                        <span class="user-dropdown-email"><i class="fas fa-building"></i> Empresa: <span>({{ session()->has('company') ? session('company')->clave : '' }})</span></span> <br>
                        <span class="user-dropdown-email"><i class="fas fa-building"></i> Sucursal: <span>({{ session()->has('sucursal') ? session('sucursal')->clave: ''}})</span></span> 
                        {{-- <span class="user-dropdown-email "><i class="fas fa-envelope"></i> {{ Auth::user()->email }} </span> --}}
                    </div></a>
                    <hr>
                    </li>
                    

                    <li><a href="{{ route('logout') }}" onclick="logout()">Cerrar sesión</a></li>
                </ul>
            </div>
        </li>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </ul>
</nav>

<script>
    function logout(){
        event.preventDefault();
        document.getElementById('logout-form').submit();
        localStorage.setItem('forceLogout', 'true');
    }

</script>