@extends('master')

@section('title', 'Añadir')

@section('content')
<div id="searchForm" class="container mt-1">
    <form action="{{ route('validate') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12 text-center mb-3">
                <h2>Añada una tarea</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" id="nombre" value="{{ filter_input(INPUT_POST, 'nombre') }}"
                        class="form-control">
                    @if (isset($errores['nombre']))
                        <div class="alert alert-danger mt-1">{{ $errores['nombre'] }}</div>
                    @endif
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" name="apellido" id="apellido"
                        value="{{ filter_input(INPUT_POST, 'apellido') }}" class="form-control">
                    @if (isset($errores['apellido']))
                        <div class="alert alert-danger mt-1">{{ $errores['apellido'] }}</div>
                    @endif
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <label for="dni" class="form-label">NIF</label>
                    <input type="text" name="dni" id="dni" value="{{ filter_input(INPUT_POST, 'dni') }}"
                        class="form-control">
                    @if (isset($errores['dni']))
                        <div class="alert alert-danger mt-1">{{ $errores['dni'] }}</div>
                    @endif
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <label for="tel" class="form-label">Teléfono</label>
                    <input type="text" name="tel" id="tel" value="{{ filter_input(INPUT_POST, 'tel') }}"
                        class="form-control">
                    @if (isset($errores['tel']))
                        <div class="alert alert-danger mt-1">{{ $errores['tel'] }}</div>
                    @endif
                </div>
            </div>


            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <label for="mail" class="form-label">Correo electrónico</label>
                    <input type="text" name="mail" id="mail" value="{{ filter_input(INPUT_POST, 'mail') }}"
                        class="form-control">
                    @if (isset($errores['mail']))
                        <div class="alert alert-danger mt-1">{{ $errores['mail'] }}</div>
                    @endif
                </div>
            </div>


            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <label for="fecha" class="form-label">Fecha de Realización</label>
                    <input type="date" name="fecha" id="fecha" value="{{ filter_input(INPUT_POST, 'fecha') }}"
                        class="form-control">
                    @if (isset($errores['fecha']))
                        <div class="alert alert-danger mt-1">{{ $errores['fecha'] }}</div>
                    @endif
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label for="desc" class="form-label">Descripción</label>
                    <input type="text" name="desc" id="desc" value="{{ filter_input(INPUT_POST, 'desc') }}"
                        class="form-control">
                    @if (isset($errores['desc']))
                        <div class="alert alert-danger mt-1">{{ $errores['desc'] }}</div>
                    @endif
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="form-group">
                    <label for="provincia" class="form-label">Provincia</label>
                    @php
                        echo CreaSelect('provincia', getProvincias(), filter_input(INPUT_POST, 'provincia'), 'form-select');
                    @endphp
                    @if (isset($errores['provincia']))
                        <div class="alert alert-danger mt-1">{{ $errores['provincia'] }}</div>
                    @endif
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="form-group">
                    <label for="poblacion" class="form-label">Ciudad</label>
                    <input type="text" name="poblacion" id="poblacion"
                        value="{{ filter_input(INPUT_POST, 'poblacion') }}" class="form-control">
                    @if (isset($errores['poblacion']))
                        <div class="alert alert-danger mt-1">{{ $errores['poblacion'] }}</div>
                    @endif
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="form-group">
                    <label for="codigo-postal" class="form-label">ZIP / Codigo Postal</label>
                    <input type="text" name="codigo-postal" id="codigo-postal"
                        value="{{ filter_input(INPUT_POST, 'codigo-postal') }}" class="form-control">
                    @if (isset($errores['codigo-postal']))
                        <div class="alert alert-danger mt-1">{{ $errores['codigo-postal'] }}</div>
                    @endif
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label for="direccion" class="form-label">Dirección:</label>
                    <input type="text" name="direccion" id="direccion"
                        value="{{ filter_input(INPUT_POST, 'direccion') }}" class="form-control">
                    @if (isset($errores['direccion']))
                        <div class="alert alert-danger mt-1">{{ $errores['direccion'] }}</div>
                    @endif
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label for="estado" class="form-label">Estado</label>
                    @php
                        $estados = [
                            '' => 'Selecione el estado',
                            'B' => 'En espera de aprobación',
                            'P' => 'Pendiente',
                            'R' => 'Realizada',
                            'C' => 'Cancelada',
                        ];
                        echo CreaSelect('estado', $estados, filter_input(INPUT_POST, 'estado'), 'form-select');
                    @endphp
                    @if (isset($errores['estado']))
                        <div class="alert alert-danger mt-1">{{ $errores['estado'] }}</div>
                    @endif
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label for="operador" class="form-label">Operador</label>
                    @php
                        echo CreaSelect('operador', getOperators(), filter_input(INPUT_POST, 'operador'), 'form-select');
                    @endphp
                    @if (isset($errores['operador']))
                        <div class="alert alert-danger mt-1">{{ $errores['operador'] }}</div>
                    @endif
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
                        <input type="file" name="txt" id="txt" class="form-control" value="" multiple>
                        @if (isset($errores['txt']))
                            <div class="alert alert-danger mt-1">{{ $errores['txt'] }}</div>
                        @endif
                    </div>
                </div>
            <div class="col-md-12 mb-3">
                <div class="form-group">
                    <label for="comentario" class="form-label">Comentarios:</label>
                    <textarea class="form-control" name="comentario" id="miTextarea" rows="3" cols="150">{{ filter_input(INPUT_POST,'comentario') }}</textarea>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mt-4 d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary col-md-4" value="add">Añadir
                        Tarea</button>
                </div>
            </div>
        </div>
    </form>

    <a href="{{ route('inbox') }}" class="return"><button class="btn btn-secondary">Volver</button></a>
</div>


@endsection
