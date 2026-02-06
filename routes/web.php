<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
})->name('home');

/* AUTH */
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.login');
})->name('register');


Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->name('admin.dashboard')
    ->middleware('auth');

    // Trang Dashboard chính (Tổng quan)
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');


// TRANG QUẢN LÝ RIÊNG: Hiện danh sách và Form
Route::get('/admin/animals/{id?}', [AdminController::class, 'manageAnimals'])->name('admin.animals');
// Xử lý lưu dữ liệu
Route::post('/admin/animals/store', [AdminController::class, 'storeAnimal'])->name('admin.animals.store');
Route::get('/admin/animals/delete/{id}', [AdminController::class, 'deleteAnimal'])->name('admin.animals.delete');
Route::get('/admin/animals/edit/{id}', [AdminController::class, 'editAnimal'])->name('admin.animals.edit');
Route::post('/admin/animals/update/{id}', [AdminController::class, 'updateAnimal'])->name('admin.animals.update');
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route lấy gợi ý tên động vật
Route::get('/search-suggestions', [App\Http\Controllers\HomeController::class, 'suggestions'])->name('search.suggestions');

// Route hiển thị trang nhận diện
Route::get('/nhan-dien', function () {
    return view('nhandien');
})->name('ai.nhandien');

// Route lấy thông tin chi tiết động vật dựa trên tên (dùng cho AI Recognition)
Route::get('/get-animal-info/{name}', function ($name) {
    // Tìm kiếm trong bảng động vật theo tên
    $animal = \App\Models\Animal::where('name', 'LIKE', '%' . $name . '%')->first();

    if ($animal) {
        return response()->json([
            'status' => $animal->status,       // Cột tình trạng bảo tồn (Vd: Nguy cấp)
            'behavior' => $animal->behavior,   // Cột tập tính
            'description' => $animal->description // Cột mô tả chi tiết
        ]);
    }

    // Trả về lỗi nếu không tìm thấy dữ liệu tương ứng trong Database
    return response()->json(['error' => 'Chưa có dữ liệu chi tiết cho loài này'], 404);
})->name('ai.get_info');
Route::get('/animal/detail/{id}', [HomeController::class, 'detail'])->name('animal.detail');
Route::post('/animal/like/{id}', [App\Http\Controllers\HomeController::class, 'likeAnimal'])->name('animal.like');

Route::get('/social', [App\Http\Controllers\SocialController::class, 'index'])->name('social.index');
Route::post('/social/post', [App\Http\Controllers\SocialController::class, 'store'])->name('social.store');
Route::post('/social/store', [SocialController::class, 'store'])->name('social.store');
Route::post('/social/like', [App\Http\Controllers\SocialController::class, 'toggleLike'])->name('social.like');
Route::get('/admin/posts', [AdminController::class, 'indexPost'])->name('admin.post.index');
Route::post('/admin/posts/update/{id}', [AdminController::class, 'updateStatus'])->name('admin.post.update');
