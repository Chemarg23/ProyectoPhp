@extends('master')

@section('title', 'Borrar Tarea')

@section('content')

    <section>

        <h2 style="margin-left: 38%" class="mb-5">Cambia tu perfil</h2>
        <div class="container mt-1">
            <form action="{{ route('updateProfile', ['id' => SessionManager::getUserId()]) }}" method="POST"
                enctype="multipart/form-userInfo">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" id="nombre"
                                value="{{ $userInfo['nombre'] ?? filter_input(INPUT_POST, 'nombre') }}"
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
                                value="{{ $userInfo['apellido'] ?? filter_input(INPUT_POST, 'apellido') }}"
                                class="form-control">
                            @if (isset($errores['apellido']))
                                <div class="alert alert-danger mt-1">{{ $errores['apellido'] }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="dni" class="form-label">NIF</label>
                            <input type="text" name="dni" id="dni"
                                value="{{ $userInfo['NIF'] ?? filter_input(INPUT_POST, 'dni') }}" class="form-control">
                            @if (isset($errores['dni']))
                                <div class="alert alert-danger mt-1">{{ $errores['dni'] }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="tel" class="form-label">Teléfono</label>
                            <input type="text" name="tel" id="tel"
                                value="{{ $userInfo['telefono'] ?? filter_input(INPUT_POST, 'tel') }}" class="form-control">
                            @if (isset($errores['tel']))
                                <div class="alert alert-danger mt-1">{{ $errores['tel'] }}</div>
                            @endif
                        </div>
                    </div>


                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="mail" class="form-label">Correo electrónico</label>
                            <input type="text" name="mail" id="mail"
                                value="{{ $userInfo['email'] ?? filter_input(INPUT_POST, 'mail') }}" class="form-control">
                            @if (isset($errores['mail']))
                                <div class="alert alert-danger mt-1">{{ $errores['mail'] }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mt-4 d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary col-md-4" value="add">Cambiar</button>
                        </div>
                    </div>
            </form>
        </div>
    </section>

@endsection
