<?php
$title = "تفاصيل المستخدم";

ob_start();
?>

<h1 class="text-3xl font-bold mb-6">تفاصيل المستخدم</h1>

<div id="userDetails" class="space-y-3 text-gray-800">
    <p><strong>ID:</strong> <?= $user['id'] ?></p>
    <p><strong>الاسم:</strong> <?= htmlspecialchars($user['name']) ?></p>
    <p><strong>اسم المستخدم:</strong> <?= htmlspecialchars($user['username']) ?></p>
    <p><strong>البريد الإلكتروني:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>الوصف:</strong> <?= htmlspecialchars($user['description']) ?></p>
    <p><strong>الصلاحية:</strong> <?= htmlspecialchars($user['role']) ?></p>
    <p><strong>الحالة:</strong> <?= $user['status'] == 1 ? 'نشط' : 'غير نشط' ?></p>
    <p><strong>تاريخ الإنشاء:</strong> <?= $user['created_at'] ?></p>
    <p><strong>آخر تعديل:</strong> <?= $user['updated_at'] ?></p>
</div>

<a href="/MVC/public/users" class="mt-6 inline-block text-blue-400 hover:underline font-medium">
    العودة إلى القائمة
</a>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout/main.php';
