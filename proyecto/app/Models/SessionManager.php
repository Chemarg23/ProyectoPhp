<?php

namespace App\Models;

use App\Models\User;
use GestorErrores;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use TaskDatabase;
use Illuminate\Http\Request;


/**
 * Clase SessionManager para gestionar sesiones de usuario.
 */
class SessionManager
{
    /**
     * Iniciar una sesión con la información del usuario.
     *
     * @param int $userId ID del usuario.
     * @param string $username Nombre de usuario.
     * @param int $rol Rol del usuario.
     */
    public static function startSession($userId, $username, $rol)
    {
        session_start();
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['rol'] = $rol;
    }

    /**
     * Verificar si el usuario está actualmente autenticado.
     *
     * @return bool True si el usuario está autenticado, false de lo contrario.
     */
    public static function isLoggedIn()
    {
        self::checkSessionStatus();
        return isset($_SESSION['user_id']);
    }

    /**
     * Verificar si el usuario tiene privilegios de administrador.
     *
     * @return bool True si el usuario es administrador, false de lo contrario.
     */
    public static function isAdmin()
    {
        self::checkSessionStatus();
        return isset($_SESSION['rol']) && $_SESSION['rol'] == 1;
    }

    /**
     * Obtener el nombre de usuario actualmente autenticado.
     *
     * @return string|null Nombre de usuario o null si no hay sesión.
     */
    public static function getUsername()
    {
        self::checkSessionStatus();
        return isset($_SESSION['username']) ? $_SESSION['username'] : null;
    }

    /**
     * Obtener el ID del usuario actualmente autenticado.
     *
     * @return int|null ID de usuario o null si no hay sesión.
     */
    public static function getUserId()
    {
        self::checkSessionStatus();
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }

    /**
     * Finalizar la sesión actual del usuario.
     */
    public static function endSession()
    {
        self::checkSessionStatus();
        $_SESSION = array();
        setcookie('remember', "", time() - 3600);
        session_destroy();
    }

    /**
     * Verificar el estado de la sesión y comenzarla si no está iniciada.
     */
    private static function checkSessionStatus()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
}

?>
