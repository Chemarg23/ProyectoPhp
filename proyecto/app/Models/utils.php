<?php
include "db.php";

use Illuminate\Http\Request;
use App\Models\SessionManager;
use app\Models\Task;

/**
 * Devuelve el valor de un campo enviado por POST. Si no existe devuelve el valor por defecto
 * @param string $nombreCampo
 * @param mixed $valorPorDefecto
 * @return string
 */
function ValorPost($nombreCampo, $valorPorDefecto = '')
{
	if (isset($_POST[$nombreCampo]))
		return $_POST[$nombreCampo];
	else
		return $valorPorDefecto;
}


/**
 *
 * @param string $name Nombre del campo
 * @param array $opciones Opciones que tiene el select
 * 			clave array=valor option
 * 			valor array=texto option
 * @param string $valorDefecto Valor seleccionado
 * @return string
 */
function CreaSelect($name, $opciones, $valorDefecto = '', $class)
{
	$html = "\n" . '<select class="' . $class . '" name="' . $name . '">';
	foreach ($opciones as $value => $text) {
		if ($value == $valorDefecto)
			$select = 'selected="selected"';
		else
			$select = "";
		$html .= "\n\t<option value=\"$value\" $select>$text</option>";
	}
	$html .= "\n</select>";

	return $html;
}


/**
 * Genera la paginación para una tabla dada.
 *
 * @param array|null $queryParams - Parámetros de consulta adicionales.
 * @param int $pag - Página actual.
 * @param string $table - Nombre de la tabla.
 * @return string - HTML que representa la paginación.
 */
function setPags(?array $queryParams, $pag = 1, $table)
{
	$db = TaskDatabase::getInstance();
	$result = $db->numRows("select COUNT(*) as num FROM $table " . getWhere($queryParams, 'w'));
	$numPags = ceil($result / 15);
	$html = "<ul class='pagination'>";
	if ($pag > 1) {
		$html .= generatePaginationLink($pag - 1, $queryParams, '&laquo;');
	}
	for ($i = 1; $i <= $numPags; $i++) {
		$html .= generatePaginationLink($i, $queryParams, $i);
	}
	if ($pag < $numPags) {
		$html .= generatePaginationLink($pag + 1, $queryParams, "&raquo;");
	}
	$html .= "</ul>";
	return $html;
}

/**
 * Genera un enlace de paginación manteniendo los datos proporcionados en la url.
 *
 * @param int $pag - Número de la página.
 * @param array $queryParams - Parámetros de consulta.
 * @param mixed $content - Contenido del enlace.
 * @return string - HTML que representa el enlace de paginación.
 */
function generatePaginationLink($pag, $queryParams, $content)
{
	$URL = '?' . http_build_query(array_merge($queryParams, ['pag' => $pag]));
	return "<li class='page-item'><a href='$URL' class='page-link'>$content</a></li>";
}

/**
 * Construye la cláusula WHERE para una consulta SQL basada en los parámetros dados.
 *
 * @param array $params - Parámetros para la consulta.
 * @param string $ini - Indica si es el inicio de la cláusula WHERE.
 * @return void - No se construye el WHERE
 * @return string - Cláusula WHERE construida.
 */
function getWhere($params, $ini)
{
	$allowedFields = ["descripcion", "estado", "op_id"];
	$params = array_intersect_key($params, array_flip($allowedFields));

	if (!empty($params)) {
		if ($ini === 'w') {
			$where = ' WHERE ';
		} else {
			$where = " AND ";
		};
		foreach ($params as $key => $value) {
			$where .= "$key like '%$value%'";
			if (next($params) !== false) {
				$where .= ' AND ';
			}
		}
		return $where;
	}
}

/**
 * Obtiene la lista de operarios.
 *
 * @return array - Lista de operarios.
 */
function getOperators()
{
	$db = TaskDatabase::getInstance();
	$sql = 'select user_id, nombre, apellido FROM usuarios WHERE rol = 0';
	$rs = $db->customQuery($sql);

	$operator = array();
	$operator[""] = "Seleccione un operario";
	foreach ($rs as $reg) {
		$operator[$reg["user_id"]] = $reg["nombre"] . " " . $reg['apellido'];
	}
	$db->close();
	return $operator;
}

/**
 * Obtiene la lista de provincias.
 *
 * @return array - Lista de provincias.
 */
function getProvincias()
{
	$db = TaskDatabase::getInstance();
	$sql = 'SELECt id, nombre FROM tbl_provincias';
	$rs = $db->customQuery($sql);

	$provincias = array();
	$provincias[""] = "Seleccione una provincia";
	foreach ($rs as $reg) {
		$provincias[$reg["id"]] = $reg["nombre"];
	}
	$db->close();
	return $provincias;
}

/**
 * Verifica y crea el directorio de una tarea para almacenar ficheros.
 *
 * @param int $id - Identificador de la tarea.
 * @return void
 */
function checkTaskDirectory($id)
{
	$directorio = public_path("files/task$id");
	if (!is_dir($directorio)) {
		mkdir($directorio, 0755, true);
	}
}

/**
 * Sube un archivo al servidor.
 *
 * @param mixed $file - Archivo a subir.
 * @param int $id - Identificador de la tarea.
 * @param string $directory - Directorio de destino.
 * @return string - Ruta del archivo subido.
 */
function uploadFile($file, $id, $directory)
{
	$directorio = "files/task$id/$directory";
	$path = public_path($directorio);
	if (!is_dir($path)) {
		mkdir($path, 0755, true);
	}
	$fileName = time() . '_' . $file->getClientOriginalName();
	$file->move($path, $fileName);

	return "$directorio/$fileName";
}

/**
 * Verifica la autenticación del usuario.
 *
 * @return void - Redirecciona si no está autenticado.
 */
function  checkAuthentication()
{
	if (!SessionManager::isLoggedIn()) {
		header('Location: ' . route('logIn'));
		exit();
	}
}

/**
 * Verifica si el usuario actual es el operador al cargo de la tarea.
 *
 * @param int $id - Identificador del operador.
 * @return void - Redirecciona si no es el operador.
 */
function checkTaskOperator($id)
{
	if ($id != SessionManager::getUserId()) {
		header('Location: ' . url()->previous());
		exit();
	}
}

/**
 * Verifica el acceso de administrador.
 *
 * @return void - Redirecciona si no es administrador.
 */
function checkAdminAccess()
{
	if (!SessionManager::isAdmin()) {
		header("Location: " . url()->previous());
		exit();
	}
}

/**
 * Sube archivos relacionados a una tarea.
 *
 * @param mixed $request - Datos de la solicitud.
 * @param int $id - Identificador de la tarea.
 * @return void
 */
function upload(&$request, $id)
{
	$task = new Task();
	checkTaskDirectory($id);
	if ($request->hasFile('imagen')) {
		$txtPath = uploadFile($request->file('imagen'), $id, 'img');
		$request['img_path'] = $txtPath;
	}
	if ($request->hasFile('txt')) {
		$txtPath = uploadFile($request->file('txt'), $id, 'docs');
		$request['txt_path'] = $txtPath;
	}
}


/**
 * Valida las credenciales del usuario.
 *
 * @param mixed $request - Datos de la solicitud.
 * @param mixed $gestor_errores - Gestor de errores.
 * @return void - Solo anota errores en caso de que los haya.
 */
function validateCredentialInput($request, &$gestor_errores)
{
	$gestor_errores->validateMail("mail", $request->input('mail'));
	$gestor_errores->emptyInput("password", $request->input('password'));
}

/**
 * Valida los datos para finalizar una tarea.
 *
 * @param mixed $request - Datos de la solicitud.
 * @param mixed $gestor_errores - Gestor de errores.
 * @return void
 */
function validateFinishTask($request, &$gestor_errores)
{
	$gestor_errores->emptyInput("estado", $request->input('estado'));
	if ($request->hasFile('imagen')) {
		$gestor_errores->validateImg($request->file('imagen')->getMimeType());
	}
	if ($request->hasFile('txt')) {
		$gestor_errores->validateTextDoc($request->file('txt')->getMimeType());
	}
}

/**
 * Valida los datos de una tarea.
 *
 * @param mixed $request - Datos de la solicitud.
 * @param mixed $gestor_errores - Gestor de errores.
 * @return void
 */
function validateTask($request, &$gestor_errores)
{
	$gestor_errores->emptyInput("nombre", $request->input('nombre'));
	$gestor_errores->emptyInput("apellido", $request->input('apellido'));
	$gestor_errores->validateMail("mail", $request->input('mail'));
	$gestor_errores->validateNif("dni", $request->input('dni'));
	$gestor_errores->emptyInput("desc", $request->input('desc'));
	$gestor_errores->validatePhone("tel", $request->input("tel"));
	$gestor_errores->validateDate("fecha", $request->input('fecha'));
	$gestor_errores->emptyInput("direccion", $request->input('direccion'));
	$gestor_errores->emptyInput("poblacion", $request->input('poblacion'));
	$gestor_errores->validatePostalCode("codigo-postal", $request->input('codigo-postal'), $request->input('provincia'));
	$gestor_errores->emptyInput("provincia", $request->input('provincia'));
	$gestor_errores->emptyInput("operador", $request->input('operador'));
	validateFinishTask($request, $gestor_errores);
}

/**
 * Valida los datos de un usuario.
 *
 * @param mixed $request - Datos de la solicitud.
 * @param mixed $gestor_errores - Gestor de errores.
 * @return void
 */
function validateUserData($request, &$gestor_errores)
{
	$gestor_errores->emptyInput("nombre", $request->input('nombre'));
	$gestor_errores->emptyInput("apellido", $request->input('apellido'));
	$gestor_errores->validateMail("mail", $request->input('mail'));
	$gestor_errores->validateNif("dni", $request->input('dni'));
	$gestor_errores->validatePhone("tel", $request->input("tel"));
}

/**
 * Valida los datos de un nuevo usuario.
 *
 * @param mixed $request - Datos de la solicitud.
 * @param mixed $gestor_errores - Gestor de errores.
 * @return void
 */
function validateNewUser($request, &$gestor_errores)
{
	validateUserData($request, $gestor_errores);
	$gestor_errores->validateRol($request->input('rol'));
	$gestor_errores->emptyInput("password", $request->input('password'));
}
