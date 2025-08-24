<?php
use App\Core\Route;
use App\Controllers\UserController;
use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\FieldController;

// موارد (Resource Routes) للمستخدمين
Route::resource("users", UserController::class);

// موارد (Resource Routes) للمجالات
Route::resource("fields", FieldController::class);

// الصفحة الرئيسية
Route::resource("home", HomeController::class);

// Route لصفحة تسجيل الدخول (GET)
Route::get('/login', [LoginController::class, 'index']);

// Route لحذف المستخدم نهائيًا
Route::delete('/users/{id}/destroyPermanent', [UserController::class, 'destroyPermanent']);

// Route لحذف المجال نهائيًا
Route::delete('/fields/{id}/destroyPermanent', [FieldController::class, 'destroyPermanent']);

// Route لمعالجة تسجيل الدخول (POST)
Route::post('/loginprocess', [LoginController::class, 'loginprocess']);

// Route للصفحة الرئيسية بعد تسجيل الدخول (Dashboard)
Route::get('/dashboard', [LoginController::class, 'dashboard']);

// Route لتسجيل الخروج
Route::get('/logout', [LoginController::class, 'logout']);
