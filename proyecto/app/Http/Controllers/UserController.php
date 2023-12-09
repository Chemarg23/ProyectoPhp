<?php

namespace App\Http\Controllers;

use App\Models\User;
use GestorErrores;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\SessionManager;

include app_path('Models/GestorErrores.php');
include app_path('Models/utils.php');
/**
 * Controlador para gestionar usuarios y perfiles.
 */
class UserController extends Controller
{
    /**
     * @var GestorErrores Instancia del gestor de errores.
     */
    public $gestor_errores;

    /**
     * @var Usuario Instancia del modelo de usuario.
     */
    private $user;

    /**
     * Constructor de la clase UserController.
     * Inicializa instancias del gestor de errores y del modelo de usuario.
     * Verifica la autenticación del usuario.
     */
    public function __construct()
    {
        $this->gestor_errores = new GestorErrores();
        $this->user = new Usuario();
        checkAuthentication();
    }

    /**
     * Muestra el formulario para actualizar el perfil del usuario.
     *
     * @param int $id Identificador del usuario.
     * @return \Illuminate\View\View Vista del formulario de perfil.
     */
    public function changeForm($id)
    {
        $data = $this->user->getUserById($id);
        return view('perfil', ['userInfo' => $data]);
    }

    /**
     * Actualiza la información del perfil del usuario.
     *
     * @param \Illuminate\Http\Request $request Objeto de solicitud.
     * @param int $id Identificador del usuario.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Vista de perfil actualizado o redirección a /taskbox.
     */
    public function updateUser(Request $request, $id)
    {
        validateUserData($request, $this->gestor_errores);

        if ($this->gestor_errores->HayErrores()) {
            return view("perfil", ['errores' => $this->gestor_errores->getErrores()]);
        }

        $this->user->updateProfile($request->all(), $id);
        return redirect('/taskbox');
    }

    /**
     * Lista todos los usuarios con paginación.
     *
     * @param \Illuminate\Http\Request $request Objeto de solicitud.
     * @return \Illuminate\View\View Vista de usuarios y paginación.
     */
    public function listUsers(Request $request)
    {
        checkAdminAccess();
        $pag = $request->query('pag') ??  1;
        $numPags = setPags($request->query(), $pag, "usuarios");
        $listUsers = $this->user->getAllUsers($pag);
        return view('watchusers', ['user_info' => $listUsers, 'numPags' => $numPags]);
    }


    /**
     * Muestra el formulario para agregar un nuevo usuario.
     *
     * @return \Illuminate\View\View Vista del formulario de nuevo usuario.
     */
    public function addForm()
    {
        checkAdminAccess();
        return view('addUser');
    }

    /**
     * Agrega un nuevo usuario.
     *
     * @param \Illuminate\Http\Request $request Objeto de solicitud.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Vista de nuevo usuario agregado o redirección a /taskbox.
     */
    public function addUser(Request $request)
    {
        checkAdminAccess();
        validateNewUser($request, $this->gestor_errores);
        if ($this->gestor_errores->HayErrores()) {
            return view("addUser", ['errores' => $this->gestor_errores->getErrores()]);
        }
        $this->user->addUser($request->all());
        return redirect("/taskbox");
    }


    /**
     * Muestra el formulario de confirmación para la eliminación de un usuario.
     *
     * @param int $id Identificador del usuario.
     * @return \Illuminate\View\View Vista de confirmación de eliminación.
     */
    public function confirmDeletion($id)
    {
        checkAdminAccess();
        $user_info = $this->user->getUserById($id);
        if ($user_info['rol']) {
            return redirect()->back();
        }
        return view('confirmUserDeletion', ['user_info' => $user_info]);
    }

    /**
     * Elimina un usuario.
     *
     * @param int $id Identificador del usuario.
     * @return \Illuminate\Http\RedirectResponse Redirección a la lista de usuarios.
     */
    public function deleteUser($id)
    {
        checkAdminAccess();
        $user_info = $this->user->getUserById($id);
        if ($user_info['rol']) {
            return redirect()->back();
        }
        $this->user->delete($id);
        return redirect()->route('showUsers');
    }
}
