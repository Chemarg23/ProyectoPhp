<?php

namespace App\Http\Controllers;

use App\Models\User;
use GestorErrores;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\SessionManager;

include app_path('Models/GestorErrores.php');
include app_path('Models/utils.php');


/**
 * Controlador para gestionar tareas.
 */
class TaskController extends Controller
{
  /**
   * @var GestorErrores Instancia del gestor de errores.
   */
  public $gestor_errores;

  /**
   * @var Task Instancia del modelo de tarea.
   */
  private $task;

  /**
   * Constructor de la clase TaskController.
   * Inicializa instancias del gestor de errores y del modelo de tarea.
   * Verifica la autenticación del usuario.
   */
  public function __construct()
  {
    $this->gestor_errores = new GestorErrores();
    $this->task = new Task();
    checkAuthentication();
  }

  /**
   * Muestra el formulario de confirmación para la eliminación de una tarea.
   *
   * @param int $id Identificador de la tarea.
   * @return \Illuminate\View\View Vista de confirmación de eliminación.
   */
  public function confirmDeletion($id)
  {
    checkAdminAccess();
    $task_info = $this->task->getTask($id);
    return view("confirmDel", compact('task_info'));
  }

  /**
   * Elimina una tarea.
   *
   * @param int $id Identificador de la tarea.
   * @return \Illuminate\Http\RedirectResponse Redirección a /taskbox.
   */
  public function delete($id)
  {
    checkAdminAccess();
    $this->task->deleteTask($id);
    return redirect('/taskbox');
  }

  /**
   * Muestra el formulario para agregar una nueva tarea.
   *
   * @return \Illuminate\View\View Vista del formulario de nueva tarea.
   */
  public function showAddForm()
  {
    checkAdminAccess();
    return view("formtask");
  }

  /**
   * Confirma una tarea.
   *
   * @param \Illuminate\Http\Request $request Objeto de solicitud.
   * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Vista de lista de tareas o redirección a /taskbox.
   */
  public function confirmTask(Request $request)
  {
    checkAdminAccess();
    $this->task->acceptTask(request()->route('id'));
    return $this->listTasks($request);
  }

  /**
   * Lista todas las tareas con paginación.
   *
   * @param \Illuminate\Http\Request $request Objeto de solicitud.
   * @return \Illuminate\View\View Vista de tareas y paginación.
   */
  public function listTasks(Request $request)
  {
    $pag = $request->query('pag') ??  1;
    $tareas = $this->task->getTasks($request->query(), $pag);
    $numPags = setPags($request->query(), $pag, "task");
    return view("tasks", compact('tareas', 'numPags'));
  }

  /**
   * Agrega una nueva tarea.
   *
   * @param \Illuminate\Http\Request $request Objeto de solicitud.
   * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Vista de nueva tarea agregada o redirección a /taskbox.
   */
  public function addTask(Request $request)
  {

    validateTask($request, $this->gestor_errores);
    if (!$this->gestor_errores->HayErrores()) {
      $id = ($this->task->maxId()) + 1;
      upload($request, $id);
      $this->task->insertTask($request->all());
      return redirect('/taskbox');
    } else {
      return view('formTask', ['errores' => $this->gestor_errores->getErrores()]);
    }
  }

  /**
   * Muestra el formulario para actualizar una tarea.
   *
   * @param int $id Identificador de la tarea.
   * @return \Illuminate\View\View Vista del formulario de actualización de tarea.
   */
  public function showUpdateForm($id)
  {
    checkAdminAccess();
    return view("updateTask", ['task_info' => $this->task->getTask($id)]);
  }


  /**
   * Actualiza una tarea.
   *
   * @param \Illuminate\Http\Request $request Objeto de solicitud.
   * @param int $id Identificador de la tarea.
   * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Vista de tarea actualizada o redirección a /taskbox.
   */
  public function update(Request $request, $id)
  {
    checkAdminAccess();
    validateTask($request, $this->gestor_errores);

    if (!$this->gestor_errores->HayErrores()) {
      upload($request, $id);
      $this->task->updateTask($request->all(), $id);
      return redirect('/taskbox');
    } else {
      return view('updateTask', ['errores' => $this->gestor_errores->getErrores()]);
    }
  }

  /**
   * Muestra los detalles de una tarea.
   *
   * @param int $id Identificador de la tarea.
   * @return \Illuminate\View\View Vista de detalles de tarea.
   */
  public function showTask($id)
  {
    $task_info = $this->task->getTask($id);
    return view("watchTask", compact('task_info'));
  }

  /**
   * Finaliza una tarea.
   *
   * @param \Illuminate\Http\Request $request Objeto de solicitud.
   * @param int $id Identificador de la tarea.
   * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse Vista de tarea finalizada o redirección a /taskbox.
   */
  public function finishTask(Request $request, $id)
  {
    $task_info = $this->task->getTask($id);
    checkTaskOperator($task_info['op_id']);
    validateFinishTask($request, $this->gestor_errores);
    
    if (!$this->gestor_errores->HayErrores()) {
      upload($request, $id);
      $this->task->finishTask($request->all(), $id);
      return redirect("/taskbox");
    } else {
      return view("finishTask", ['task_info' => $task_info, 'errores' => $this->gestor_errores->getErrores()]);
    }
  }

  /**
   * Muestra el formulario para finalizar una tarea.
   *
   * @param int $id Identificador de la tarea.
   * @return \Illuminate\View\View Vista del formulario de finalización de tarea.
   */
  public function finishForm($id)
  {
    $task_info = $this->task->getTask($id);
    checkTaskOperator($task_info['op_id']);
    return view("finishTask", ['task_info' => $task_info]);
  }
}
