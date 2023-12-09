<aside class="min-vh-100 col-md-2 text-white bg-light">
    <div class="container">
        <div class="row">
            <div class="p-3 bg-light col-md-12" style="height: 100%;">
                <a href="{{ route('inbox') }}"
                    class="d-flex align-items-center mb-md-0 me-md-auto link-dark text-decoration-none">
                    <svg class="bi me-2" width="40" height="32">
                        <use xlink:href="#bootstrap"></use>
                    </svg>
                    <span class="fs-4">
                        <p class="d-flex align-items-center">
                            <img src="https://www.corneclima.com/wp-content/uploads/2021/07/Logo-Corneclima.png"
                                alt="Bunglebuild Logo" height="30">
                            Bunglebuild
                        </p>
                    </span>
                </a>

                <hr style="border-color: black;">
                @if (Route::currentRouteName() != 'logIn' && SessionManager::isAdmin())
                    <div class="col-md-12">
                        <a href="{{ route('addTask') }}" class="btn btn-primary d-block mb-2">Añadir Tarea</a>

                        <hr style="border-color: black;" class="mb-3">
                    </div>
                @endif
                <div class="col-md-12">
                    <ul class="nav nav-pills flex-column mb-auto">

                        <li class="nav-item">
                            <a href={{ route('inbox') }} class="nav-link link-dark" aria-current="page">
                                <svg class="bi me-2" width="16" height="16">
                                    <use xlink:href="#home"></use>
                                </svg><i class="bi bi-house"></i>
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('inbox') . '?op_id=' . SessionManager::getUserId() }}"
                                class="nav-link link-dark">
                                <svg class="bi me-2" width="16" height="16">
                                    <use xlink:href="#speedometer2"></use>
                                </svg><i class="bi bi-person"></i>
                                Mis tareas
                            </a>
                        </li>
                        <li class="nav-item dropdown mt-auto ">
                            <a class="nav-link dropdown-toggle link-dark" href="#" id="navbarDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <svg class="bi me-2" width="16" height="16">
                                    <use xlink:href="#table"></use>
                                </svg>
                                <i class="bi bi-list-task"></i>
                                Estado
                            </a>
                            <div class="dropdown-menu text-small shadow" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('inbox') . '?estado=B' }}">En espera</a>
                                <a class="dropdown-item" href="{{ route('inbox') . '?estado=P' }}">Pendiente</a>
                                <a class="dropdown-item" href="{{ route('inbox') . '?estado=R' }}">Realizada</a>
                                <a class="dropdown-item" href="{{ route('inbox') . '?estado=C' }}">Cancelada</a>
                            </div>
                        </li>
                        @if (SessionManager::isAdmin())
                            <hr style="border-color: black;" class="mb-3">
                            <li>
                                <a href="{{ route('showUsers') }}" class="nav-link link-dark">
                                    <svg class="bi me-2" width="16" height="16">
                                        <use xlink:href="#speedometer2"></use>
                                    </svg><i class="bi bi-person"></i>
                                    Ver Usuarios
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('addUser') }}" class="nav-link link-dark">
                                    <svg class="bi me-2" width="16" height="16">
                                        <use xlink:href="#speedometer2"></use>
                                    </svg><i class="bi bi-person-add"></i>
                                    Añadir usuario

                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
                <div style=" position: absolute;
        bottom: 0; width: 75%">
                    <hr class="bg-dark text-black">
                    <div class="dropdown mt-auto">
                        <a href="#" class=" align-items-center text-decoration-none dropdown-toggle link-dark"
                            id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">

                            <strong>{{ SessionManager::getUserName() }}</strong>
                        </a>
                        <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
                            <li><a class="dropdown-item"
                                    href="{{ route('changeProfile', ['id' => SessionManager::getUserId()]) }}">Editar
                                    Perfil</a></li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="{{ route('logOut') }}">Sign out <i
                                        class="bi bi-box-arrow-right"></i></a></li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>
