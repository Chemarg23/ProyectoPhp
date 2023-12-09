
@extends('master')

@section('title', 'OverView')

@section('content')

<section>
    @include('layout.taskInfo')
    <div class="buttonBar">
        <a href="{{ route('inbox') }}" class="return"><button class="btn btn-secondary">Volver</button></a>
        @if (SessionManager::isAdmin())
            <a href="{{ route('updateForm',['id' => $task_info['task_id']]) }}" class="edit"><button class="btn btn-warning">Editar</button></a>
            <a href="{{ route('confirmDelete', ['id' => $task_info['task_id']]) }}" class="delete"><button
                    class="btn btn-danger">Eliminar</button></a>
        @elseif($task_info['op_id'] == SessionManager::getUserId() && $task_info['estado'] == "P")
            <a href="{{ route('finishForm', ['id' => $task_info['task_id']]) }}" class="edit"><button class="btn btn-warning">Finalizar</button></a>
        @endif
    </div>
</section>
@endsection
