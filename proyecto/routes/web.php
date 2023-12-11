<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LogInController;
use Illuminate\Foundation\Auth\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',[LogInController::class,'showLogIn'])-> name('logIn');
Route::post('/logIn',[LogInController::class,'checkCredentials']) -> name('validateCredentials');
Route::get('/logOut',[LogInController::class, 'logOut'])->name('logOut');
Route::get('/forgottenPassword',[LogInController::class,'showForgottenForm'])->name('forgottenPass');
Route::post('/forgottenPassword',[LogInController::class, 'sendMail'])->name('verifyMail');
Route::get('/changePassword',[LogInController::class, 'showChangeForm'])->name('changePass');
Route::post('/changePassword',[LogInController::class, 'changePassword'])->name('updatePass');

Route::get('/taskbox', [TaskController::class,"listTasks"]) -> name("inbox"); 

Route::get('/taskbox/ConfirmDeletion/{id}', [TaskController::class, "confirmDeletion"])->name("confirmDelete");
Route::get('/taskbox/delete/{id}', [TaskController::class, "delete"])->name("delete");
Route::get('accept/{id}',[TaskController::class, 'confirmTask']) -> name("acceptTask");
Route::get('/taskbox/finish/{id}',[TaskController::class, 'finishForm']) -> name("finishForm");
Route::post('/taskbox/finishing/{id}',[TaskController::class, 'finishTask']) -> name("finishTask");

Route::get('/taskbox/addTask', [TaskController::class,"showAddForm"]) -> name("addTask"); 
Route::post('/taskbox/adding', [TaskController::class,"addTask"]) -> name("validate"); 

Route::get('/taskbox/watchTask/{id}', [TaskController::class, 'showTask'])->name('show');


Route::get('/taskbox/update/{id}',[TaskController::class,"showUpdateForm"])->name('updateForm');
Route::post('/taskbox/updating/{id}',[TaskController::class,"update"])->name('update');

Route::get("users",[UserController::class, 'listUsers' ])->name('showUsers');
Route::get("changeProfile/{id}", [UserController::class, 'changeForm'])->name('changeProfile');
Route::post("changeProfile/update/{id}", [UserController::class, 'updateUser'])->name('updateProfile');
Route::get("addUser",[UserController::class,'addForm'])->name('addUser');
Route::post("addUser",[UserController::class, 'addUser'])->name('addingUser');
Route::get("deleteUser/{id}", [UserController::class,'confirmDeletion'])->name('userDeletionForm');
Route::get("deletingUser/{id}", [UserController::class,'deleteUser'])->name('deleteUser');




