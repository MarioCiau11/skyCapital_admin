@extends('layouts.layout')

@section('content')

<section class="bg-white">

    <div class="columns is-marginless is-centered is-mobile">
        <div class="column is-11">

        <div class="columns is-mobile">
            <div class="column">
            <h1 class="title is-3">Cliente: {{$Clientes->razonSocial}}</h1>
            </div>
        </div>
        <hr class="is-marginless">
        {{ Breadcrumbs::render('cat.clientes.show', $Clientes) }}

        <div class="columns mb-3">
            <div class="column">
                <div class="buttons">
                <ul class="btn-grp gutter-20px gutter-vr-20px">
                    <li> <a href="{{route('cat.clientes.edit', ['cliente' => Crypt::encrypt($Clientes->idCliente)])}}" class="badge badge-outline badge-info">Editar Cliente</a></li>
                    <li><form id="delete-form" action="{{ route('cat.clientes.destroy', ['cliente' => Crypt::encrypt($Clientes->idCliente)]) }}" method="POST">
                        @method('DELETE')
                        @csrf
                        @if ($Clientes->estatus != 0)
                            <button id="button-delete" type="button" class="badge badge-outline badge-danger delete2">Baja</button> 
                        @endif
                        </form></li>
                </ul>
                </div>
            </div>
        </div>
            <table class="table table is-bordered is-striped is-hoverable is-fullwidth">
                <tbody>

                    <tr>
                        <th>Datos Generales</th>
                        <th>&nbsp;</th>
                    </tr>

                    <tr>
                        <td>Clave</td>
                        <td>{{ $Clientes->clave }}</td>
                    </tr>
                    <tr>
                        <td>Tipo de Persona</td>
                        <td>{{ $Clientes->tipoPersona == 1 ? 'Persona Física' : 'Persona Moral' }}</td>
                    </tr>
                    <tr>
                        <td>Razón social</td>
                        <td>{{ $Clientes->razonSocial }}</td>
                    </tr>
                    <tr>
                        <td>RFC</td>
                        <td>{{ $Clientes->RFC }}</td>
                    </tr>
                    <tr>
                        <td>CURP</td>
                        <td>{{ $Clientes->CURP }}</td>
                    </tr>

                    <tr>
                        <th>Información de Representante Legal</th>
                        <th>&nbsp;</th>
                    </tr>

                    <tr>
                        <td>Nombres</td>
                        <td>{{ $Clientes->nombres }}</td>
                    </tr>
                    <tr>
                        <td>Apellido Paterno</td>
                        <td>{{ $Clientes->apellidoPaterno }}</td>
                    </tr>
                    <tr>
                        <td>Apellido Materno</td>
                        <td>{{ $Clientes->apellidoMaterno }}</td>
                    </tr>
                    <tr>
                        <td>Teléfono Celular</td>
                        <td>{{ $Clientes->telefonCelular }}</td>
                    </tr>
                    <tr>
                        <td>Correo Electrónico</td>
                        <td>{{ $Clientes->correoElectronico }}</td>
                    </tr>

                    <tr>
                        <th>Información del Cliente</th>
                        <th>&nbsp;</th>
                    </tr>

                    <tr>
                        <td>Dirección</td>
                        <td>{{ $Clientes->direccion }}</td>
                    </tr>
                    <tr>
                        <td>Entre Vialidades</td>
                        <td>{{ $Clientes->vialidades }}</td>
                    </tr>
                    <tr>
                        <td>Número Interior</td>
                        <td>{{ $Clientes->noInterior }}</td>
                    </tr>
                    <tr>
                        <td>Número Exterior</td>
                        <td>{{ $Clientes->noExterior }}</td>
                    </tr>
                    <tr>
                        <td>Colonia/Fraccionamiento</td>
                        <td>{{ $Clientes->coloniaFracc }}</td>
                    </tr>
                    <tr>
                        <td>Localidad/Municipio</td>
                        <td>{{ $Clientes->localidadMunicipio }}</td>
                    </tr>
                    <tr>
                        <td>Estado</td>
                        <td>{{ $Clientes->estado }}</td>
                    </tr>
                    <tr>
                        <td>País</td>
                        <td>{{ $Clientes->pais }}</td>
                    </tr>
                    <tr>
                        <td>Código Postal</td>
                        <td>{{ $Clientes->codigoPostal }}</td>
                    </tr>
                    <tr>
                        <td>Teléfono de Oficina 1</td>
                        <td>{{ $Clientes->telefono1 }}</td>
                    </tr>
                    <tr>
                        <td>Teléfono de Oficina 2</td>
                        <td>{{ $Clientes->telefono2 }}</td>
                    </tr>
                    <tr>
                        <td>Teléfono Celular</td>
                        <td>{{ $Clientes->telCelular }}</td>
                    </tr>
                    <tr>
                        <td>Contacto 1</td>
                        <td>{{ $Clientes->contacto1 }}</td>
                    </tr>
                    <tr>
                        <td>Correro Electrónico 1 (Principal)</td>
                        <td>{{ $Clientes->correoElectronico1 }}</td>
                    </tr>
                    <tr>
                        <td>Contacto 2</td>
                        <td>{{ $Clientes->contacto2 }}</td>
                    </tr>
                    <tr>
                        <td>Correo Electrónico 2</td>
                        <td>{{ $Clientes->correoElectronico2 }}</td>
                    </tr>
                    @php
                        // dd($Clientes->contraseñaPortal);
                    @endphp
                    <tr>
                        <td>Constraseña Portal</td>
                        <td><input style="border: 0" type="password" name="" id="" value="{{ $Clientes->contraseñaPortal != null ? \Crypt::decrypt($Clientes->contraseñaPortal) : '' }}" readonly></td>
                    </tr>
                    <tr>
                        <td>Observaciones/Comentarios</td>
                        <td>{{ $Clientes->observaciones }}</td>
                    </tr>
                    <tr>
                        <td>Ocupación</td>
                        <td>{{ $Clientes->ocupacion }}</td>
                    </tr>
                    <tr>
                        <td>Grupo</td>
                        <td>{{ $Clientes->grupo != null ? $Clientes->getGrupo->nombre : null }}</td>
                    </tr>
                    <tr>
                        <td>Categoría</td>
                        <td>{{ $Clientes->categoria != null ? $Clientes->getCategoria->nombre : null}}</td>
                    </tr>
                    <tr>
                        <td>Estatus</td>
                        <td>{{ ($Clientes->estatus) == 1 ? 'Alta':'Baja' }}</td>
                    </tr>

                    <tr>
                        <th>Condiciones Comerciales</th>
                        <th>&nbsp;</th>
                    </tr>

                    <tr>
                        <td>Condición de Pago</td>
                        <td>{{ $Clientes->condicionPago != null ? $Clientes->getCondiciones->nombreCondicion : null}}</td>
                    </tr>

                    <tr>
                        <th>Información Relacionada al Cliente</th>
                        <th>&nbsp;</th>
                    </tr>

                    <tr>
                        <td>Usuario que lo Registró </td>
                        <td>{{ $Clientes->user_id ? $Clientes->getUser->username : null }}</td>
                    </tr>
                    <tr>
                        <td>Fecha Creación</td>
                        <td>{{$Clientes->fechaAlta}}</td>
                    </tr>
                    <tr>
                        <td>Última Actualización</td>
                        <td>{{$Clientes->fechaCambio}}</td>
                    </tr>
                    <tr>
                        <td>Fecha de Baja</td>
                        <td>{{$Clientes->fechaBaja}}</td>
                    </tr>   

                    <tr>
                        <th>Datos digitales</th>
                        <th>&nbsp;</th>
                    </tr>
                    {{-- @php
                        dd($documentos);
                    @endphp --}}
                    @if (isset($documentos) && !empty($documentos))
                    
                        @foreach ($documentos as $key => $document)
                            @php
                                $key = $key + 1;
                                $srcDoc = "";
                                //nombre de los files 
                                $pathFileArray = explode('/', $document['path']);
                                $patch = explode('-', $document['path'])[0];
                                $longitudPath = count($pathFileArray);
                                $nameFileArray = explode('-', $pathFileArray[$longitudPath - 1]);
                                $nameFile = $nameFileArray[count($nameFileArray) - 1];

                                //nameFiles de los documentos Digitales
                                $FileArray = explode('/', $document['file']);
                                $longitudFile = count($FileArray);
                                $file = $FileArray[$longitudFile - 1];
                            @endphp
                            <tr>
                                <td>Nombre del Documento {{$key}}</td>
                                <td>{{ $nameFile }}</td>
                            </tr>
                            <tr>
                                <td>Nombre del Archivo {{$key}}</td>
                                <td>{{ $file }}
                                    <a href="{{route('descargar.doc', [$document->idFile])}}" class="ml-4 badge badge-light">Descargar</a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        </div>
    </div>
</section>    
@endsection
