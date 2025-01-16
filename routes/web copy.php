<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnswerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataFeedController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ExamineeController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TestTopicController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserGroupController;
use App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::livewire('QuestionBank/module/view', 'module');
Route::redirect('/', 'login');
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Route for the getting the data feed
    // Route::get('/json-data-feed', [DataFeedController::class, 'getDataFeed'])->name('json_data_feed');
    Route::get('dashboard', [ExamineeController::class, 'index'])->name('dashboard')->middleware(['role:1,5,10']);
    Route::get('/examiner/dashboard', [AdminController::class, 'index'])->name('examiner.dashboard.index')->middleware(['role:5,10']);
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard.index')->middleware(['role:10']);
    // Route::get('/dashboard/analytics', [DashboardController::class, 'analytics'])->name('analytics');
    // Route::get('/dashboard/fintech', [DashboardController::class, 'fintech'])->name('fintech');


    Route::group(['middleware' => ['role: 10, 5']], function () {
        //Question Bank Route Group
        Route::group(['prefix' => 'QuestionBank'], function () {
            //Module Routes
            Route::group(['prefix' => 'module'], function () {
                Route::post('create', [ModuleController::class, 'store'])->name('QuestionBank.module.create');
                Route::get('view', [ModuleController::class, 'index'])->name('QuestionBank.module.view');
                Route::delete('delete_module/{id}', [ModuleController::class, 'QuestionBank.module.destroy']);
            });

            //Topic Routes
            Route::group(['prefix' => 'topic'], function () {
                Route::post('add_topic', [TopicController::class, 'store'])->name('QuestionBank.topic.create');
                Route::get('view', [TopicController::class, 'index'])->name('QuestionBank.topic.view');
                Route::delete('delete_topic/{id}', [TopicController::class, 'QuestionBank.topic.destroy']);
            });

            //Question Routes
            Route::group(['prefix' => 'question'], function () {
                Route::post('add_question', [QuestionController::class, 'store']);
                Route::get('view_question', [QuestionController::class, 'index']);
                Route::get('/get-questions', [QuestionController::class, 'getQuestionsByTopic'])->name('get.questions');
            });

            //Answer Routes
            Route::group(['prefix' => 'answer'], function () {
                Route::post('add_answer', [AnswerController::class, 'store']);
                Route::get('view_answer', [AnswerController::class, 'index']);
            });



            //Group

        });
        //User
        Route::group(['prefix' => 'users'], function () {
            Route::post('add_user', [UsersController::class, 'store']);
            Route::get('view', [UsersController::class, 'index'])->name('users.view');
            Route::get('edit_user/{id}', [UsersController::class, 'edit']);
        });

        Route::group(['prefix' => 'groups'], function () {
            Route::post('add_group', [GroupController::class, 'store']);
            Route::get('view', [GroupController::class, 'index'])->name('groups.view');
            Route::get('edit_group/{id}', [GroupController::class, 'edit']);
            Route::put('update_group/{id}', [GroupController::class, 'update']);
            Route::put('delete_group/{id}', [GroupController::class, 'destroy']);


            //User-Group
            Route::post('addUsersToGroup', [UserGroupController::class, 'addUsersToGroup'])->name('users.updateSelected');
            Route::delete('removeUsersFromGroup', [UserGroupController::class, 'removeUsersFromGroup'])->name('users.deleteSelected');
        });
    });

    //Exam
    Route::group(['prefix' => 'exams'], function () {


        //Test Routes
        Route::get('view_test', [TestController::class, 'index'])->name('exams.view_test');
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
    });
});
