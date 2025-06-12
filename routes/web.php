<?php

require base_path('routes/admin.php');

use App\Http\Controllers\ChefController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WithdrawsControllr;
use App\Http\Controllers\Admin\NotificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    if (Auth::guard('admin')->check()) {
        return redirect()->route('home');
    } else {
        return redirect()->route('admin.register');
    }
});

// هنا لو عندك صفحة home مثلا Controller خاص بها
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth:admin');


// كل المسارات اللي تحت دي محمية لازم تسجيل دخول ادمن
Route::middleware(['auth:admin'])->prefix('dashboard')->name('dashboard.')->group(function () {

    // Users
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('user/{id}', [UserController::class, 'show'])->name('user.show');
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');

    // Chefs
    Route::get('chefs', [ChefController::class, 'index'])->name('chefs.index');
    Route::get('chef/{id}', [ChefController::class, 'show'])->name('chef.show');

    // Foods
    Route::get('foods', [FoodController::class, 'index'])->name('foods.index');
    Route::get('food/{id}', [FoodController::class, 'show'])->name('food.show');
    Route::delete('food/{id}', [FoodController::class, 'destroy'])->name('food.destroy');
    Route::patch('foods/{food}/toggle-status', [FoodController::class, 'toggleStatus'])->name('foods.toggleStatus');

    // Categories
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::patch('categories/{id}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggleStatus');

    // Withdraws
    Route::get('withdraws', [WithdrawsControllr::class, 'index'])->name('withdraws.index');
    Route::get('withdraws/{id}', [WithdrawsControllr::class, 'show'])->name('withdraws.show');
    Route::get('withdraws/{withdraw}/edit', [WithdrawsControllr::class, 'edit'])->name('withdraws.edit');
    Route::put('withdraws/{withdraw}', [WithdrawsControllr::class, 'update'])->name('withdraws.update');

    // Profile
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::post('profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
});

// Notifications محمية
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
    Route::get('/notifications/{id}', [NotificationController::class, 'show'])->name('admin.notifications.show');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('admin.notifications.read');
});



require __DIR__ . '/auth.php';
