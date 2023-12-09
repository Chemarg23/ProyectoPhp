@php
    $estados = [
        'B' => 'En espera',
        'P' => 'Pendiente',
        'R' => 'Realizada',
        'C' => 'Cancelada',
    ];
    $textDocs = array_filter(explode('|', $task_info['txt_url']));
    $img_path = array_filter(explode('|', $task_info['img_url']));
@endphp
<div class="row">
    <div class="card col-md-4 no-padding hover-shadow">
        <div class="card-header bg-dark text-white">
            Información de contacto
        </div>
        <div class="card-body">
            <p class="card-text">Nombre: {{ $task_info['nombre'] . ' ' . $task_info['apellido'] }}.</p>
            <p class="card-text">Teléfono: +34 {{ $task_info['telefono'] }}.</p>
            <p class="card-text">Correo Electrónico: {{ $task_info['email'] }}.</p>
            <p class="card-text">NIF/CIF: {{ $task_info['NIF'] }}.</p>
            <p class="card-text">Operario al cargo: {{ $task_info['name'] . ' ' . $task_info['surname'] }}.</p>
        </div>
    </div>

    <div class="card col-md-4 no-padding hover-shadow">
        <div class="card-header bg-dark text-white ">
            Información de la Tarea
        </div>
        <div class="card-body">
            <p class="card-text">Descripción: {{ $task_info['descripcion'] }}.</p>
            <p class="card-text">Fecha de realización: {{ $task_info['fecha_realizacion'] }}.</p>
            <p class="card-text">Localización: {{ $task_info['poblacion'] . ' , ' . $task_info['direccion'] }}.</p>
            <p class="card-text">Código Postal: {{ $task_info['codigo_postal'] }}.</p>
            <p class="card-text">Estado: {{ $estados[$task_info['estado']] }}.</p>
            <p class="card-text">Comentarios sobre la tarea: {{ $task_info['comentarios_pre'] }}</p>
            <p class="card-text">Comentarios del operario: {{ $task_info['comentarios_post'] }}</p>
                @foreach ($textDocs as $doc)
                    <a href="{{ asset($doc) }}" target="_blank" style="text-decoration: inherit"><i
                            class="bi bi-file-earmark-pdf"></i>{{ substr(basename($doc), 11) }}</a> <br>
                @endforeach
            <div class="card-img mt-3 ml-2">
                @foreach ($img_path as $imagen)
                    <a class="link-offset-2 link-underline link-underline-opacity-25" href="{{ asset($imagen) }}"
                        target="_blank"><img src="{{ asset($imagen) }}" alt="" width="35px"
                            height="50px"></a>
                @endforeach
            </div>
        </div>
    </div>

</div>
