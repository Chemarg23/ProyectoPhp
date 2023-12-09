<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="{{ asset('js/utils.js') }}"></script>
    <title>BungleBuild - Inciar Sesion</title>
</head>

<body>
    <br>
    <section class="gradient-custom section justify-content-center">
        <div class="container ">

            <div class="row d-flex justify-content-center align-items-center log-div">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card border-0 rounded-3 shadow-lg">

                        <div class="card-body-log p-4 text-center">

                            @if (isset($status) && $status === 'success')
                                <div class="alert alert-success mt-1">El correo ha sido enviado exitosamente
                                </div>
                            @else
                                <form action="{{ route('verifyMail') }}" method="POST">
                                    @csrf
                                    <div class="mb-md-5 mt-md-4">
                                        <h3 class="fw-bold mb-2 text-uppercase">Introduzca su correo</h3>

                                        <div class="mb-4 mt-5">
                                            <input type="text" placeholder="Email..." id="typeEmailX" name="mail"
                                                value="{{ filter_input(INPUT_POST, 'mail') }}"
                                                class="form-control form-control-lg" />
                                            @if (isset($errores['mail']))
                                                <div class="alert alert-danger mt-1">{{ $errores['mail'] }}</div>
                                            @endif
                                            @if (isset($status) && $status === 'failure')
                                                <div class="alert alert-danger mt-1">No existe su correo</div>
                                            @endif

                                        </div>
                                        @if (!isset($status) || $status !== 'success')
                                            <button class="btn btn-primary btn-lg px-5 mt-3" type="submit">Enviar
                                                correo</button>
                                        @endif
                            @endif
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
