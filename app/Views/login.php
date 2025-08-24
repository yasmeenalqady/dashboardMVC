<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-blue-100 via-blue-200 to-blue-300 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg">
        <h2 class="text-3xl font-bold mb-6 text-center text-blue-700">تسجيل الدخول</h2>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm text-center font-semibold shadow">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form action="/MVC/public/loginprocess" method="POST" class="space-y-5">
            <!-- حقل البريد -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700">البريد الإلكتروني</label>
                <input type="email" name="email" required
                    class="w-full p-3 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
            </div>

            <!-- حقل كلمة المرور -->
            <div>
                <label class="block mb-1 font-semibold text-gray-700">كلمة المرور</label>
                <input type="password" name="password" required
                    class="w-full p-3 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 transition">
            </div>

            <!-- زر تسجيل الدخول -->
            <button type="submit"
                class="w-full bg-blue-600 text-white p-3 rounded-lg font-bold hover:bg-blue-700 hover:shadow-md transition">
                تسجيل الدخول
            </button>
        </form>

        <p class="text-center text-gray-600 mt-6 text-sm">
            ليس لديك حساب؟ <a href="#" class="text-blue-600 hover:underline">إنشاء حساب</a>
        </p>
    </div>

</body>
</html>
