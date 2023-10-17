@extends('layouts.layout')
@section('title', $Empresa->nombreEmpresa)

@section('content')

    <main class="ath-body">
        <section class="bg-white">

            <div class="columns is-marginless is-centered is-mobile">
                <div class="column is-11">

                    <div class="columns is-mobile">
                        <div class="column">
                            <h1 class="title is-3">Empresa: {{ $Empresa->nombreEmpresa }}</h1>
                        </div>
                    </div>
                    <hr class="is-marginless">
                    {{ Breadcrumbs::render('cat.empresas.show', $Empresa) }}

                    <div class="columns">
                        <div class="column">
                            <div class="buttons">
                                <ul class="btn-grp gutter-20px gutter-vr-20px">
                                    <li> <a href="{{ route('cat.empresas.edit', Crypt::encrypt($Empresa->idEmpresa)) }}"
                                            class="badge badge-outline badge-info">Editar Empresa</a></li>
                                    @if($Empresa->estatus == 1)
                                    <li>
                                        <form id="delete-form"
                                            action="{{ route('cat.empresas.destroy', Crypt::encrypt($Empresa->idEmpresa)) }}"
                                            method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button id="button-delete" type="button"
                                                class="badge badge-outline badge-danger delete2">Eliminar Empresa</button>
                                        </form>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <table class="table table is-bordered is-striped is-hoverable is-fullwidth">
                        <tbody>
                            <tr>
                                <td>Clave</td>
                                <td>{{ $Empresa->clave }}</td>
                            </tr>
                            <tr>
                                <td>Nombre</td>
                                <td>{{ $Empresa->nombreEmpresa }}</td>
                            </tr>
                            <tr>
                                <td>Nombre corto</td>
                                <td>{{ $Empresa->nombreCorto }}</td>
                            </tr>
                            <tr>
                                <td>Descripción</td>
                                <td>{{ $Empresa->descripcion }}</td>
                            </tr>
                            <tr>
                                <td>Estatus</td>
                                <td>{{ $Empresa->estatus == 1 ? 'Alta' : 'Baja' }}</td>
                            </tr>
                            <tr>
                                <td>Usuario que lo registró</td>
                                <td>{{ $Empresa->getUser != null ? $Empresa->getUser->user_name : null }}</td>
                            </tr>
                            <tr>
                                <td>Dirección</td>
                                <td>{{ $Empresa->direccion }}</td>
                            </tr>
                            <tr>
                                <td>Colonia</td>
                                <td>{{ $Empresa->colonia }}</td>
                            </tr>
                            <tr>
                                <td>Código Postal</td>
                                <td>{{ $Empresa->codigoPostal }}</td>
                            </tr>
                            <tr>
                                <td>Ciudad</td>
                                <td>{{ $Empresa->ciudad }}</td>
                            </tr>
                            <tr>
                                <td>Estado</td>
                                <td>{{ $Empresa->estado }}</td>
                            </tr>
                            <tr>
                                <td>País</td>
                                <td>{{ $Empresa->pais }}</td>
                            </tr>
                            <tr>
                                <td>Teléfono 1</td>
                                <td>{{ $Empresa->telefono1 }}</td>
                            </tr>
                            <tr>
                                <td>Teléfono 2</td>
                                <td>{{ $Empresa->telefono2 }}</td>
                            </tr>
                            <tr>
                                <td>Correo</td>
                                <td>{{ $Empresa->correoElectronico }}</td>
                            </tr>
                            <tr>
                                <td>RFC</td>
                                <td>{{ $Empresa->RFC }}</td>
                            </tr>
                            <tr>
                                <td>Régimen Fiscal</td>
                                <td>{{ $Empresa->regimenFiscal }}</td>
                            </tr>
                            <tr>
                                <td>Registro Patronal</td>
                                <td>{{ $Empresa->registroPatronal }}</td>
                            </tr>
                            {{-- <tr>
                                <td>Ruta Llave</td>
                                <td>{{ $Empresa->rutaLlave }}</td>
                            </tr>
                            <tr>
                                <td>Ruta Certificado</td>
                                <td>{{ $Empresa->rutaCertificado }}</td>
                            </tr> --}}
                            <tr>
                                <td>Ruta Documentos</td>
                                <td>{{ $Empresa->rutaDocumentos }}</td>
                            </tr>
                            <tr>
                                <td>Fecha creación</td>
                                <td>{{ $Empresa->fechaAlta }}</td>
                            </tr>
                            <tr>
                                <td>Última actualización</td>
                                <td>{{ $Empresa->fechaCambio }}</td>
                            </tr>
                            <tr>
                                <td>Fecha de Baja</td>
                                <td>{{ $Empresa->fechaBaja }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </section>
    </main>



@endsection
