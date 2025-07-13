<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SymptomController;

Route::get('/', function () {
    return Auth::check()
        ? view('main')
        : view('auth');
});

Route::get('login', function () {
    return view('auth/login');
});

Route::get('register', function () {
    return view('auth/register');
});

Route::get('user', function () {
    return view('user');
});

Route::get('hospital', function () {
    return view('hospital');
});

Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'store']);
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'authenticate'])->name('login');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::post('/api/search', [SymptomController::class, 'search'])->name('symptom.search');

Route::middleware('web')->group(function () {
    Route::post('/api/keywords/store', [SymptomController::class, 'storeKeyword'])->name('keywords.store');
    Route::get('/api/keywords/recent', [SymptomController::class, 'recentKeywords']);
});

use Illuminate\Support\Facades\DB;

Route::get('/db-check', function () {
    try {
        DB::connection()->getPdo();
        logger('✅ DB 연결 성공');
        return response()->json(['message' => 'DB 연결 성공']);
    } catch (\Exception $e) {
        logger('❌ DB 연결 실패: ' . $e->getMessage());
        return response()->json(['error' => 'DB 연결 실패', 'details' => $e->getMessage()], 500);
    }
});

use App\Http\Controllers\UserController;

Route::get('/user/pdf/all', [UserController::class, 'downloadAllMedicalRecordsPdf'])->name('user.medical.all.pdf');