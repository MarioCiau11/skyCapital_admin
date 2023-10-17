@extends('layouts.layout')

@section('title', 'Login')


@section('extra-js')
<script src="{{ asset('js/login.js') }}"></script>
@endsection


@section('content')
<main class="nk-pages nk-pages-centered bg-theme">
    <div class="ath-container">
        <div class="ath-header text-center">

            <a class="ath-logo">  <img class="logo-dark" decoding="async"  src="https://skycapital.mx/wp-content/uploads/2021/03/Mesa-de-trabajo-1.webp" class="attachment-full size-full wp-image-3000" alt="logo skycapital" loading="lazy"></a>
        </div>
        <div class="ath-body">
            <h5 class="ath-heading title">Iniciar sesión <small class="tc-default">con tu cuenta</small></h5>
            <form class="login-form" method="POST" action="{{ route('login') }}" id="form-login">
                {{ csrf_field() }}
                <div class="field-item">
                    <div class="field-wrap">
                        <input type="text" class="input-bordered" placeholder="Usuario" id="username"  name="username" required autocomplete="username">
                        <div class="alert alert-danger mt-1" id="user-mensaje" style="display:none">
                        
                        </div>
                    </div>
                </div>
                <div class="field-item">
                    <div class="field-wrap">
                        <input type="password" class="input-bordered" placeholder="Contraseña" id="password" name="password" required autocomplete="current-password">
                        <div class="alert alert-danger mt-1" id="pass-mensaje" style="display:none">
                        
                        </div>
                    </div>
                </div>
                <div class="field-item">
                    <label class="field-label">Empresa</label>
                    <div class="field-wrap">
                        <select name="selectEmpresas" class="" data-select2-theme="bordered" required id="select-empresas">
                        </select>
                        <div class="alert alert-danger mt-1" id="empresa-mensaje" style="display:none">
                        
                        </div>
                    </div>
                </div>
                <div class="field-item">
                    <label class="field-label">Sucursal</label>
                    <div class="field-wrap">
                        <select name="selectSucursales" class="" data-select2-theme="bordered" required id="select-sucursales">
                        </select>
                        <div class="alert alert-danger mt-1" id="sucursal-mensaje" style="display:none">
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center pdb-r">
                    <div class="field-item pb-0">
                        <label class="checkbox">
                            <input type="checkbox"
                            name="remember" {{ old('remember') ? 'checked' : '' }}> Recuérdame
                          </label>
                    </div>
                    {{-- <div class="forget-link fz-6">
                        <a href="page-reset.html">Forgot password?</a>
                    </div> --}}
                </div>
                <button class="btn btn-primary  btn-md" style="background-color: #AF865C; border: #AF865C;" id="btn-login">Iniciar Sesión</button>
            </form>
            {{-- <div class="sap-text"><span>Or Sign In With</span></div>

            <ul class="row gutter-20px gutter-vr-20px">
                <li class="col"><a href="#" class="btn btn-md btn-facebook btn-block"><em class="icon fab fa-facebook-f"></em><span>Facebook</span></a></li>
                <li class="col"><a href="#" class="btn btn-md btn-google btn-block"><em class="icon fab fa-google"></em><span>Google</span></a></li>
            </ul> --}}
        </div>
        {{-- <div class="ath-note text-center tc-light">
            Don’t have an account? <a href="page-register.html"> <strong>Sign up here</strong></a>
        </div> --}}
    </div>
</main>


@endsection

