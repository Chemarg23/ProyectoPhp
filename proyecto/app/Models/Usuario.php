<?php

namespace App\Models;

use App\Models\User;
use GestorErrores;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use TaskDatabase;
use Illuminate\Http\Request;

include_once app_path('Models/GestorErrores.php');
include_once app_path('Models/utils.php');

/**
 * Class Usuario
 * 
 * Representa un modelo de usuario con métodos para gestionar usuarios en la base de datos.
 */
class Usuario
{
    /**
     * @var TaskDatabase Instancia de TaskDatabase para la conexión a la base de datos.
     */
    private $connection;

    /**
     * Constructor de la clase Usuario.
     * 
     * Inicializa la conexión a la base de datos.
     */
    public function __construct()
    {
        $this->connection = TaskDatabase::getInstance();
    }

    /**
     * Obtiene un usuario por su dirección de correo electrónico.
     * 
     * @param string $mail La dirección de correo electrónico del usuario.
     * @return array|false Los datos del usuario si existe, o false si no existe.
     */
    public function getUserByMail($mail)
    {
        $rs = $this->connection->customQuery("SELECT * FROM usuarios WHERE email = '$mail'");
        return isset($rs[0]) ? $rs[0] : false;
    }

    /**
     * Obtiene un usuario por su identificador único.
     * 
     * @param int $id El identificador único del usuario.
     * @return array|false Los datos del usuario si existe, o false si no existe.
     */
    public function getUserById($id)
    {
        $rs = $this->connection->customQuery("SELECT * FROM usuarios WHERE user_id = '$id'");
        return isset($rs[0]) ? $rs[0] : false;
    }

    /**
     * Obtiene una lista paginada de usuarios.
     * 
     * @param int $pag La página de resultados a obtener.
     * @return array La lista de usuarios en la página especificada.
     */
    public function getAllUsers($pag)
    {
        $pag = $pag * 15 - 15;
        $sql = "SELECT * FROM usuarios LIMIT $pag, 15";
        return $this->connection->customQuery($sql);
    }

    /**
     * Verifica si un usuario con la dirección de correo electrónico proporcionada ya existe.
     * 
     * @param string $mail La dirección de correo electrónico a verificar.
     * @return bool True si el usuario existe, False si no.
     */
    public function userExist($mail)
    {
        $sql = "SELECT * FROM usuarios WHERE email = '$mail'";
        $rs = $this->connection->customQuery($sql);
        return !empty($rs);
    }

    /**
     * Establece el token de recuerdo y la fecha de vencimiento para un usuario.
     * 
     * @param string $mail La dirección de correo electrónico del usuario.
     * @param string $token El token de recuerdo.
     * @param string $expireDate La fecha de vencimiento del token.
     */
    public function setRememberToken($mail, $token, $expireDate)
    {
        $sql = "UPDATE usuarios SET remember_token = '$token', expire_token = '$expireDate' WHERE email = '$mail'";
        $this->connection->exec($sql);
    }

    /**
     * Verifica un token de recuerdo y devuelve los datos del usuario si el token es válido.
     * 
     * @param string $token El token de recuerdo a verificar.
     * @return array|false Los datos del usuario si el token es válido, o false si no es válido.
     */
    public function checkToken($token)
    {
        $sql = "SELECT * FROM usuarios WHERE remember_token = '$token'";
        $rs = $this->connection->customQuery($sql);
        return isset($rs[0]) ? $rs[0] : false;
    }

    /**
     * Actualiza la contraseña de un usuario después de restablecerla.
     * 
     * @param string $password La nueva contraseña del usuario.
     * @param string $token El token de recuerdo asociado al restablecimiento de contraseña.
     */
    public function updatePassword($password, $token)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET password = '$password', remember_token = null, expire_token = null WHERE remember_token = '$token'";
        $this->connection->exec($sql);
    }

    /**
     * Elimina un usuario por su identificador único.
     * 
     * @param int $id El identificador único del usuario a eliminar.
     */
    public function delete($id)
    {
        $sql = "DELETE FROM usuarios WHERE user_id = '$id'";
        $this->connection->exec($sql);
    }

    /**
     * Agrega un nuevo usuario a la base de datos.
     * 
     * @param array $params Los datos del nuevo usuario.
     */
    public function addUser($params)
    {
        $prepArray = [
            ':nombre' => $params['nombre'],
            ':apellido' => $params['apellido'],
            ':mail' => $params['mail'],
            ':pass' => password_hash($params['password'], PASSWORD_DEFAULT),
            ':NIF' => $params['dni'],
            ':rol' => $params['rol'],
            ':telefono' => $params['tel'],
        ];
        
        $sql = 'INSERT INTO usuarios VALUES(null, :nombre, :apellido, :mail, :pass, :NIF, :rol, :telefono, null, null)';
        
        $this->connection->prep($sql, $prepArray);
    }

    /**
     * Actualiza el perfil de un usuario.
     * 
     * @param array $params Los nuevos datos del usuario.
     * @param int $id El identificador único del usuario.
     */
    public function updateProfile($params, $id)
    {
        $sql = "UPDATE usuarios SET 
            nombre = :nombre, 
            apellido = :apellido, 
            email = :mail, 
            NIF = :NIF, 
            telefono = :telefono 
            WHERE user_id = $id";
    
    $userData = $this->prepareArray($params);
    $this->connection->prep($sql, $userData);
    }

    /**
     * Verifica las credenciales de inicio de sesión de un usuario.
     * 
     * @param Request $request La solicitud HTTP con las credenciales del usuario.
     * @param GestorErrores $gestor_errores Objeto para gestionar errores.
     * @return bool True si las credenciales son válidas, False si no.
     */
    public function checkCredentials($request, &$gestor_errores)
    {
        $rs = $this->getUserByMail($request->input('mail'));

        if (!$rs) {
            $gestor_errores->AnotaError('mail', 'No existe el usuario');
            return false;
        }
        
        return (trim(strtolower($request->input("mail"))) == strtolower($rs['email']) && password_verify($request->input("password"), $rs['password']));
    }

    /**
     * Método privado para preparar un array de datos del usuario.
     * 
     * @param array $params Los datos del usuario.
     * @return array El array preparado para la ejecución de la consulta SQL.
     */
    private function prepareArray($params)
    {
        return [
            ':nombre' => $params['nombre'],
            ':apellido' => $params['apellido'],
            ':mail' => $params['mail'],
            ':NIF' => $params['dni'],
            ':telefono' => $params['tel'],
        ];
    }
}

?>
