@extends('master')

@section('title', 'Finalizar')

@section('content')

    <section class="mt-3">
        <h3 style="margin-left: 39%; padding:30px">Concluir tarea</h3>
        @include('layout.taskInfo')
        <div class="container mt-2">
            <form action="{{ route('finishTask', ['id' => $task_info['task_id']]) }}" enctype="multipart/form-data"
                method="POST">
                @csrf
                <div class="row mt-5">
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="estado" class="form-label">Estado</label>
                            @php
                                echo CreaSelect('estado', ['' => 'Seleccione el estado', 'R' => 'Realizada', 'C' => 'Cancelada'], filter_input(INPUT_POST, 'estado'), 'form-select');
                            @endphp
                            @if (isset($errores['estado']))
                                <div class="alert alert-danger mt-1">{{ $errores['estado'] }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="imagen" class="form-label">Imagenes</label>
                            <input type="file" name="imagen" id="imagen" class="form-control" multiple>
                            @if (isset($errores['imagen']))
                                <div class="alert alert-danger mt-1">{{ $errores['imagen'] }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="txt" class="form-label">Documento de texto</label>
                            <input type="file" name="txt" id="txt" class="form-control" multiple>
                            @if (isset($errores['txt']))
                                <div class="alert alert-danger mt-1">{{ $errores['txt'] }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="comentario" class="form-label">Comentarios:</label>
                            <textarea class="form-control" name="comentario_post" id="miTextarea" rows="3" cols="150">{{ filter_input(INPUT_POST, 'comentario_post') }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mt-4 d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary col-md-4" value="add">Concluir</button>
                        </div>
                    </div>
                </div>
        </div>
        </form>

        <div class="buttonBar">
            <a href="{{ route('inbox') }}" class="ml-2"><button class="btn btn-secondary">Volver</button></a>
        </div>
    </section>
@endsection
