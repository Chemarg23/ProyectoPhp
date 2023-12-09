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
                    
                    <div class="card-body-log p-5 text-center">
                       
            
                        <form action="{{ route('validateCredentials') }}" method="POST">
                            @csrf
                            <div class="mb-md-5 mt-md-4">
                                <h2 class="fw-bold mb-2 text-uppercase">Iniciar Sesión</h2>
                                <p class="text-muted mb-5">Introduzca su usuario y contraseña</p>

                                <div class="mb-4">
                                    <label for="typeEmailX" class="form-label">Email</label>
                                    <input type="text" id="typeEmailX" name="mail" value="{{ filter_input(INPUT_POST, 'mail') }}"
                                        class="form-control form-control-lg" />
                                    @if (isset($errores['mail']))
                                        <div class="alert alert-danger mt-1">{{ $errores['mail'] }}</div>
                                    @endif
                                </div>

                                <div class="mb-4">
                                    <label for="typePasswordX" class="form-label">Contraseña</label>
                                    <input type="password" id="typePasswordX" name="password"
                                        value="{{ filter_input(INPUT_POST, 'password') }}" class="form-control form-control-lg" />
                                    @if (isset($errores['password']))
                                        <div class="alert alert-danger mt-1">{{ $errores['password'] }}</div>
                                    @endif
                                </div>

                                <div class="form-check form-check-inline mb-5">
                                    <input class="form-check-input" type="checkbox" name="remember" id="inlineCheckbox1" value="option1">
                                    <label class="form-check-label"  for="inlineCheckbox1">Recuerdame</label>
                                  </div><br>
                                <button class="btn btn-primary btn-lg px-5" type="submit">Iniciar Sesión</button><br><br>
                                <a class="mt-2" href="{{ route("forgottenPass") }}"><i>Has olvidado tu contraseña?</i></a>
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
