<!DOCTYPE html>
<html lang="th" class="h-full w-full scroll-smooth">

<head>
    <title><?= $title ?? 'Trash Bank' ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');

        * {
            font-family: 'Noto Sans Thai', 'Inter', sans-serif;
        }

        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --primary-light: #d1fae5;
            --secondary: #3b82f6;
            --danger: #ef4444;
            --warning: #f59e0b;
            --success: #10b981;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #f0fdf7 100%);
        }

        /* ... styles อื่นๆ คงเดิม ... */

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gradient-to-b from-green-100 to-slate-50 min-h-screen bg-fixed">

    <div x-data="{ sidebarOpen: window.innerWidth >= 1024, profileMenuOpen: false }"
        @resize.window="sidebarOpen = window.innerWidth >= 1024" class="min-h-screen flex flex-col">

        <header
            class="fixed top-0 left-0 right-0 z-30 flex items-center justify-between h-16 px-4 bg-white border-b border-slate-200 transition-all duration-300">
            <div class="flex items-center gap-2">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="text-slate-500 focus:outline-none p-2 hover:bg-slate-100 rounded-md transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <a href="/admin" class="flex items-center gap-2 group">
                    <div
                        class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white font-bold text-lg">
                        ♻️</div>
                    <div class="hidden sm:block">
                        <h1 class="text-xl font-bold text-emerald-700">Trash Bank</h1>
                        <p class="text-xs text-slate-500">Admin Dashboard</p>
                    </div>
                </a>
            </div>

            <div class="hidden md:block text-center">
                <h2 class="text-lg font-semibold text-slate-800"><?= $title ?? '' ?></h2>
            </div>

            <div class="relative" @click.away="profileMenuOpen = false">
                <button @click="profileMenuOpen = !profileMenuOpen"
                    class="flex items-center gap-2 px-3 py-2 hover:bg-slate-100 rounded-lg transition-colors">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=10b981&color=fff"
                        class="w-8 h-8 rounded-full">
                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-medium text-slate-900">ผู้ดูแล</p>
                        <p class="text-xs text-slate-500">ศูนย์กลาง</p>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                        :class="{'rotate-180': profileMenuOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="profileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white border border-slate-200 py-2">
                    <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">⚙️ ตั้งค่า</a>
                    <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">👤 โปรไฟล์</a>
                    <hr class="my-1">
                    <a href="/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">🚪 ออกจากระบบ</a>
                </div>
            </div>
        </header>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed top-16 bottom-0 left-0 z-20 w-64 bg-white border-r border-slate-200 transform transition-transform duration-150 ease-in-out overflow-y-auto">

            <nav class="p-4 space-y-2">
                <div class="mb-6">
                    <p class="px-4 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">เมนู</p>
                </div>

                <a href="/admin"
                    class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                    <span class="text-sm font-medium">หน้าหลัก</span>
                </a>

                <div class="mt-6">
                    <p class="px-4 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">ธุรกรรม</p>

                    <a href="/admin/transactions/waste"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600"
                            xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                            <g fill="currentColor" fill-rule="evenodd" stroke-width="0.5" stroke="currentColor">
                                <path
                                    d="M3.5 6a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5v-8a.5.5 0 0 0-.5-.5h-2a.5.5 0 0 1 0-1h2A1.5 1.5 0 0 1 14 6.5v8a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 14.5v-8A1.5 1.5 0 0 1 3.5 5h2a.5.5 0 0 1 0 1z" />
                                <path
                                    d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z" />
                            </g>
                        </svg>
                        <span class="text-sm font-medium">ฝากขยะ</span>
                    </a>

                    <a href="/admin/transactions/clear_waste"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <span class="text-sm font-medium">เคลียร์ยอด</span>
                    </a>

                    <a href="/admin/transactions/waste_sale"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                        </svg>
                        <span class="text-sm font-medium">ขายขยะ</span>
                    </a>
                </div>

                <div class="mt-6">
                    <p class="px-4 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">จัดการ</p>

                    <a href="/admin/manage/users"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 16 16">
                            <path fill="currentColor"
                                d="M7.5 9a2 2 0 0 1 2 2c0 .965-.592 1.73-1.411 2.23C7.27 13.728 6.175 14 5 14s-2.27-.272-3.089-.77C1.091 12.73.5 11.965.5 11a2 2 0 0 1 2-2zm-5 1a1 1 0 0 0-1 1c0 .508.304.992.932 1.375S3.966 13 5 13s1.94-.242 2.568-.625S8.5 11.508 8.5 11a1 1 0 0 0-1-1zm11.652-.992A1.5 1.5 0 0 1 15.5 10.5c0 .771-.47 1.409-1.101 1.83c-.636.424-1.486.67-2.399.67c-.699 0-1.36-.146-1.917-.403c.16-.287.28-.601.35-.943c.423.21.964.346 1.567.346c.743 0 1.394-.202 1.844-.502c.453-.302.656-.665.656-.998a.5.5 0 0 0-.4-.49L14 10h-3.674a3 3 0 0 0-.575-.979A1.5 1.5 0 0 1 9.999 9h4zm-1.92-5.5a2.253 2.253 0 0 1 2.022 2.241l-.012.23A2.253 2.253 0 0 1 12.002 8l-.23-.012a2.25 2.25 0 0 1-2.01-2.01l-.012-.23a2.25 2.25 0 0 1 2.252-2.252zM5 2.5A2.75 2.75 0 1 1 5 8a2.75 2.75 0 0 1 0-5.5m7.002 1.997a1.252 1.252 0 1 0 0 2.504a1.252 1.252 0 0 0 0-2.504M5 3.5A1.75 1.75 0 1 0 5 7a1.75 1.75 0 0 0 0-3.5"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                        <span class="text-sm font-medium">ผู้ใช้งาน</span>
                    </a>

                    <a href="/admin/manage/faculty"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600" xmlns="http://www.w3.org/2000/svg" width="30" height="24" viewBox="0 0 640 512">
                            <path fill="currentColor"
                                d="m335.5 4l288 160c15.4 8.6 21 28.1 12.4 43.5s-28.1 21-43.5 12.4L320 68.6L47.5 220c-15.4 8.6-34.9 3-43.5-12.4s-3-34.9 12.4-43.5L304.5 4c9.7-5.4 21.4-5.4 31.1 0zM320 160a40 40 0 1 1 0 80a40 40 0 1 1 0-80m-176 96a40 40 0 1 1 0 80a40 40 0 1 1 0-80m312 40a40 40 0 1 1 80 0a40 40 0 1 1-80 0M226.9 491.4L200 441.5V480c0 17.7-14.3 32-32 32h-48c-17.7 0-32-14.3-32-32v-38.5l-26.9 49.9c-6.3 11.7-20.8 16-32.5 9.8s-16-20.8-9.8-32.5l37.9-70.3c15.3-28.5 45.1-46.3 77.5-46.3h19.5c16.3 0 31.9 4.5 45.4 12.6l33.6-62.3c15.3-28.5 45.1-46.3 77.5-46.3h19.5c32.4 0 62.1 17.8 77.5 46.3l33.6 62.3c13.5-8.1 29.1-12.6 45.4-12.6h19.5c32.4 0 62.1 17.8 77.5 46.3l37.9 70.3c6.3 11.7 1.9 26.2-9.8 32.5s-26.2 1.9-32.5-9.8L552 441.5V480c0 17.7-14.3 32-32 32h-48c-17.7 0-32-14.3-32-32v-38.5l-26.9 49.9c-6.3 11.7-20.8 16-32.5 9.8s-16-20.8-9.8-32.5l36.3-67.5c-1.7-1.7-3.2-3.6-4.3-5.8L376 345.5V400c0 17.7-14.3 32-32 32h-48c-17.7 0-32-14.3-32-32v-54.5l-26.9 49.9c-1.2 2.2-2.6 4.1-4.3 5.8l36.3 67.5c6.3 11.7 1.9 26.2-9.8 32.5s-26.2 1.9-32.5-9.8z"
                                stroke-width="13" stroke="currentColor" />
                        </svg>
                        <span class="text-sm font-medium">คณะ</span>
                    </a>

                </div>
            </nav>
        </aside>

        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" x-transition:opacity
            class="fixed inset-0 z-10 bg-black opacity-50  lg:hidden"></div>

        <main :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-0'"
            class="flex-1 p-8 pt-24 transition-all duration-150 ease-in-out">
            <?php include $viewPath; ?>
        </main>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="<?= $script ?? "" ?>"></script>
    <script type="module" src="<?= $module ?? "" ?>"></script>
</body>

</html>