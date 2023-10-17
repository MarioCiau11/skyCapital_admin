<header class="nk-header page-header is-transparent is-sticky is-shrink is-dark is-split" id="header">
    <!-- Header @s -->
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
	
    <div class="header-main">
        <div class="header-container container">
            <div class="header-wrap">
                <!-- Logo @s -->
                <div class="header-logo logo animated" data-animate="fadeInDown" data-delay=".65" style="visibility: visible; animation-delay: 0.65s;">
                    <a href="/" class="logo-link">
                        <img class="logo-light" decoding="async"  src="{{$src}}" class="attachment-full size-full wp-image-3000" alt="logo skycapital" loading="lazy" style="">
                    </a>
                </div>

                <!-- Menu Toogle @s -->
                <div class="header-nav-toggle">
                    <a href="#" class="navbar-toggle" data-menu-toggle="example-menu-04" >
                        <div class="toggle-line">
                            <span></span>
                        </div>
                    </a>
                </div>

                <!-- Menu @s -->
                <div class="header-navbar header-navbar-s1">
                    @include('layouts.navbar')
                </div><!-- .header-navbar @e -->
            </div>                                                
        </div>
    </div><!-- .header-main @e -->


</header>