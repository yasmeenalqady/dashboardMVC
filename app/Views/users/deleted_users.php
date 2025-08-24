<?php
$title = "المستخدمون المحذوفون";

ob_start();
?>

<h1 class="text-3xl font-bold mb-6 text-red-600">المستخدمون المحذوفون</h1>

<?php if (!empty($deletedUsers) && is_array($deletedUsers)): ?>
  <div class="overflow-x-auto">
    <table class="w-full border-collapse border border-gray-200 text-gray-800 transition-colors duration-300">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="p-3 border">ID</th>
          <th class="p-3 border">الاسم</th>
          <th class="p-3 border">اسم المستخدم</th>
          <th class="p-3 border">البريد الإلكتروني</th>
          <th class="p-3 border">الصلاحية</th>
          <th class="p-3 border">الحالة</th>
          <th class="p-3 border">تاريخ الحذف</th>
          <th class="p-3 border">الإجراءات</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($deletedUsers as $user): ?>
          <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
            <td class="p-3 border"><?= $user['id'] ?></td>
            <td class="p-3 border"><?= htmlspecialchars($user['name']) ?></td>
            <td class="p-3 border"><?= htmlspecialchars($user['username']) ?></td>
            <td class="p-3 border"><?= htmlspecialchars($user['email']) ?></td>
            <td class="p-3 border"><?= htmlspecialchars($user['role']) ?></td>
            <td class="p-3 border">
              <?= $user['status'] == 1 ? '<span class="text-green-600 font-medium">نشط</span>' : '<span class="text-gray-400">غير نشط</span>' ?>
            </td>
            <td class="p-3 border text-red-500"><?= $user['deleted_at'] ?></td>
            <td class="p-3 border flex gap-2">
              <form action="/MVC/public/users/<?= $user['id'] ?>/restore" method="POST">
                <button class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">استعادة</button>
              </form>
              <form action="/MVC/public/users/<?= $user['id'] ?>/destroyPermanent" method="POST" class="inline"
                    onsubmit="return confirm('هل أنت متأكد؟ لا يمكن التراجع عن هذا الإجراء!')">
                <button class="bg-red-800 text-white px-3 py-1 rounded hover:bg-red-700 text-sm">حذف نهائي</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php else: ?>
  <p class="text-gray-400">لا يوجد مستخدمون محذوفون.</p>
<?php endif; ?>

<a href="/MVC/public/users" class="mt-6 inline-block text-blue-400 hover:underline font-medium">
  العودة إلى المستخدمين النشطين
</a>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout/main.php';
