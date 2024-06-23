
<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TokenResultController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route to display login form
Route::get('login', [AuthController::class, 'LoginForm'])->name('login.form');

// Route to handle login
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::middleware('auth')->group(function () {
    Route::get('token-result/{token}', [TokenResultController::class, 'tokenResultPage'])->name('token-result');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

});
