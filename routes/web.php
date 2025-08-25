<?php
use App\Core\Route;
use App\Controllers\UserController;
use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\FieldController;
use App\Controllers\ProjectController; // ✅ إضافة الكنترولر الجديد

// موارد (Resource Routes) للمستخدمين
Route::resource("users", UserController::class);

// موارد (Resource Routes) للمجالات
Route::resource("fields", FieldController::class);

// موارد (Resource Routes) للمشاريع ✅
Route::resource("projects", ProjectController::class);

// الصفحة الرئيسية


Route::get('/api/dashboard', [HomeController::class, 'apiDashboard']);

// Route لصفحة تسجيل الدخول (GET)
Route::get('/login', [LoginController::class, 'index']);

// Route لمعالجة تسجيل الدخول (POST)
Route::post('/loginprocess', [LoginController::class, 'loginprocess']);

// Route للصفحة الرئيسية بعد تسجيل الدخول (Dashboard)
Route::get('/dashboard', [LoginController::class, 'dashboard']);

// Route لتسجيل الخروج
Route::get('/logout', [LoginController::class, 'logout']);

// Route لحذف المستخدم نهائيًا
Route::delete('/users/{id}/destroyPermanent', [UserController::class, 'destroyPermanent']);

// Route لحذف المجال نهائيًا
Route::delete('/fields/{id}/destroyPermanent', [FieldController::class, 'destroyPermanent']);

// Route لحذف المشروع نهائيًا ✅
Route::delete('/projects/{id}/destroyPermanent', [ProjectController::class, 'destroyPermanent']);
