<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<title><?= $title ?? 'لوحة التحكم' ?></title>

<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = { darkMode: 'class', theme: { extend: { fontFamily: { sans: ['Cairo', 'sans-serif'] } } } };
</script>
<style>
  body, aside, table, th, td, a, button, input, form { transition: background-color 0.3s, color 0.3s, border-color 0.3s; }
</style>
</head>

<body class="font-sans min-h-screen flex bg-gray-100 text-gray-900">

<!-- Sidebar -->
<aside id="sidebar" class="fixed top-0 right-0 h-full w-64 bg-white shadow-lg flex flex-col p-6">
  <h2 class="text-2xl font-bold mb-6">لوحة التحكم</h2>
  <nav class="flex flex-col space-y-4 mb-6">
    <a href="/MVC/public/home" class="px-3 py-2 rounded hover:bg-gray-200 transition">الرئيسية</a>
    <a href="/MVC/public/settings" class="px-3 py-2 rounded hover:bg-gray-200 transition">الإعدادات</a>
    <a href="/MVC/public/projects" class="px-3 py-2 rounded hover:bg-gray-200 transition">المشاريع</a>
    <a href="/MVC/public/users" class="px-3 py-2 rounded hover:bg-gray-200 transition">المستخدمون</a>
  </nav>
  <button id="toggleDark" class="flex items-center justify-center gap-2 px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition">
    <span id="darkIcon">🌙</span>
    <span id="darkText">الوضع الداكن</span>
  </button>
</aside>

<!-- Main Content -->
<main class="flex-1 p-6 mr-64">
  <?= $content ?? '' ?>
</main>

<script>
const toggleDarkBtn = document.getElementById('toggleDark');
const darkIcon = document.getElementById('darkIcon');
const darkText = document.getElementById('darkText');
const body = document.body;
const sidebar = document.getElementById('sidebar');
const allButtons = document.querySelectorAll('button, a');
const allInputs = document.querySelectorAll('input');

function applyDarkMode(isDark){
  if(isDark){
    body.style.backgroundColor = '#1f2937';
    body.style.color = '#f3f4f6';
    sidebar.style.backgroundColor = '#111827';
    sidebar.style.color = '#f3f4f6';
    document.querySelectorAll('table').forEach(t=>t.style.backgroundColor='#1f2937');
    document.querySelectorAll('th, td').forEach(c=>{ c.style.color='#f3f4f6'; c.style.borderColor='#374151'; });
    allButtons.forEach(b=>{ b.style.backgroundColor='#374151'; b.style.color='#f3f4f6'; });
    allInputs.forEach(i=>{ i.style.backgroundColor='#374151'; i.style.color='#f3f4f6'; i.style.borderColor='#4b5563'; });
    darkIcon.textContent='☀️'; darkText.textContent='الوضع الفاتح';
  } else {
    body.style.backgroundColor = '#f3f4f6';
    body.style.color = '#111827';
    sidebar.style.backgroundColor = '#ffffff';
    sidebar.style.color = '#111827';
    document.querySelectorAll('table').forEach(t=>t.style.backgroundColor='#ffffff');
    document.querySelectorAll('th, td').forEach(c=>{ c.style.color='#111827'; c.style.borderColor='#d1d5db'; });
    allButtons.forEach(b=>{ b.style.backgroundColor=''; b.style.color=''; });
    allInputs.forEach(i=>{ i.style.backgroundColor=''; i.style.color=''; i.style.borderColor=''; });
    darkIcon.textContent='🌙'; darkText.textContent='الوضع الداكن';
  }
  localStorage.setItem('darkMode', isDark);
}

applyDarkMode(localStorage.getItem('darkMode')==='true');
toggleDarkBtn.addEventListener('click', ()=>{ applyDarkMode(localStorage.getItem('darkMode')!=='true'); });
</script>

</body>
</html>
