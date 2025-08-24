<?php
require_once __DIR__ . '/../vendor/autoload.php';
header("Access-Control-Allow-Origin: http://localhost:5173"); // ضع المنفذ الصحيح لتطبيق Vue
header("Access-Control-Allow-Credentials: true"); // مهم جداً للسماح بالكوكيز
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

use App\Core\Route;
use Dotenv\Dotenv;

// ✅ تحميل ملف .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// جرب طباعة القيم بعد التحميل
// echo '<pre>';
// print_r($_ENV);
// echo '</pre>';
// exit;

// قراءة الـ URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

// إزالة الـ base path (مثال: /MVC/public)
if ($base !== '/' && strpos($uri, $base) === 0) {
    $uri = substr($uri, strlen($base));
}

$uri = '/' . trim($uri, '/');
if ($uri === '//') $uri = '/';

// تحميل Routes
require __DIR__ . '/../routes/web.php';

// تنفيذ الراوت
Route::dispatch($_SERVER['REQUEST_METHOD'], $uri);
