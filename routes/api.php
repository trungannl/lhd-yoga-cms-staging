<?php

use App\Http\Controllers\V1\StrudentApiController;
use App\Http\Controllers\V1\CoacherApiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\ResetPasswordController;
use App\Http\Controllers\V1\UserApiController;
use App\Http\Controllers\V1\SkinApiController;
use App\Http\Controllers\V1\StudioApiController;
use App\Http\Controllers\V1\StudioStudentApiController;
use App\Http\Controllers\V1\UploadApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {
    Route::post('user/login', [AuthController::class, 'login'])->name('login');
    Route::post('user/register', [AuthController::class, 'register'])->name('register');
    Route::post('user/forgot-password', [ResetPasswordController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('user/verify-code', [ResetPasswordController::class, 'verifyCode'])->name('verify-code');
    Route::post('user/change-passwod', [ResetPasswordController::class, 'changePasswod'])->name('change-passwod');

    Route::post('user/send-otp', [AuthController::class, 'sendOtp'])->name('send-otp');
    Route::post('user/check-otp', [AuthController::class, 'checkOtp'])->name('check-otp');

    Route::post('upload/image', [UploadApiController::class, 'store'])->name('upload-image');
    Route::get('health', [UploadApiController::class, 'health'])->name('health');
});

Route::prefix('v1')->middleware('jwt.verify')->group(function () {
    Route::post('user/refresh-token',[AuthController::class, 'refresh'])->name('refresh-token');
    Route::get('user/logout', [AuthController::class, 'logout'])->name('logout');

    Route::put('my-profile', [UserApiController::class, 'update'])->name('user.update');
    Route::get('my-profile', [UserApiController::class, 'profile'])->name('user.profile');

    Route::get('get-student', [UserApiController::class, 'getStudentByPhone'])->name('user.get-student-phone');
    Route::get('get-coacher', [UserApiController::class, 'getCoacherByPhone'])->name('user.get-coacher-phone');

    Route::post('my-profile/upload', [UserApiController::class, 'upload'])->name('user.upload');
    Route::post('my-profile/change-password', [UserApiController::class, 'changePassword'])->name('user.change-password');
    Route::delete('my-profile/remove', [UserApiController::class, 'remove'])->name('user.remove');

    Route::get('search-student', [UserApiController::class, 'searchStudent'])->name('user.search-student');
    Route::get('search-coacher', [UserApiController::class, 'searchCoacher'])->name('user.search-coacher');

    Route::group(['middleware' => ['student']], function () {
        Route::get('student/my-class', [StrudentApiController::class, 'class'])->name('student.my-class');
        Route::get('student/all-class', [StrudentApiController::class, 'allClass'])->name('student.all-class');
        Route::post('student/register-class', [StrudentApiController::class, 'registerClass'])->name('student.register-class');
        Route::post('student/attend', [StrudentApiController::class, 'attend'])->name('student.attend');
        Route::get('student/my-class/{classId}', [StrudentApiController::class, 'showClassAttend'])->name('student.show-class-attend');
        Route::get('student/all-class/{classId}', [StrudentApiController::class, 'showClassRegister'])->name('student.show-class-register');
    });

    Route::group(['middleware' => ['coacher']], function () {
        Route::get('coacher/my-class', [CoacherApiController::class, 'class'])->name('coucher.my-class');
        Route::get('coacher/my-class/{classId}', [CoacherApiController::class, 'showClass'])->name('coucher.show-my-class');
        Route::get('coacher/class/{classId}/data', [CoacherApiController::class, 'listData'])->name('coucher.list-data');
        Route::post('coacher/class/{classId}/data', [CoacherApiController::class, 'createData'])->name('coucher.create-data');
        Route::post('coacher/class/{classId}/checkin', [CoacherApiController::class, 'checkin'])->name('coucher.checkin');
        Route::get('coacher/salary', [CoacherApiController::class, 'salary'])->name('coucher.salary');
    });

    Route::group(['middleware' => ['studio']], function () {
        Route::get('students', [StrudentApiController::class, 'index'])->name('student.index');
        Route::post('students', [StrudentApiController::class, 'store'])->name('student.store');
        Route::post('students/{id}', [StrudentApiController::class, 'update'])->name('student.update');
        Route::get('students/{id}', [StrudentApiController::class, 'show'])->name('student.show');
        Route::delete('students/{id}', [StrudentApiController::class, 'destroy'])->name('student.destroy');

        Route::get('coacher', [CoacherApiController::class, 'index'])->name('coacher.index');
        Route::post('coacher', [CoacherApiController::class, 'store'])->name('coacher.store');
        Route::post('coacher/{id}', [CoacherApiController::class, 'update'])->name('coacher.update');
        Route::get('coacher/{id}', [CoacherApiController::class, 'show'])->name('coacher.show');
        Route::delete('coacher/{id}', [CoacherApiController::class, 'destroy'])->name('coacher.destroy');

        Route::post('class', [StudioApiController::class, 'store'])->name('class.store');
        Route::post('class/{id}', [StudioApiController::class, 'update'])->name('class.update');
        //PUT method do not support upload file, so we use a POST method for update class.
        Route::get('class', [StudioApiController::class, 'index'])->name('class.index');
        Route::get('class/{id}', [StudioApiController::class, 'show'])->name('class.show');
        Route::get('class/{id}/student', [StudioApiController::class, 'getStudents'])->name('class.student');
        Route::post('class/{id}/student', [StudioApiController::class, 'addStudent'])->name('class.add-student');
        Route::post('class/{id}/register-student', [StudioApiController::class, 'registerStudent'])->name('class.register-student');

        Route::get('list-register-student', [StudioStudentApiController::class, 'index'])->name('class.list-register-student');
        Route::get('register-student/{classId}/{studentId}', [StudioStudentApiController::class, 'show'])->name('class.get-register-student');
        Route::post('add-register-student', [StudioStudentApiController::class, 'store'])->name('class.add-register-student');
        Route::post('update-register-student', [StudioStudentApiController::class, 'update'])->name('class.update-register-student');
    });

});

