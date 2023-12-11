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
use Carbon\Carbon;

include app_path('Models/GestorErrores.php');
include app_path('Models/utils.php');

/**
 * Controlador para manejar la autenticación y la gestión de sesiones.
 */
class LogInController extends Controller
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
     * @var string Clave utilizada para encriptar y desencriptar cookies.
     */
    private $key = "b9d70b6bac6d902580a6b75261d39a55012b175e8b05524cc19042d474a1b7ae";

    /**
     * @var string Vector de inicialización utilizado para encriptar y desencriptar cookies.
     */
    private $iv = "ab862319eacc7d84";

    /**
     * Constructor de la clase LogInController.
     * Inicializa instancias del gestor de errores y del modelo de usuario.
     */
    public function __construct()
    {
        $this->gestor_errores = new GestorErrores();
        $this->user = new Usuario();
    }

    /**
     * Muestra la página de inicio de sesión.
     *
     * Si existe una cookie 'remember', intenta desencriptarla y
     * inicia una sesión si es válida.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Vista de inicio de sesión o redirección a /taskbox.
     */
    public function showLogIn(){
        
        if (SessionManager::isLoggedIn()) {
            return redirect("/taskbox");
        }
        if (isset($_COOKIE['remember'])) {
            $id = openssl_decrypt($_COOKIE['remember'], "aes-256-cbc", $this->key, 0, $this->iv);
            $userData = $this->user->getUserById($id);
            if ($userData) {
                SessionManager::startSession($userData['user_id'], "{$userData['nombre']} {$userData['apellido']}", $userData['rol']);
                return redirect("/taskbox");
            }
        }elseif(isset($_COOKIE['credential'])){
            $mail = openssl_decrypt($_COOKIE['credential'], "aes-256-cbc", $this->key, 0, $this->iv);
        }
        return view("login",['mail' => $mail]);
        

        
    }


    /**
     * Verifica las credenciales proporcionadas durante el inicio de sesión.
     *
     * Valida las entradas del formulario y verifica las credenciales del usuario.
     * Si es exitoso, inicia una sesión y, opcionalmente, establece una cookie 'remember'.
     *
     * @param \Illuminate\Http\Request $request Objeto de solicitud.
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Vista de inicio de sesión o redirección a /taskbox.
     */
    public function checkCredentials(Request $request){
        validateCredentialInput($request, $this->gestor_errores);
        if ($this->gestor_errores->HayErrores()) {
            return view("login", ['errores' => $this->gestor_errores->getErrores()]);
        }
        if (!$this->user->checkCredentials($request, $this->gestor_errores)) {
            $this->gestor_errores->AnotaError('password', "Contraseña incorrecta");
            return view("login", ['errores' => $this->gestor_errores->getErrores()]);
        }
        $data = $this->user->getUserByMail($request->input('mail'));
        if ($request->has('remember')) {
            $user_id = openssl_encrypt($data['user_id'], "aes-256-cbc", $this->key, 0, $this->iv);
            setcookie('remember', $user_id, time() + 3600 * 24 * 365);
        }
        $credential = openssl_encrypt($data['email'], "aes-256-cbc", $this->key, 0, $this->iv);
        setcookie('credential', $credential, time() + 3600 * 24 * 365);
        SessionManager::startSession($data['user_id'], "{$data['nombre']} {$data['apellido']}", $data['rol']);
        return redirect('/taskbox');
    }

    /**
     * Muestra el formulario para restablecer la contraseña olvidada.
     *
     * @return \Illuminate\View\View Vista del formulario de restablecimiento de contraseña.
     */
    public function showForgottenForm()
    {
        return view("forgottenPass");
    }

    /**
     * Envia un correo electrónico con un enlace para restablecer la contraseña.
     *
     * @param \Illuminate\Http\Request $request Objeto de solicitud.
     * @return \Illuminate\View\View Vista de éxito o fracaso del envío del correo electrónico.
     */
    public function sendMail(Request $request)
    {
        $mail = $request->input('mail');
        $this->gestor_errores->validateMail('mail', $mail);
        if ($this->gestor_errores->HayErrores()) {
            return view('forgottenPass', ['errores' => $this->gestor_errores->getErrores()]);
        }
        if ($this->user->userExist($mail)) {
            $token = bin2hex(random_bytes(32));
            $expireDate = Carbon::now()->addHours(2);
            $this->user->setRememberToken($mail, $token, $expireDate);
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $message = "Para restablecer su contraseña haga click <a href='" . route('changePass') . "?token=$token'>aquí</a>";
            mail($mail, 'Restablecimiento de contraseña', $message,$headers);

            return view('forgottenPass', ['status' => 'success']);
        } else {
            return view('forgottenPass', ['status' => 'failure']);
        }
    }

    /**
     * Muestra el formulario para cambiar la contraseña con un token.
     *
     * @param \Illuminate\Http\Request $request Objeto de solicitud.
     * @return \Illuminate\View\View Vista del formulario de cambio de contraseña.
     */
    public function showChangeForm(Request $request)
    {
        $token = $request->query('token');
        if ($token && ($user = $this->user->checkToken($token))) {
            if (Carbon::parse($user['expire_token']) > Carbon::now()) {
                return view("setPassword", compact('token'));
            } else {
                return view('setPassword', ['failure' => true]);
            }
        } else {
            return abort(403, 'No tienes permiso para acceder a esta página');
        }
    }

    /**
     * Cambia la contraseña del usuario.
     *
     * @param \Illuminate\Http\Request $request Objeto de solicitud.
     * @return \Illuminate\View\View Vista de éxito o fracaso del cambio de contraseña.
     */
    public function changePassword(Request $request)
    {
        $this->gestor_errores->emptyInput('password', $request->input("password"));
        $this->gestor_errores->emptyInput("passwordConfirm", $request->input("passwordConfirm"));
        if ($this->gestor_errores->HayErrores()) {
            return view('setPassword', ['errores' => $this->gestor_errores->getErrores(), 'token' => $request->input("token")]);
        }
        if ($request->input("password") == $request->input("passwordConfirm")) {
            $this->user->updatePassword($request->input("password"), $request->input('token'));
            return redirect()->route('logIn');
        } else {
            $this->gestor_errores->AnotaError("passwordConfirm", "Las dos contraseñas deben coincidir");
            return view('setPassword', ['errores' => $this->gestor_errores->getErrores(), 'token' => $request->input("token")]);
        }
    }

    /**
     * Cierra la sesión del usuario.
     *
     * @return \Illuminate\Http\RedirectResponse Redirección a la página de inicio.
     */
    public function logOut()
    {
        SessionManager::endSession();
        return redirect('/');
    }
}
