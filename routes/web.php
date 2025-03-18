<?php


// use App\Models\PolicePosition;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PolicePositionController;

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

// Route::get('/', function () {
//     return view('welcome');
// });
// die('hh');
Route::get('/', [PolicePositionController::class, 'index'])->name('index');
Route::post('/store', [PolicePositionController::class, 'store'])->name('store');
Route::post('/check', [PolicePositionController::class, 'check'])->name('check');