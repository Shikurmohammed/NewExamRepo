<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\Auth\LogoutController;
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
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\BackupRestore;
use App\Livewire\Examinee\Dashboard;
use App\Livewire\Examinee\MyTest;
use App\Livewire\Examinee\StartExam;
use App\Livewire\Examiner\ExaminerDashboard;
use App\Livewire\Exams\QuestionAssignment;
use App\Livewire\Exams\TestExecution;
use App\Livewire\Exams\TestList;
use App\Livewire\Exams\TestLoginForm;
use App\Livewire\Groups\GroupList;
use App\Livewire\Module;
use App\Livewire\QuestionBank\AnswerList;
use App\Livewire\QuestionBank\ModuleList;
use App\Livewire\QuestionBank\QuestionList;
use App\Livewire\QuestionBank\TopicList;
use App\Livewire\Users\UserList;
use App\Models\Test;
use NunoMaduro\Collision\Exceptions\TestException;

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

// Redirect '/' to 'login'
Route::redirect('/', 'login');
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Use Livewire middleware and define Livewire components
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Dashboard Routes
    Route::get('/examinee/dashboard', Dashboard::class)
        ->name('examinee.dashboard')
        ->middleware(['role:1,5,10']);
    Route::get('/examiner/dashboard', ExaminerDashboard::class)
        ->name('examiner.dashboard.index')
        ->middleware(['role:5,10']);
    Route::get('/admin/dashboard', AdminDashboard::class)
        ->name('admin.dashboard.index')
        ->middleware(['role:10']);

    // // Question Bank Routes
    Route::group(['prefix' => 'QuestionBank', 'middleware' => ['role:10,5']], function () {
        Route::get('module/view', ModuleList::class)
            ->name('QuestionBank.module.view');
        Route::get('module/create', ModuleList::class)
            ->name('QuestionBank.module.create');

        Route::get('topic/view', TopicList::class)
            ->name('QuestionBank.topic.view');
        Route::get('question/view', QuestionList::class)
            ->name('QuestionBank.question.view');
        Route::get('answer/view', AnswerList::class)
            ->name('QuestionBank.answer.view');
    });

    // User Management Routes
    Route::group(['prefix' => 'users'], function () {
        Route::get('view_user', UserList::class)
            ->name('users.view');

        Route::get('view_group', GroupList::class)
            ->name('groups.view');
    });

    Route::group(['prefix' => 'settings'], function () {
        Route::get('backup_restore/view', BackupRestore::class)
            ->name('backup_restore.view');
    });
    // Exam Routes
    Route::group(['prefix' => 'exams', 'middleware' => ['role:10,5']], function () {
        Route::get('view_test', TestList::class)
            ->name('exams.view_test');
        Route::get('view_question_assignment', QuestionAssignment::class)->name('exams.view_question_assignment');
        //Route::get('view_test_execution', TestExecution::class);
    });
    Route::group(['prefix' => 'MyExam', 'middleware' => ['role:10,5,1']], function () {
        Route::get('myexam_list', MyTest::class)
            ->name('myexam_list');
        // Route::get('start_exam', MyTest::class)
        //     ->name('start_exam');
        // Route::get('start_exam/{testId}', [MyTest::class, 'startExam']) // Specify the method if needed
        //     ->name('start_exam');

        Route::get('start_exam/{testId}', TestExecution::class) // StartExam Specify the method if needed
            ->name('start_exam');
        Route::get('execute_exam/{testId}', TestExecution::class) //, 'showQuestion', 'executeExam'// Specify the method if needed
            ->name('execute_exam');

        // Route::get('start_exam/{testId}', [StartExam::class, 'startTest']) // Specify the method if needed
        //     ->name('start_exam');

        // "test.info"
        Route::get('info/{testId}', TestExecution::class) // Specify the method if needed
            ->name('info');
        Route::get('test_login/{testId}', TestLoginForm::class)->name('test_login');
    });


    Route::get('/mytest-list', StartExam::class)->name('mytest.list');
});
