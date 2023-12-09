@extends('master')

@section('title', 'Borrar Tarea')

@section('content')

    <section>

        <h2 style="margin-left: 38%" class="mb-5">Añada un usuario</h2>
        <div class="container mt-1">
            <form action="{{ route('addingUser') }}" method="POST"
                enctype="multipart/form-userInfo">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" id="nombre"
                                value="{{  filter_input(INPUT_POST, 'nombre') }}"
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
                                value="{{   filter_input(INPUT_POST, 'apellido') }}"
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
                                value="{{ filter_input(INPUT_POST, 'dni') }}" class="form-control">
                            @if (isset($errores['dni']))
                                <div class="alert alert-danger mt-1">{{ $errores['dni'] }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="tel" class="form-label">Teléfono</label>
                            <input type="text" name="tel" id="tel"
                                value="{{   filter_input(INPUT_POST, 'tel') }}" class="form-control">
                            @if (isset($errores['tel']))
                                <div class="alert alert-danger mt-1">{{ $errores['tel'] }}</div>
                            @endif
                        </div>
                    </div>


                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="mail" class="form-label">Correo electrónico</label>
                            <input type="text" name="mail" id="mail"
                                value="{{  filter_input(INPUT_POST, 'mail') }}" class="form-control">
                            @if (isset($errores['mail']))
                                <div class="alert alert-danger mt-1">{{ $errores['mail'] }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="text" name="password" id="password"
                                value="{{  filter_input(INPUT_POST, 'password') }}" class="form-control">
                            @if (isset($errores['password']))
                                <div class="alert alert-danger mt-1">{{ $errores['password'] }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-group">
                            <label for="rol" class="form-label">Rol</label>
                           @php
                             echo CreaSelect('rol', ['' => 'Seleccione su rol', '1' => 'Administrador', '0' => 'Operario'], filter_input(INPUT_POST, 'rol') ?? '', 'form-select')
                           @endphp
                            @if (isset($errores['rol']))
                                <div class="alert alert-danger mt-1">{{ $errores['rol'] }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mt-4 d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary col-md-4" value="add">Añadir</button>
                        </div>
                    </div>
                    
            </form>
        </div>
    </section>

@endsection
