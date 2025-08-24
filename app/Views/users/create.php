<?php
$title = "إضافة مستخدم جديد";

ob_start();
?>

<h1 class="text-3xl font-bold mb-6">إضافة مستخدم جديد</h1>

<form action="/MVC/public/users" method="POST" class="space-y-4 max-w-3xl">

    <div>
        <label class="block mb-1 font-medium">الاسم</label>
        <input type="text" name="name" class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500" required>
    </div>

    <div>
        <label class="block mb-1 font-medium">اسم المستخدم</label>
        <input type="text" name="username" class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500" required>
    </div>

    <div>
        <label class="block mb-1 font-medium">كلمة المرور</label>
        <input type="password" name="password" class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500" required>
    </div>

    <div>
        <label class="block mb-1 font-medium">البريد الإلكتروني</label>
        <input type="email" name="email" class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500" required>
    </div>

    <div>
        <label class="block mb-1 font-medium">الصلاحية</label>
        <select name="role" class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500" required>
            <option value="user">مستخدم</option>
            <option value="editor">محرر</option>
            <option value="admin">مدير</option>
        </select>
    </div>

    <div>
        <label class="block mb-1 font-medium">الوصف</label>
        <textarea name="description" class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500" rows="3"></textarea>
    </div>

    <div>
        <label class="block mb-1 font-medium">الحالة</label>
        <select name="status" class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="1">نشط</option>
            <option value="0">غير نشط</option>
        </select>
    </div>

    <div class="flex items-center mt-4">
        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-semibold transition duration-200">
            حفظ
        </button>
        <a href="/MVC/public/users" class="ml-4 text-gray-700 hover:underline">إلغاء</a>
    </div>

</form>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout/main.php';
