@extends('master')

@section('title', 'Eliminar')

@section('content')
    <section>
        <h3 style="margin-left: 20%; padding:30px">Desea dar de baja a {{ $user_info['nombre'] . ' ' . $user_info['apellido'] }}?
        </h3>
        <div class="card col-md-4 no-padding hover-shadow mt-3 mb-5" style="margin-left: 25%">
            <div class="card-header bg-dark text-white">
                Información del trabajador
            </div>
            <div class="card-body" >
                <p class="card-text">Nombre: {{ $user_info['nombre'] . ' ' . $user_info['apellido'] }}.</p>
                <p class="card-text">Teléfono: +34 {{ $user_info['telefono'] }}.</p>
                <p class="card-text">Correo Electrónico: {{ $user_info['email'] }}.</p>
                <p class="card-text">NIF/CIF: {{ $user_info['NIF'] }}.</p>
            </div>
        </div>
        <div class="buttonBar mt-5">
            <a href="{{ route('deleteUser', ['id' => $user_info["user_id"]]) }}">
                <button class="btn btn-danger ml-2 mt-4" id="delete" name="delete">Eliminar</button>
            </a>
            <a href="{{ route('showUsers') }}" class="ml-2"><button class="btn btn-secondary  mt-4">Cancelar</button></a>
        </div>
    </section>
@endsection
