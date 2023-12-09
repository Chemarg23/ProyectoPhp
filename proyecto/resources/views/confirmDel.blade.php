
@extends('master')

@section('title', 'Borrar Tarea')

@section('content')

<section>
    <h3 style="margin-left: 20%; padding:30px">Desea borrar la tarea con la siguiente información?</h3>
    @include('layout.taskInfo')
<div class="buttonBar">
    <a href="{{ route('delete', ['id' => $task_info['task_id']]) }}" >
        <button class="btn btn-danger ml-2" id="delete" name="delete">Eliminar</button>
    </a>
    <a href="{{ route('inbox') }}" class="ml-2"><button class="btn btn-secondary">Cancelar</button></a>
</div>
</section>

@endsection
