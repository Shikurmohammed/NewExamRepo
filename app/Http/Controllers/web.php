<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\ExamineeController;
use App\Http\Controllers\ExaminorController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserGroupController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TestSubjectController;
use App\Http\Controllers\TestTopicController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard')->middleware('can:isAdmin');
Route::get('/examiner/dashboard', [ExamineeController::class, 'index'])->name('examiner.dashboard')->middleware('can:isExaminer');
Route::get('/examinee/dashboard', [ExamineeController::class, 'index'])->name('examinee.dashboard')->middleware('can:isExaminee');


//Module Routes
Route::post('add_module', [ModuleController::class, 'store']); //->middleware(['auth','verified']);
Route::get('view_module', [ModuleController::class, 'index'])->middleware('auth');
Route::delete('delete_module/{id}', [ModuleController::class, 'destroy']);

//Topic|subject Routes
Route::post('add_topic', [TopicController::class, 'store']); //->middleware(['auth','verified']);
Route::get('view_topic', [TopicController::class, 'index']);
Route::get('/get-topics', [TopicController::class, 'getTopicsByModule'])->name('get.topics');

//Question Routes
Route::post('add_question', [QuestionController::class, 'store']);
Route::get('view_question', [QuestionController::class, 'index']);
Route::get('/get-questions', [QuestionController::class, 'getQuestionsByTopic'])->name('get.questions');

//Answer Qoutes
Route::post('add_answer', [AnswerController::class, 'store']);
Route::get('view_answer', [AnswerController::class, 'index']);

//User
Route::post('add_user', [UsersController::class, 'store']);
Route::get('view_user', [UsersController::class, 'index']);
Route::get('edit_user/{id}', [UsersController::class, 'edit']);

//Group
Route::post('add_group', [GroupController::class, 'store']);
Route::get('view_group', [GroupController::class, 'index'])->name('view.group');
Route::get('edit_group/{id}', [GroupController::class, 'edit']);
Route::put('update_group/{id}', [GroupController::class, 'update']);
Route::put('delete_group/{id}', [GroupController::class, 'destroy']);

//User-Group
Route::post('addUsersToGroup', [UserGroupController::class, 'addUsersToGroup'])->name('users.updateSelected');
Route::delete('removeUsersFromGroup', [UserGroupController::class, 'removeUsersFromGroup'])->name('users.deleteSelected');

//Test Routes
Route::get('view_test', [TestController::class, 'index'])->middleware('auth');
Route::post('add_test', [TestController::class, 'store'])->middleware('auth');
Route::get('edit_test/{id}', [TestController::class, 'edit'])->middleware('auth');
Route::put('update_test/{id}', [TestController::class, 'update'])->middleware('auth');
Route::delete('delete_test/{id}', [TestController::class, 'destroy'])->middleware('auth');
Route::get('lockTest/{id}', [TestController::class, 'lockTest'])->middleware('auth');
Route::get('unLockTest/{id}', [TestController::class, 'unLockTest'])->middleware('auth');

// Test Subject Set Routes
Route::get('view_question_assignment', [TestTopicController::class, 'index'])->middleware('auth');
//Route::post('assign_question', [TestTopicController::class, 'store']);//->middleware('auth');
Route::post('assign_question', [TestTopicController::class, 'assignQuestion']); //->middleware('auth');

//Test Execution Routes
Route::get('view_test_execution', [TestTopicController::class, 'startTest'])->middleware('auth');
