<!DOCTYPE html>
<html lang="zxx" class="js">
<head>
	<meta charset="utf-8">
<meta name="author" content="Netlancers">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Fav Icon  -->
{{-- <link rel="shortcut icon" href="images/favicon.png"> --}}
<!-- Site Title  -->
<title>Sky Capital Administración</title>
<!-- Bundle and Base CSS -->

<link href="{{ asset('css/vendor.bundle.css') }}" rel="stylesheet">
<link href="{{ asset('css/style-dark.css') }}" rel="stylesheet">
<link href="{{ asset('css/select2.css') }}" rel="stylesheet" />
<link href="{{ asset('css/dataTables.bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('css/select.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ asset('js/summernote/summernote-lite.css') }}"  rel="stylesheet">


<link href="{{ asset('css/theme.css') }}" rel="stylesheet" />

<script src="{{ asset('js/currency.min.js') }}"></script>
<script src="{{ asset('js/jquery-1.11.1.min.js') }}"></script>
<script src="{{ asset('js/crypto-js/crypto-js.js') }}"></script>
<script src="{{ asset('js/inputmask/dependencyLibs/inputmask.dependencyLib.js') }}"></script>
<script src="{{ asset('js/inputmask/inputmask.js') }}"></script>
<script src="{{ asset('js/inputmask/inputmask.extensions.js') }}"></script>
<script src="{{ asset('js/inputmask/inputmask.numeric.extensions.js') }}"></script>
<script src="{{ asset('js/inputmask/inputmask.date.extensions.js') }}"></script>

{{-- Sweetalert --}}
<script src="{{ asset('js/sweetalert.min.js') }}"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css" />





@yield('extra-css')
</head>


    <body class="nk-body body-wider">

		@if(!Auth::check())
			@yield('content')
		@else
			@include('layouts.header')
			<main class="ath-body" >
				<section class="section" id="ico">
					@yield('content')
				</section>
			</main>
		@endif

	
		@php

		if(session()->has('company')){
			 $path = 'empresas/' . session('company')->idEmpresa . '/logo.png';
            $src = '';
            if (Storage::disk('public')->exists($path)) {
                // dd($src);
                $contents = Storage::disk('public')->get($path);
                $contents = base64_encode($contents);
                $src = 'data: ' . mime_content_type('../storage/app/public/' . $path) . ';base64,' . $contents;
            }
		}else{
			$src = '';
		}
		@endphp
	
	{{-- <div class="preloader preloader-alt no-split"><span class="spinner spinner-alt"><img class="spinner-brand" src="{{ asset('images/logo-full-white.png') }}" srcset="{{ asset('images/logo-full-white.png') }}" alt="logo"> </span></div> --}}
	@if(Auth::check())
		<div class="preloader preloader-alt no-split"><span class="spinner spinner-alt"> <img  class="spinner-brand" decoding="async"  src="{{$src}}" class="attachment-full size-full wp-image-3000" alt="logo skycapital" loading="lazy"></span></div>
	@endif	
	<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
		@csrf
	</form>
	<a href="{{ route('logout') }}" onclick="event.preventDefault();
    document.getElementById('logout-form').submit();" class="d-none">Cerrar sesión</a>
	

	<!-- .modal @e -->
	@yield('modal')
	<!-- .modal @e -->
	<!-- JavaScript -->
	<script>
		// Al cargar la página, inicializar el valor en el almacenamiento local
		localStorage.setItem('forceLogout', 'false');
		// console.log(localStorage.getItem('forceLogout'));
		
		// Verificar periódicamente si se ha forzado el cierre de sesión
		setInterval(function () {
			if (localStorage.getItem('forceLogout') === 'true') {
				// Realizar acciones de cierre de sesión en esta pestaña
				
				// hacer que se de clic en el botón de cerrar sesión
				document.querySelector('a[href="{{ route('logout') }}"]').click();
				// Redirigir a la página de inicio de sesión
				window.location.href = "{{ route('login') }}";
				
					}
		}, 1000); // Verificar cada segundo (puedes ajustar este valor)
	</script>

	<script src="{{ asset('js/jquery.bundle.js') }}"></script>
	<script src="{{ asset('js/language/Table/tablaIdioma.js') }}"></script>
	<script src="{{ asset('js/scripts.js') }}"></script>
	<script src="{{ asset('js/charts.js') }}"></script>
	<script src="{{ asset('js/moment.min.js') }}"></script>
	<script src="{{ asset('js/jquery-1.11.1.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap-wizard.min.js') }}"></script>
	<script src="{{ asset('js/select2.min.js') }}"></script>
	<script src="{{ asset('js/jquery-ui-1.10.3.min.js') }}"></script>
	<script src="{{ asset('js/jquery-migrate-1.2.1.min.js') }}"></script>
	<script src="{{ asset('js/helpers.js') }}"></script>
	<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
	<script src="{{ asset('js/sweetalert.min.js') }}"></script>
	<script src="{{ asset('js/dropzone.min.js') }}"></script>
	<script src="{{ asset('js/jquery-migrate-1.2.1.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('js/jquery-ui-1.10.3.min.js') }}"></script>
	<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap.min.js"></script>
	<script src="https://cdn.datatables.net/select/1.4.0/js/dataTables.select.min.js"></script>


	<script src="{{ asset('js/summernote/summernote-lite.js') }}"></script>

	{{-- <script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js"></script> --}}

	{{-- iconos --}}
	<script src="{{ asset('js/fontawesome/js/fontawesome-all.js') }}"></script>
	<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>

	@yield('extra-js')
</body>

</html>