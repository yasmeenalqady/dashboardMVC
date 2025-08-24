<?php
$title = "قائمة المستخدمين";

ob_start();
?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- رأس الصفحة وأزرار الإجراء -->
<div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-3">
    <h1 class="text-3xl font-bold mb-3 md:mb-0">قائمة المستخدمين</h1>
    <div class="flex gap-3">
        <a href="/MVC/public/users/create" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold transition hover:bg-blue-700">إضافة مستخدم</a> 
        <a href="/MVC/public/users/deleted" class="px-6 py-2 bg-red-600 text-white rounded-lg font-semibold transition hover:bg-red-700">عرض المحذوفين</a>
    </div>
</div>

<!-- رسالة الخطأ -->
<?php if (!empty($_SESSION['error'])): ?>
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<!-- نموذج البحث -->
<form method="GET" class="mb-4 flex gap-2">
    <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" 
           placeholder="ابحث عن مستخدم..." class="px-4 py-2 border rounded w-full md:w-64 transition">
    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded transition hover:bg-gray-700">بحث</button>
</form>

<!-- جدول المستخدمين -->
<div class="overflow-x-auto shadow-lg rounded-lg">
    <table class="min-w-full border transition-colors duration-300">
        <thead class="border-b bg-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-sm font-medium border">#</th>
                <th class="px-4 py-3 text-left text-sm font-medium border">الاسم</th>
                <th class="px-4 py-3 text-left text-sm font-medium border">اسم المستخدم</th>
                <th class="px-4 py-3 text-left text-sm font-medium border">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $index => $user): ?>
                    <tr class="hover:bg-gray-100 transition">
                        <td class="px-4 py-2 border"><?= ($perPage * ($currentPage - 1)) + $index + 1 ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($user['name']) ?></td>
                        <td class="px-4 py-2 border"><?= htmlspecialchars($user['username']) ?></td>
                        <td class="px-4 py-2 border flex gap-2">
                            <a href="/MVC/public/users/<?= $user['id'] ?>" class="px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600 text-sm">عرض</a>
                            <a href="/MVC/public/users/<?= $user['id'] ?>/edit" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">تعديل</a>
                            <form action="/MVC/public/users/<?= $user['id'] ?>" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟');">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">حذف</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="px-4 py-4 text-center">لا يوجد مستخدمين</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- التنقل بين الصفحات -->
   <?php if ($totalPages > 1): ?>
<div class="flex justify-between items-center mt-4">
    <a href="?search=<?= urlencode($search ?? '') ?>&page=<?= max(1, $currentPage - 1) ?>" 
       class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">السابق</a>

    <span>صفحة <?= $currentPage ?> من <?= $totalPages ?></span>

    <a href="?search=<?= urlencode($search ?? '') ?>&page=<?= min($totalPages, $currentPage + 1) ?>" 
       class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">التالي</a>
</div>
<?php endif; ?>

    
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout/main.php';
