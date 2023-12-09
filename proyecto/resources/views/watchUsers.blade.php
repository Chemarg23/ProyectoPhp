@php
    $roles = [
        '1' => 'administrador',
        '0' => 'operador',
    ];
@endphp
@extends('master')

@section('title', 'inbox')

@section('content')
    <section>
        <div class="container">
            <div class="row">



                <div class="container">
                    <div class="table-responsive">


                        <table class="table table-bordered table-striped">
                            <thead class="bg-dark">
                                <tr>
                                    <th scope="col" class=" text-sm font-normal text-white bg-dark">
                                        Nombre
                                    </th>

                                    <th scope="col" class=" text-sm font-normal text-white bg-dark">
                                        Apellido
                                    </th>

                                    <th scope="col" class=" text-sm font-normal text-white bg-dark">
                                        NIF
                                    </th>

                                    <th scope="col" class=" text-sm font-normal text-white bg-dark">
                                        Email
                                    </th>
                                    <th scope="col" class=" text-sm font-normal text-white bg-dark">
                                        Teléfono
                                    </th>
                                    <th scope="col" class=" text-sm font-normal text-white bg-dark">
                                        Rol
                                    </th>
                                    <th scope="col" class=" text-sm font-normal text-white bg-dark">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach ($user_info as $user)
                                    <tr class="">
                                        <td class="text-break">
                                            {{ $user['nombre'] }}
                                        </td>
                                        <td class="text-break">
                                            {{ $user['apellido'] }}
                                        </td>
                                        <td class="text-break">{{ $user['NIF'] }}</td>

                                        <td class="text-break">
                                            {{ $user['email'] }}
                                        </td>
                                        <td class="text-break">
                                            {{ $user['telefono'] }}
                                        </td>
                                        <td class="text-break">
                                            {{ $roles[$user['rol']] }}
                                        </td>
                                        <td class="">
                                            <div class="d-flex  gap-4">
                                                @if ($user['rol'] != 1)
                                                    <a href="{{ route('userDeletionForm', ['id' => $user['user_id']]) }}">
                                                        <button class="btn btn-outline-danger col-md-12" id="delete"
                                                            name="delete" value="{{ $user['user_id'] }}"> Dar de
                                                            baja</button>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class=" d-flex align-items-center justify-content-center">
                            <div class="paginacion">
                                {!! $numPags !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </section>

@endsection
