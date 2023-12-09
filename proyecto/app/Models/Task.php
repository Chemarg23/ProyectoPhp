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
 * Clase Task para gestionar tareas.
 */

class Task
{
    private $connection;

    /**
     * Constructor de la clase Task. Inicializa la conexión a la base de datos.
     */
    public function __construct()
    {
        $this->connection = TaskDatabase::getInstance();
    }

    /**
     * Obtiene los detalles de una tarea específica.
     *
     * @param int $id Identificador de la tarea.
     * @return array Detalles de la tarea.
     */
    public function getTask($id)
    {
        $query = "SELECT t.*, u.nombre as name, u.apellido as surname 
                  FROM task t, usuarios u 
                  WHERE u.user_id = t.op_id AND task_id = $id";
        return $this->connection->customQuery($query)[0];
    }

    /**
     * Elimina una tarea específica.
     *
     * @param int $id Identificador de la tarea a eliminar.
     */
    public function deleteTask($id)
    {
        $sql = "DELETE FROM task WHERE task_id = $id";
        $this->connection->exec($sql);
    }

    /**
     * Marca una tarea como aceptada.
     *
     * @param int $id Identificador de la tarea a marcar.
     */
    public function acceptTask($id)
    {
        $this->updateTaskStatus($id, 'P');
    }

    /**
     * Obtiene un conjunto de tareas que cumplen con ciertas condiciones y paginación.
     *
     * @param array|null $condition Condiciones de filtrado (puede ser null).
     * @param int $pag Número de página para la paginación.
     * @return array Lista de tareas.
     */
    public function getTasks(?array $condition, $pag)
    {
        $pag = $pag * 15 - 15;
        $sql = "SELECT t.*, u.nombre as op_name, u.apellido as op_ap  
                FROM task t, usuarios u 
                WHERE u.user_id = t.op_id " . getWhere($condition, 'a') . " 
                ORDER BY fecha_inicio DESC 
                LIMIT " . $pag . ", 15";
        return $this->connection->customQuery($sql);
    }

    /**
     * Finaliza una tarea específica.
     *
     * @param array $params Parámetros para la finalización de la tarea.
     * @param int $id Identificador de la tarea a finalizar.
     */
    public function finishTask(array $params, $id)
    {
        $sql = "UPDATE task
                SET estado = :estado,
                    img_url = CONCAT(img_url, '|', :img),
                    txt_url = CONCAT(txt_url, '|', :txt),
                    comentarios_post = :comentario_post
                WHERE task_id = $id";
        $this->connection->prep($sql, $this->prepareFinishTaskParams($params));
    }

    /**
     * Actualiza los detalles de una tarea específica.
     *
     * @param array $params Parámetros para la actualización de la tarea.
     * @param int $id Identificador de la tarea a actualizar.
     */
    public function updateTask(array $params, $id)
    {
        $sql = "UPDATE task
                SET descripcion = :desc,
                    nombre = :nombre,
                    apellido = :apellido,
                    telefono = :tel,
                    email = :mail,
                    NIF = :dni,
                    fecha_realizacion = :fecha,
                    direccion = :direccion,
                    poblacion = :poblacion,
                    codigo_postal = :codigo_postal,
                    provincia = :provincia,
                    op_id = :operador,
                    estado = :estado,
                    comentarios_pre = :comentario,
                    txt_url = CONCAT(txt_url, '|', :txt),
                    img_url = CONCAT(img_url, '|', :img)
                WHERE task_id = $id";
        $this->connection->prep($sql, $this->prepareTaskParams($params));
    }

    /**
     * Inserta una nueva tarea en la base de datos.
     *
     * @param array $params Parámetros para la nueva tarea.
     */
    public function insertTask(array $params)
    {
        $sql = "INSERT INTO task 
                VALUES (null, :desc, :nombre, :apellido, :tel, :mail, :dni, null, :fecha, 
                        :direccion, :poblacion, :codigo_postal, :provincia, :operador, 
                        :estado, :comentario, null, :txt, :img)";
        $this->connection->prep($sql, $this->prepareTaskParams($params));
    }

    /**
     * Obtiene el ID máximo de las tareas.
     *
     * @return int ID máximo de las tareas.
     */
    public function maxId()
    {
        return $this->connection->customQuery("SELECT MAX(task_id) as id FROM task")[0]['id'];
    }

    // Métodos privados auxiliares

    private function prepareTaskParams($params)
    {
        $params = $this->setDefaults($params, ['img_path', 'txt_path']);
        return [
            ':desc' => $params['desc'],
            ':nombre' => $params['nombre'],
            ':apellido' => $params['apellido'],
            ':tel' => $params['tel'],
            ':mail' => $params['mail'],
            ':dni' => $params['dni'],
            ':fecha' => $params['fecha'],
            ':direccion' => $params['direccion'],
            ':poblacion' => $params['poblacion'],
            ':codigo_postal' => $params['codigo-postal'],
            ':provincia' => $params['provincia'],
            ':operador' => $params['operador'],
            ':estado' => $params['estado'],
            ':img' => $params['img_path'],
            ':txt' => $params['txt_path'],
            ':comentario' => $params['comentario'],
        ];
    }

    private function prepareFinishTaskParams($params)
    {
        $params = $this->setDefaults($params, ['img_path', 'txt_path']);
        return [
            ':estado' => $params['estado'],
            ':img' => $params['img_path'],
            ':txt' => $params['txt_path'],
            ':comentario_post' => $params['comentario_post'],
        ];
    }

    private function updateTaskStatus($id, $status)
    {
        $this->connection->exec("UPDATE task SET estado = '$status' WHERE task_id = $id");
    }

    private function setDefaults($params, $keys)
    {
        foreach ($keys as $key) {
            if (!isset($params[$key])) {
                $params[$key] = '';
            }
        }
        return $params;
    }
}
