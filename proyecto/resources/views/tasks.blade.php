@extends('master')

@section('title', 'inbox')

@section('content')

    <div class="container">
        <div class="row">
          
            <div class="col-md-11 task-container">
                <div class="container-fluid">
                    <div class="row justify-content-center p-1 mb-4">
                        <form class="position-relative w-100" action="{{ route('inbox') }}" method="GET">
                            @csrf
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="bi bi-search text-center"></i>
                                    </div>
                                </div>
                                <input type="text" name="descripcion"
                                    class="form-control backdrop-blur-sm bg-white-20 border-gray-100"
                                    placeholder="Search...">
                            </div>
                        </form>
                    </div>
                </div>

                <div class="container">
                    <div class="table-responsive">

                        <table class="table table-bordered table-striped">
                            <thead class="bg-dark">
                                <tr>
                                    <th scope="col" class=" text-sm font-normal text-white bg-dark">
                                        Descripción
                                    </th>

                                    <th scope="col" class=" text-sm font-normal text-white bg-dark">
                                        Operario al cargo
                                    </th>

                                    <th scope="col" class=" text-sm font-normal text-white bg-dark">
                                        Persona de contacto
                                    </th>

                                    <th scope="col" class=" text-sm font-normal text-white bg-dark">
                                        Correo electrónico
                                    </th>
                                    <th scope="col" class=" text-sm font-normal text-white bg-dark">
                                        Población
                                    </th>
                                    <th scope="col" class=" text-sm font-normal text-white bg-dark">
                                        Estado
                                    </th>
                                    <th scope="col" class=" text-sm font-normal text-white bg-dark">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach ($tareas as $tarea)
                                    <tr class="">
                                        <td class="text-break">
                                            {{ $tarea['descripcion'] }}
                                        </td>
                                        <td class="text-break">
                                            {{ $tarea['op_name'] . ' ' . $tarea['op_ap'] }}
                                        </td>
                                        <td class="text-break">{{ $tarea['nombre'] . ' ' . $tarea['apellido'] }}</td>

                                        <td class="text-break">
                                            {{ $tarea['email'] }}
                                        </td>
                                        <td class="text-break">
                                            {{ $tarea['poblacion'] }}
                                        </td>
                                        <td class="text-break">
                                            @php
                                                $estados = [
                                                    'B' => 'En espera',
                                                    'P' => 'Pendiente',
                                                    'R' => 'Realizada',
                                                    'C' => 'Cancelada',
                                                ];
                                            @endphp
                                            {{ $estados[$tarea['estado']] }}
                                        </td>
                                        <td class="">
                                            <div class="d-flex  gap-4">

                                                @if (!SessionManager::isAdmin() && SessionManager::getUserId() == $tarea['op_id'] && $tarea['estado'] == 'P')
                                                    <a href="{{ route('finishForm',['id' => $tarea['task_id']]) }}">
                                                        <button class="btn btn-warning col-md-12" id="finish"
                                                            name="finish" value="{{ $tarea['task_id'] }}"><i
                                                                class="bi bi-pencil-square"></i></button>
                                                    </a>
                                                @endif
                                                @if (SessionManager::isAdmin())
                                                    @if ($tarea['estado'] === 'B')
                                                        <a href="{{ route('acceptTask', ['id' => $tarea['task_id']]) }}">
                                                            <button class="btn btn-success col-md-12" name="accept"> <i
                                                                    class="bi bi-check"></i></button>
                                                        </a>
                                                        <a href="{{ route('confirmDelete', ['id' => $tarea['task_id']]) }}">
                                                            <button class="btn btn-danger col-md-12" id="delete"
                                                                name="delete" value="{{ $tarea['task_id'] }}"> <i
                                                                    class="bi bi-x"></i></button>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('updateForm', ['id' => $tarea['task_id']]) }}">
                                                            <button class="btn btn-warning col-md-12"> <i
                                                                    class="bi bi-pencil-square"></i>
                                                            </button></a>
                                                        <a
                                                            href="{{ route('confirmDelete', ['id' => $tarea['task_id']]) }}">
                                                            <button class="btn btn-danger col-md-12" id="delete"
                                                                name="delete" value="{{ $tarea['task_id'] }}"><i
                                                                    class="bi bi-trash"></i></button>
                                                        </a>
                                                    @endif
                                                @endif
                                                <a href="{{ route('show', ['id' => $tarea['task_id']]) }}">
                                                    <button class="btn btn-info"><i class="bi bi-eye"></i></button>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class=" d-flex align-items-center justify-content-center">
        <div class="paginacion">
            {!! $numPags !!}
        </div>
    </div>
    </section>
    
@endsection
