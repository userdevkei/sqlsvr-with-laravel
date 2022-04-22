<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', [StudentController::class,'index'])->name('home');
Route::post('/storeDetails', [StudentController::class, 'storeDetails'])->name('storeDetails');


Route::any('viewStudents', [StudentController::class, 'viewStudents'])->name('viewStudents');

Route::get('/openVerify',
    function () { return view('verify');
})->name('openVerify');


Route::post('/verify', [StudentController::class, 'verify'])->name('verify');
Route::post('/re-verify/{id}', [StudentController::class, 're-verify'])->name('re-verify');
Route::any('/requestOTP/{id}', [StudentController::class, 'requestOTP'])->name('requestOTP');

Route::get('/re-verify',
    function () { return view('re-verify');
    })->name('re-verify');
