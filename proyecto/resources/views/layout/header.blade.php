    <header>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark">
            <div class="container d-flex justify-content-between align-items-center">
                <p class="navbar-brand d-flex align-items-center">
                    <img src="https://www.corneclima.com/wp-content/uploads/2021/07/Logo-Corneclima.png"
                        alt="Bunglebuild Logo" height="30">
                    Bunglebuild
                </p>

                <div class="right">
                    @if (Route::currentRouteName() != 'logIn' && SessionManager::isAdmin())
                        <a href="{{ route('addTask') }}"><button class="btn btn-primary">Añadir Tarea</button></a>
                    @endif
                </div>
            </div>
        </nav>
    </header>
