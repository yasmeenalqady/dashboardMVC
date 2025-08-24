<?php
$title = "تعديل المستخدم";

ob_start();
?>

<h1 class="text-3xl font-bold mb-6">تعديل المستخدم</h1>

<form action="/MVC/public/users/<?= $user['id'] ?>" method="POST" class="space-y-4 max-w-3xl">
    <input type="hidden" name="_method" value="PUT">

    <div>
        <label class="block mb-1 font-medium">الاسم</label>
        <input type="text" name="name" class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500" 
               value="<?= htmlspecialchars($user['name']) ?>" required>
    </div>

    <div>
        <label class="block mb-1 font-medium">اسم المستخدم</label>
        <input type="text" name="username" class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500" 
               value="<?= htmlspecialchars($user['username']) ?>" required>
    </div>

    <div>
        <label class="block mb-1 font-medium">كلمة المرور</label>
        <input type="password" name="password" class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500" 
               placeholder="اترك الحقل فارغًا إذا لم ترغب بتغيير كلمة المرور">
    </div>

    <div>
        <label class="block mb-1 font-medium">البريد الإلكتروني</label>
        <input type="email" name="email" class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500" 
               value="<?= htmlspecialchars($user['email']) ?>" required>
    </div>

    <div>
        <label class="block mb-1 font-medium">الوصف</label>
        <textarea name="description" class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500" rows="3"><?= htmlspecialchars($user['description']) ?></textarea>
    </div>

    <div>
        <label class="block mb-1 font-medium">الصلاحية</label>
        <select name="role" class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>مدير</option>
            <option value="editor" <?= $user['role'] === 'editor' ? 'selected' : '' ?>>محرر</option>
            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>مستخدم</option>
        </select>
    </div>

    <div>
        <label class="block mb-1 font-medium">الحالة</label>
        <select name="status" class="w-full border px-3 py-2 rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="1" <?= $user['status'] == 1 ? 'selected' : '' ?>>نشط</option>
            <option value="0" <?= $user['status'] == 0 ? 'selected' : '' ?>>غير نشط</option>
        </select>
    </div>

    <div class="flex items-center mt-4">
        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 font-semibold transition duration-200">
            حفظ التعديلات
        </button>
        <a href="/MVC/public/users" class="ml-4 text-gray-700 hover:underline">إلغاء</a>
    </div>
</form>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout/main.php';
