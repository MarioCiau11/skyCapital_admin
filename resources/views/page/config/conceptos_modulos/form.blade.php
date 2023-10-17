<div class="row">
    <div class="col-lg-12">
        <p class="tc-danger mr-auto mb-0"> * = Campos Obligatorios</p>
    </div>
    <div class="col-lg-8">
        <div class="field-item">
            <label for="" class="field-label">Nombre del concepto <span style="color: red">*</span></label>
            <div class="field-wrap">
                <input name="inputConcepto" value="{{ old('inputconcepto') ?? $ConceptosMod->nombreConcepto }}"
                    type="text" class="input-bordered" required>
                @if ($errors->has('inputConcepto'))
                    <div class="alert alert-danger">
                        {{ $errors->first('inputConcepto') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="field-item">
            <div class="field-wrap">
                <label for="" class="field-label">Estatus</label>
                <select value="" id="selectEstatus" name="selectEstatus" class=""
                    data-select2-theme="bordered" required>
                    <option value="1" @if (old('selectEstatus', $ConceptosMod->estatus) == 1 || old('selectEstatus', $ConceptosMod->estatus) === null) selected @endif>Alta</option>
                    <option value="0" @if (old('selectEstatus', $ConceptosMod->estatus) == 0 && old('selectEstatus', $ConceptosMod->estatus) !== null) selected @endif>Baja</option>
                </select>
                @if ($errors->has('selectEstatus'))
                    <div class="alert alert-danger">
                        {{ $errors->first('selectEstatus') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="field-item">
            <label for="" class="field-label">Módulo <span style="color: red">*</span></label>
            <div class="field-wrap">
                <select name="selectModulo" id="selectModulo" required>
                    <option value="">seleccione un Módulo</option>
                    <option value="VTAS">Ventas</option>
                    <option value="CXC">Cuentas por cobrar</option>
                    <option value="TES">Tesorería</option>
                </select>
                @if ($errors->has('selectModulo'))
                    <div class="alert alert-danger">
                        {{ $errors->first('selectModulo') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="field-item">
            <label for="" class="field-label">Movimiento <span style="color: red">*</span></label>
            <div class="field-wrap">
                <select name="selectMovimiento" id="selectMovimiento" required>

                </select>
                @if ($errors->has('selectMovimiento'))
                    <div class="alert alert-danger">
                        {{ $errors->first('selectMovimiento') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="field-item" id="clave">
            <label for="" class="field-label">Clave de Producto o servicio <span
                    style="color: red">*</span></label>
            <div class="field-wrap">
                {{-- <input id="inputClave" name="inputClave" type="text" class="input-bordered" readonly> --}}
                {!! Form::text('inputClave', null, ['class' => 'input-bordered', 'id' => 'inputClave', 'autocomplete' => 'on']) !!}
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        //recupero los valores que estan guardados en la bd
        window.moduloBD = "{{ isset($ConceptosMod) ? $ConceptosMod->modulo : '' }}";
        window.clave = "{{ isset($ConceptosMod) ? $ConceptosMod->claveProdServ : '' }}";
        const movimiento = "{{ isset($ConceptosMod) ? $ConceptosMod->movimiento : '' }}";
        $("#selectModulo").val('{{ isset($ConceptosMod) ? $ConceptosMod->modulo : '' }}').trigger(
            'change.select2');
        //hago la request ajax y pongo los valores recuperados
        async function recuperarData(modulo, movimiento) {
            if (modulo == 'VTAS') {
                $('#clave').show();
            }
            await jQuery.ajax({
                url: "/getMovimientos",
                type: "GET",
                dataType: "json",
                data: {
                    Modulo: modulo
                },
                success: function({
                    datos,
                    estatus
                }) {
                    if (estatus === 200) {
                        $('#selectMovimiento').children().remove();
                        $('#selectMovimiento').append(
                            '<option value="" selected> seleecione un Movimiento</option>');
                        datos.forEach((element) => {
                            $("#selectMovimiento").append(
                                `<option value="${element['value']}">${element['name']}</option>`
                            );
                        });

                        $('#selectMovimiento').val(movimiento).trigger('change');

                    }
                },
                error: function({
                    error,
                    status
                }) {
                    console.log(error, status);
                }
            });
        }

        if (window.moduloBD != '') {
            $('#selectModulo').val(window.moduloBD).trigger('change');
            recuperarData(window.moduloBD, movimiento);
        }
    });
</script>
