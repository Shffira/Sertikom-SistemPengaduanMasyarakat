<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ResponseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Route Masyarakat
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
    Route::resource('complaints', ComplaintController::class);
    Route::get('complaints/{complaint}/respond', [ResponseController::class, 'create'])->name('responses.create');
    Route::post('complaints/{complaint}/respond', [ResponseController::class, 'store'])->name('responses.store');
    Route::get('/respon-masuk', [ComplaintController::class, 'responMasuk'])->name('respon.masuk');
});

// Route Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/complaints', [ComplaintController::class, 'adminIndex'])->name('complaints.index');
    Route::delete('/complaints/{complaint}', [ComplaintController::class, 'adminDestroy'])->name('complaints.destroy');
    Route::get('complaints/{complaint}/respond', [ResponseController::class, 'create'])->name('responses.create');
    Route::post('complaints/{complaint}/respond', [ResponseController::class, 'store'])->name('responses.store');
    
});

require __DIR__.'/auth.php';