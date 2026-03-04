<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

/* 1. TRANG CHỦ & TÌM KIẾM */
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search-suggestions', [HomeController::class, 'suggestions'])->name('search.suggestions');
Route::get('/animal/detail/{id}', [HomeController::class, 'detail'])->name('animal.detail');
Route::post('/animal/like/{id}', [HomeController::class, 'likeAnimal'])->name('animal.like');

/* 2. HỆ THỐNG XÁC THỰC (AUTH) */
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::get('/register', function () { return view('auth.login'); })->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/* 3. MẠNG XÃ HỘI (SOCIAL) */
Route::get('/social', [SocialController::class, 'index'])->name('social.index');
Route::post('/social/store', [SocialController::class, 'store'])->name('social.store');
// Route thả tim quan trọng nhất: Nhận ID từ URL và chạy vào hàm like($id)
Route::post('/post/{id}/like', [SocialController::class, 'like'])->name('social.like');

/* 4. NHẬN DIỆN AI (YOLO) */
Route::get('/nhan-dien', function () { return view('nhandien'); })->name('ai.nhandien');
Route::get('/get-animal-info/{name}', function ($name) {
    $animal = \App\Models\Animal::where('name', 'LIKE', '%' . $name . '%')->first();
    if ($animal) {
        return response()->json([
            'status' => $animal->status,
            'behavior' => $animal->behavior,
            'description' => $animal->description
        ]);
    }
    return response()->json(['error' => 'Chưa có dữ liệu chi tiết cho loài này'], 404);
})->name('ai.get_info');

/* 5. QUẢN TRỊ (ADMIN) */
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Quản lý động vật
    Route::get('/admin/animals/{id?}', [AdminController::class, 'manageAnimals'])->name('admin.animals');
    Route::post('/admin/animals/store', [AdminController::class, 'storeAnimal'])->name('admin.animals.store');
    Route::get('/admin/animals/delete/{id}', [AdminController::class, 'deleteAnimal'])->name('admin.animals.delete');
    Route::get('/admin/animals/edit/{id}', [AdminController::class, 'editAnimal'])->name('admin.animals.edit');
    Route::post('/admin/animals/update/{id}', [AdminController::class, 'updateAnimal'])->name('admin.animals.update');

    // Quản lý bài viết mạng xã hội
    Route::get('/admin/posts', [AdminController::class, 'indexPost'])->name('admin.post.index');
    Route::post('/admin/posts/update/{id}', [AdminController::class, 'updateStatus'])->name('admin.post.update');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});
