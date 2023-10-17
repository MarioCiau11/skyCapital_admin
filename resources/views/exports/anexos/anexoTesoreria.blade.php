@extends('layouts.layout')
@section('extra-css')
    <link rel="stylesheet" href="{{ asset('css/anexos.css') }}">
@endsection

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <div class="col-lg-12 container-anexo">
        <div class="title col-lg-12 col-sm-12">
            <h2 class="text-black">Documentos digitales</h2>
        </div>
        <div class="col-lg-12 col-sm-12 n-contract">
            <h5 class="text-black">{{$tesoreria->movimiento.'-'.$tesoreria->folioMov}}</h5>
        </div>
        <div class="gallery">
            {{-- @php
                dd($tesoreria, $docsTes,$lastId);
            @endphp --}}
            @foreach ($docsTes as $document)
                <?php
                    $srcDoc = "";
                    // nombre de los files
                    $pathFileArray = explode('/', $document['path']);
                    $patch = explode('-', $document['path'])[0];
                    $longitudPath = count($pathFileArray);
                    $nameFileArray = explode('-', $pathFileArray[$longitudPath - 1]);
                    $nameFile = $nameFileArray[count($nameFileArray) - 1];
                        
                    //nameFiles de los fileos digitales
                    $FileArray = explode('/', $document['file']);
                    $longitudFile = count($FileArray);
                    $file = $FileArray[$longitudFile - 1];
                // dd($file);
                ?>
                <div class="form-group col-lg-4 col-sm-12">
                    <div class="col-lg-12">
                        <div class="field_wrapper_edit">
                            <div class='col-lg-12'>
                                <label class='negrita'><span>Nombre del archivo</span></label>
                                <input type="text" class="form-control" disabled name="nombreActualArchivo" value="{{$file}}">
                            </div>
                            <div class="row doc-repair">
                                <div class="col-lg-12">
                                    <a href="{{route('descargar.anexo', [$document->idFile])}}" class="actions badge badge-light">Descargar</a>  
                                    <button class="actions badge badge-danger"  onclick="eliminarAnexo('{{ $document['idFile'] }}')"> Eliminar </button>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <form class="col-lg-12 form-upload" method="POST" action="{{ route('proc.tesoreria.createAnexo', ['id' => $lastId]) }}" accept-charset="UTF-8" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <div class="col-lg-12" style="">
                        <div class="field_wrapper" style="">
                            <div class="col-lg-12">
                                <label class='field-label'>Subir nuevo archivo</label>
                            </div>
                            <div class='col-lg-12 doc-repair upload-align'>
                                <input type="file" class="col-lg-12 input-file inputfile-5"
                                    data-multiple-caption="{count} archivos seleccionados" name="file[]"
                                    id="file" accept=".doc, .pdf, .txt, .docx" multiple>
                                <label for="file">
                                    <figure class="upload-doc">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile"
                                        width="20" height="17" viewBox="0 0 20 17">
                                            <path
                                                d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z">
                                            </path>
                                        </svg>
                                    </figure>
                                </label>
                                <ul id="listaDeArchivos"></ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">SUBIR</button>
                    </div>
                </div>
            </form>
        </div>
        <script src="{{ asset('js\procesos\anexos.js') }}"></script>
    </div>
@endsection

