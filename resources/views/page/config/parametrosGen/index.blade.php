@extends('layouts.layout')

<?php
    //  dd($parametro);
?>
@section('content')
    <main class=" ath-body mt-2">
        <div class="col-lg-12 col-sm-12 col-md-12">
            <!-- Nav tabs -->
            <ul class="nav tab-nav tab-nav-line tab-nav-center">
                <li>
                    <a href="#tab-1" data-toggle="tab" class="active">Configuración General</a>
                </li>
                <li>
                    <a href="#tab-2" data-toggle="tab">Bóvedas</a>
                </li>
                <li>
                    <a href="#tab-3" data-toggle="tab">Plantillas</a>
                </li>
                <li>
                    <a href="#tab-4" data-toggle="tab">Consecutivos Movimientos</a>
                </li>
            </ul>
            <form action="{{route('config.parametros-generales.store')}}" class="form-validate createForm" method="post" id="form-create">
                @csrf
                <!-- Tab panes -->
                <div class="tab-content nopadding noborder">
                    <div class="tab-pane active" id="tab-1">
                        <div class="nk-block-text">
                            <h2 class="title">Configuración del sistema</h2>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row ">

                                    
                                    <div class="col-lg-6"> 
                                        <div class="field-item">
                                            <label class="field-label">Moneda</label>
                                            <div class="field-wrap">
                                                {!! Form::select('selectmoneda', $monedas, null, [
                                                    'id' => 'selectmoneda',
                                                    'class' => '',
                                                    'placeholder' => 'Seleccione uno...',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12 ">
                                        <div class="row">
                                            <div class="col-lg-6 col-sm-12">
                                                <label class="field-label">Ejercicio Inicia</label>
                                                <input id="inicioFecha" type="date" name="inicioFecha" value="{{ isset($parametro) ? $parametro->inicioEjerecicio : '2022-01-01'}}" class="input-bordered">
                                            </div>
                                            <div class="col-lg-6 col-sm-12">
                                                <label class="field-label">Ejercicio Termina</label>
                                                <input id="finFecha" type="date" name="finFecha" value="{{ isset($parametro) ? $parametro->finEjercicio : '2022-01-01'}}" class="input-bordered">
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 "> 
                                        <div class="field-item">
                                            <label class="field-label">Días Hábiles</label>
                                            <div class="field-wrap">
                                                {!! Form::select('selectdias', ['Lun-Vie' => 'Lun-Vie', 'Lun-Sab' => 'Lun-Sab', 'Todos' => 'Todos'], null, [
                                                    'id' => 'selectdias',
                                                    'class' => '',
                                                    'placeholder' => 'Seleccione uno...',
                                                ]) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-sm-12 "> 
                                        <div class="field-item">
                                            <label class="field-label">Morosidad</label>
                                            <div class="field-wrap">
                                                <input type="text" name="inputMorosidad" id="inputMorosidad" class=" input-bordered percentage" placeholder="0%" value="{{ isset($parametro) ? $parametro->morosidad : ''}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="field-item">
                                            <label class="field-label"># Días envío notificación "Aviso por vencer"</label>
                                            <div class="field-wrap">
                                                <input type="number" name="inputAviso" id="inputAviso" class=" input-bordered" placeholder="0" value="{{ isset($parametro) ? $parametro->aviso1 : ''}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="field-item">
                                            <label class="field-label"># Días envío notificación "Aviso pago vencido"</label>
                                            <div class="field-wrap">
                                                <input type="number" name="inputAviso2" id="inputAviso2" class=" input-bordered" placeholder="0" value="{{ isset($parametro) ? $parametro->aviso2 : ''}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- tab-pane -->
                    <div class="tab-pane" id="tab-2">
                        <div class="nk-block-text">
                            <h2 class="title">Bóvedas</h2>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-sm-12">
                                <div class="field-item">
                                    <label for="" class="field-label">Documentos Proyectos</label>
                                    <div class="field-wrap">
                                        <input type="text" class="input-bordered" name="inputDocsProyectos" placeholder="CLAVEPROYECTO/PROYECTO" value="{{ isset($parametro) ? $parametro->docsProyectos : ''}}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-sm-12">
                                <div class="field-item">
                                    <label for="" class="field-label">Documentos Módulos</label>
                                    <div class="field-wrap">
                                        <input type="text" class="input-bordered" name="inputDocsMódulos" placeholder="CLAVEPROYECTO/PROYECTO/MÓDULO" value="{{ isset($parametro) ? $parametro->docsModulos : ''}}">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-12 col-sm-12">
                                <div class="field-item">
                                    <label for="" class="field-label">Documentos Clientes</label>
                                    <div class="field-wrap">
                                        <input type="text" class="input-bordered" name="inputDocsClientes" placeholder="CLIENTES/CLAVECLIENTE" value="{{ isset($parametro) ? $parametro->docsClientes : ''}}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 col-sm-12">
                                <div class="field-item">
                                    <label for="" class="field-label">Documentos Movimientos</label>
                                    <div class="field-wrap">
                                        <input type="text" class="input-bordered" name="inputDocsMovimientos" placeholder="CLIENTES/CLAVECLIENTE" value="{{ isset($parametro) ? $parametro->docsMovimientos : ''}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- tab-pane -->
                    <div class="tab-pane" id="tab-3">
                        <div class="nk-block-text">
                            <h2 class="title">Plantillas</h2>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('aviso1', 'Notificación "Aviso por Vencer"', ['class' => 'field-label']) !!}
                                {!! Form::textarea('aviso1', isset($parametro) ? $parametro->plantillaAviso1 : '' , [
                                    'class' => 'form-control',
                                    'id' => 'aviso1',
                                    'autocomplete' => 'on',
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('aviso2', 'Notificación "Aviso Pago Vencido"', ['class' => 'field-label']) !!}
                                {!! Form::textarea('aviso2', isset($parametro) ? $parametro->plantillaAviso2 : '' , [
                                    'class' => 'form-control',
                                    'id' => 'aviso2',
                                    'autocomplete' => 'on',
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('contrato', 'Contrato', ['class' => 'field-label']) !!}
                                {!! Form::textarea('contrato', isset($parametro) ? $parametro->contrato : '' , [
                                    'class' => 'form-control',
                                    'id' => 'contrato',
                                    'autocomplete' => 'on',
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('pagares', 'Pagarés', ['class' => 'field-label']) !!}
                                {!! Form::textarea('pagares', isset($parametro) ? $parametro->pagare : '' , [
                                    'class' => 'form-control',
                                    'id' => 'pagares',
                                    'autocomplete' => 'on',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('convenio', 'Convenio Comercial', ['class' => 'field-label']) !!}
                                {!! Form::textarea('convenio', isset($parametro) ? $parametro->convenio : '' , [
                                    'class' => 'form-control',
                                    'id' => 'convenio',
                                    'autocomplete' => 'on',
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::label('notificacion', 'Formato notificación', ['class' => 'field-label']) !!}
                                {!! Form::textarea('notificacion', isset($parametro) ? $parametro->notificacion : '' , [
                                    'class' => 'form-control',
                                    'id' => 'notificacion',
                                    'autocomplete' => 'on',
                                ]) !!}
                            </div>
                        </div>


                    </div> <!-- tab-pane -->
                    <div class="tab-pane" id="tab-4">
                        <div class="nk-block-text">
                            <h2 class="title">Consecutivos Movimientos</h2>
                        </div>

                        <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-12 col-md-12">
                            <div class="row">
                                <div class="col-lg-2 col-md-4">
                                    <ul class="nav tab-nav tab-nav-vr tab-nav-bdr mr-lg-3">
                                        <li><a class="active" data-toggle="tab" href="#tab-1-1"><em class="fas fa-caret-right"></em>Ventas</a></li>
                                        <li><a data-toggle="tab" href="#tab-1-2" class=""><em class="fas fa-caret-right"></em>CxC</a></li>
                                        <li><a data-toggle="tab" href="#tab-1-3" class=""><em class="fas fa-caret-right"></em>Tesorería</a></li>
                                    </ul>
                                </div><!-- .col -->
                                <div class="col-lg-10 col-md-8">
                                    <div class="tab-content nopadding noborder">
                                        <div class="tab-pane active" id="tab-1-1">

                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th class="table-head">Movimiento</th>
                                                        <th class="table-des">Último Consecutivo</th>
                                                    </tr>
                                                    {{-- <tr>
                                                        <td class="table-head">Cotizador</td>
                                                        <td class="table-des"><input type="number" class="input-bordered" name="consecutivoCotizador" id="consecutivoCotizador" placeholder="0" value="{{ isset($parametro) ? $parametro->consCotizador : ''}}"></td>
                                                    </tr> --}}
                                                    <tr>
                                                        <td class="table-head">Contrato</td>
                                                     <td class="table-des"><input type="number" class="input-bordered" name="consecutivoContrato" id="consecutivoContrato" placeholder="0" value="{{ isset($consecutivos) ? $consecutivos->consContrato : ''}}"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table-head">Factura</td>
                                                     <td class="table-des"><input type="number" class="input-bordered" name="consecutivoFactura" id="consecutivoFactura" placeholder="0" value="{{ isset($consecutivos) ? $consecutivos->consFactura : ''}}"></td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </div>
                                        <div class="tab-pane" id="tab-1-2">
                            
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th class="table-head">Movimiento</th>
                                                        <th class="table-des">Último Consecutivo</th>
                                                    </tr>
                                                    <tr>
                                                        <td class="table-head">Anticipo</td>
                                                        <td class="table-des"><input type="number" class="input-bordered" name="consecutivoAnticipo" id="consecutivoAnticipo" placeholder="0" value="{{ isset($consecutivos) ? $consecutivos->consAnticipo : ''}}"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table-head">Aplicación</td>
                                                     <td class="table-des"><input type="number" class="input-bordered" name="consecutivoAplicacion" id="consecutivoAplicacion" placeholder="0" value="{{ isset($consecutivos) ? $consecutivos->consAplicacion : ''}}"></td>
                                                    </tr>
                                                    {{-- <tr>
                                                        <td class="table-head">Devolución</td>
                                                     <td class="table-des"><input type="number" class="input-bordered" name="consecutivoDevolucion" id="consecutivoDevolucion" placeholder="0" value="{{ isset($consecutivos) ? $consecutivos->consDevolucion : ''}}"></td>
                                                    </tr> --}}
                                                    <tr>
                                                        <td class="table-head">Cobro</td>
                                                     <td class="table-des"><input type="number" class="input-bordered" name="consecutivoCobro" id="consecutivoCobro" placeholder="0" value="{{ isset($consecutivos) ? $consecutivos->consCobro : ''}}"></td>
                                                    </tr>
                                                    {{-- <tr>
                                                        <td class="table-head">Factura</td>
                                                     <td class="table-des"><input type="number" class="input-bordered" name="consecutivoFactura2" id="consecutivoFactura2" placeholder="0" value="{{ isset($consecutivos) ? $consecutivos->consFactura2 : ''}}"></td>
                                                    </tr> --}}
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane" id="tab-1-3">

                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr>
                                                        <th class="table-head">Movimiento</th>
                                                        <th class="table-des"> Último Consecutivo</th>
                                                    </tr>
                                                    <tr>
                                                        <td class="table-head">Transferencia</td>
                                                        <td class="table-des"><input type="number" class="input-bordered" name="consecutivoTransferencia" id="consecutivoTransferencia" placeholder="0" value="{{ isset($consecutivos) ? $consecutivos->consTransferencia : ''}}"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table-head">Egreso</td>
                                                     <td class="table-des"><input type="number" class="input-bordered" name="consecutivoEgreso" id="consecutivoEgreso" placeholder="0" value="{{ isset($consecutivos) ? $consecutivos->consEgreso : ''}}"></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="table-head">Ingreso</td>
                                                     <td class="table-des"><input type="number" class="input-bordered" name="consecutivoIngreso" id="consecutivoIngreso" placeholder="0" value="{{ isset($consecutivos) ? $consecutivos->consIngreso : ''}}"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div><!-- .col -->
                            </div>
                        </div>
                    </div>
                </div>
                    
                    </div>

                    <div class="row">
                        <div class="col-lg-12 text-center mb-5">
                            <input type="submit" value="Guardar" class="btn btn-outline btn-grad submitButton">
                        </div>
                    </div>
                </div><!-- tab-content -->
            </form>
        </div><!-- .col -->
    </main>

    @include('include.mensaje')

@endsection
@section('extra-js')
<script src="{{ asset('js/vendor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>
<script>

    $(document).ready(function() {

        $('#inputAviso').on('input', function() {
            if ($(this).val().length > 4) {
                $(this).val($(this).val().slice(0, 4));
            }
        });
        $('#inputAviso2').on('input', function() {
            if ($(this).val().length > 4) {
                $(this).val($(this).val().slice(0, 4));
            }
        });

        $("#selectmoneda").val('{{ isset($parametro) ? $parametro->monedaDefault : '' }}')
                    .trigger('change');

        $("#selectdias").val('{{ isset($parametro) ? $parametro->diasHabiles : '' }}')
                    .trigger('change');
    
        let editor;
        ClassicEditor
        .create(document.querySelector('#aviso1'), {
            language: 'es',
            simpleUpload: {
                uploadUrl: "{{route('image.upload', ['_token' => csrf_token() ])}}",
            }
        })
        .catch(error => {
            console.error(error);
        });

        ClassicEditor
        .create(document.querySelector('#aviso2'), {
            language: 'es',
            simpleUpload: {
                uploadUrl: "{{route('image.upload', ['_token' => csrf_token() ])}}",
            }
        })
        .catch(error => {
            console.error(error);
        });

        ClassicEditor
        .create(document.querySelector('#contrato'), {
            language: 'es',
            simpleUpload: {
                uploadUrl: "{{route('image.upload', ['_token' => csrf_token() ])}}",
            }
        })
        .catch(error => {
            console.error(error);
        });

        ClassicEditor
        .create(document.querySelector('#pagares'), {
            language: 'es',
            simpleUpload: {
                uploadUrl: "{{route('image.upload', ['_token' => csrf_token() ])}}",
            }
        })
        .catch(error => {
            console.error(error);
        });


        ClassicEditor
        .create(document.querySelector('#convenio'), {
            language: 'es',
            simpleUpload: {
                uploadUrl: "{{route('image.upload', ['_token' => csrf_token() ])}}",
            }
        })
        .catch(error => {
            console.error(error);
        });

        ClassicEditor
        .create(document.querySelector('#notificacion'), {
            language: 'es',
            simpleUpload: {
                uploadUrl: "{{route('image.upload', ['_token' => csrf_token() ])}}",
            }
        })
        .catch(error => {
            console.error(error);
        });

    const inicioFechaInput = document.getElementById('inicioFecha');
    const finFechaInput = document.getElementById('finFecha');

    // Agrega el evento de escucha al campo de fecha inicial
    inicioFechaInput.addEventListener('input', function() {
        // Obtiene las fechas como objetos Date
        const inicioFecha = new Date(this.value);
        const finFecha = new Date(finFechaInput.value);

        // Si la fecha inicial es mayor que la fecha final, actualiza la fecha final
        if (inicioFecha > finFecha) {
            finFechaInput.value = this.value;
        }

        // Restringe las fechas anteriores en el campo de fecha final
        finFechaInput.min = this.value;
    });

    // Agrega el evento de escucha al campo de fecha final
    finFechaInput.addEventListener('input', function() {
        // Obtiene las fechas como objetos Date
        const inicioFecha = new Date(inicioFechaInput.value);
        const finFecha = new Date(this.value);

        // Si la fecha final es menor que la fecha inicial, actualiza la fecha inicial
        if (finFecha < inicioFecha) {
            inicioFechaInput.value = this.value;
        }
    });


    
    
});

    
</script>
<script src="{{ asset('js/config/parametrosGen.js')}}">
    
</script>

@endsection
