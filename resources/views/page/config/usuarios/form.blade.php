  <?php

//    dd($user->getCompanyIdsAttribute());

  ?>
<div class="col-lg-12">
    <p class="tc-danger mr-auto mb-0"> * = Campos Obligatorios</p>
</div>
  <div class="col-md-12">
    <div class="form-group mt10">
    <label class="field-label">Nombre <span style="color:red">*</span></label>
    <div class="field-wrap">
        <input name="name" type="text" class="input-bordered" id="name" required aria-required="true" value="{{ old('name') ?? $user->user_name }}">
    </div>
    @if ($errors->has('name'))
    <div class="alert alert-danger">
        {{ $errors->first('name') }}
    </div>
  @endif
</div>

<div class="field-item">
    <label class="field-label">Usuario <span style="color:red">*</span></label>
    <div class="field-wrap">
        <input name="username" type="text" class="input-bordered" id="username" required aria-required="true" value="{{ old('username') ?? $user->username }}">
    </div>
    @if ($errors->has('username'))
    <div class="alert alert-danger">
        {{ $errors->first('username') }}
    </div>
  @endif
</div>

<div class="field-item">
    <label class="field-label">Correo Electrónico </label>
    <div class="field-wrap">
        <input name="email" type="email" class="input-bordered" id="email" value="{{ old('email') ?? $user->email }}">
    </div>
    @if ($errors->has('email'))
    <div class="alert alert-danger">
        {{ $errors->first('email') }}
    </div>
    @endif
</div>

<div class="field-item">
    <label class="field-label">Contraseña <span style="color:red">*</span></label>
    <div class="field-wrap">
        <input name="password" type="password" class="input-bordered" id="password" @if (empty($user->password)) required @endif >
    </div>
    @if ($errors->has('password'))
    <div class="alert alert-danger">
        {{ $errors->first('password') }}
    </div>
    @endif
    <div class="alert alert-warning" id="alertPassword">
        
    </div>
</div>

<div class="field-item">
    <label class="field-label">Confirmar Contraseña <span style="color:red">*</span></label>
    <div class="field-wrap">
        <input name="inputContraseña2" type="password" class="input-bordered" id="inputContraseña2" @if (empty($user->password)) required @endif >
    </div>
    @if ($errors->has('inputContraseña2'))
    <div class="alert alert-danger">
        {{ $errors->first('inputContraseña2') }}
    </div>
    @endif
    <div class="alert alert-warning" id="alertPassword2">
            </div>
</div>

<div class="field-item">
    <label class="field-label">Estatus </label>
    <div class="field-wrap">
        <select value="0" id="selectEstatus" name="selectEstatus" class="" data-select2-theme="bordered" >
            <option value="1" @if (old('selectEstatus', $user->user_status) == 1 || old('selectEstatus', $user->user_status) === null) selected @endif>Alta</option>
        <option value="0" @if (old('selectEstatus', $user->user_status) == 0 && old('selectEstatus', $user->user_status) !== null) selected @endif>Baja</option>
        </select>
    </div>
    @if ($errors->has('selectEstatus'))
    <div class="alert alert-danger">
        {{ $errors->first('selectEstatus') }}
    </div>
    @endif
</div>

<div class="field-item">

          
    <label class="field-label">Rol <span style="color:red">*</span></label>
    <div class="field-wrap">
        {!! Form::select('selectRol', $roles, $user->user_rol, array('id' => 'selectRol', "class" => '')) !!} 
    </div>
    @if ($errors->has('selectRol'))
    <div class="alert alert-danger">
        {{ $errors->first('selectRol') }}
    </div>
    @endif
</div>

<div class="field-item">
    <label class="field-label">Empresa <span style="color:red">*</span></label>
    <div class="field-wrap">
        {!! Form::select('empresas[]', $companies, $user->getCompanyIdsAttribute(), [
            'id' => 'select-multi',
            'class' => 'widthAll',
            'multiple',
            'required'
        ]) !!}

    </div>
    @if ($errors->has('selectEmpresa'))
    <div class="alert alert-danger">
        {{ $errors->first('selectEmpresa') }}
    </div>
    @endif
</div>
<script>
    jQuery(document).ready(function($) {
        $('#alertPassword').hide();
        $('#alertPassword2').hide();

        let alerta = "la contraseña debe ser de mínimo 4 caracteres";
        let alerta2 = "las contraseñas no coinciden";

        let inputcontraseña = $('#password');
        let inputcontraseña2 = $('#inputContraseña2');

        inputcontraseña.on('keyup', function() {
            if (inputcontraseña.val().length < 4) {
                $('#alertPassword').show();
                $('#alertPassword').html(alerta);
            } else {
                $('#alertPassword').hide();
            }
        });
        inputcontraseña2.on('keyup', function() {
            if (inputcontraseña.val() != inputcontraseña2.val()) {
                $('#alertPassword2').show();
                $('#alertPassword2').html(alerta2);
            } else {
                $('#alertPassword2').hide();
            }
        });
    });
</script>