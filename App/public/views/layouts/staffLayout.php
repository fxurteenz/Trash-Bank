<!DOCTYPE html>
<html lang="th" class="h-full w-full">

<head>
    <title><?= $title ?? '' ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&family=Inter:wght@300..700&display=swap');

        :root {
            --brand-emerald-50: #ecfdf5;
            --brand-emerald-100: #d1fae5;
            --brand-emerald-500: #10b981;
            --brand-emerald-600: #059669;
            --brand-blue-500: #3b82f6;
            --brand-blue-600: #2563eb;
        }

        .h-screen-minus-nav {
            min-height: calc(100vh - 4rem);
        }

        .noto-sans-thai {
            font-family: "Noto Sans Thai", sans-serif;
        }

        .inter {
            font-family: "Inter", sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #ecfdf5 100%);
            min-height: 100vh;
        }

        .app-gradient-header {
            background: linear-gradient(90deg, var(--brand-emerald-500) 0%, var(--brand-blue-500) 100%);
        }

        .app-gradient-sidebar {
            background: linear-gradient(180deg, var(--brand-emerald-600) 0%, var(--brand-emerald-500) 100%);
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.15);
            transform: translateY(-2px);
        }
    </style>
</head>

<body class="noto-sans-thai">
    <div class="min-h-screen"
        x-data="{ sidebarOpen: window.innerWidth >= 1024, profileDropdownOpen: false }">

        <header class="flex items-center justify-between h-16 app-gradient-header text-white shadow-lg fixed top-0 left-0 right-0 z-30 px-4 w-full">
            <div class="flex items-center space-x-4">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="text-white/90 focus:outline-none lg:hidden p-2 hover:bg-white/10 rounded transition">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <a href="/staff" class="flex items-center space-x-2">
                    <span class="text-xl font-bold">♻️ Waste-Bank</span>
                </a>

                <span class="hidden md:block text-lg ml-6 border-l border-white/30 pl-4 font-semibold text-white/90"><?= $title ?></span>
            </div>

            <div class="relative" @click.away="profileDropdownOpen = false">
                <button @click="profileDropdownOpen = !profileDropdownOpen"
                    class="flex items-center focus:outline-none hover:opacity-80 transition">
                    <img class="h-10 w-10 rounded-full object-cover border-2 border-white/80"
                        src="/assets/images/4042171.png" alt="Profile">
                </button>

                <div x-show="profileDropdownOpen" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 z-40">
                    <a href="#"
                        class="block px-4 py-2 text-sm text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 transition">👤 ดูโปรไฟล์</a>
                    <div class="border-t border-slate-100 my-1"></div>
                    <a href="/logout"
                        class="block px-4 py-2 text-sm text-slate-700 hover:bg-red-50 hover:text-red-600 transition">🚪 ออกจากระบบ</a>
                </div>
            </div>
        </header>

        <div class="flex pt-16 w-full">
            <aside :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
                class="fixed inset-y-0 left-0 z-20 w-56 app-gradient-sidebar text-white transform transition-transform duration-300 lg:translate-x-0 lg:static lg:min-h-screen lg:block shadow-lg">
                <div class="p-6 mt-16 lg:mt-0">
                    <nav class="space-y-2">
                        <a href="/staff"
                            class="flex items-center p-3 rounded-lg transition duration-200 <?= $pages === "home" ? "bg-white/20 font-semibold" : "hover:bg-white/10" ?>">
                            <span class="text-lg mr-3">🏠</span>
                            <span>หน้าหลัก</span>
                        </a>

                        <div class="text-xs uppercase tracking-wider text-white/60 my-4 pl-2 font-semibold">การดำเนินการ</div>
                        
                        <a href="/staff/transactions/waste"
                            class="flex items-center p-3 rounded-lg transition duration-200 <?= $pages === "wasteTransaction" ? "bg-white/20 font-semibold" : "hover:bg-white/10" ?>">
                            <span class="text-lg mr-3">♻️</span>
                            <span>ฝากขยะ</span>
                        </a>
                        
                        <a href="/staff/transactions/clear_waste"
                            class="flex items-center p-3 rounded-lg transition duration-200 <?= $pages === "clearWasteTransaction" ? "bg-white/20 font-semibold" : "hover:bg-white/10" ?>">
                            <span class="text-lg mr-3">🧹</span>
                            <span>เคลียร์ยอด</span>
                        </a>
                    </nav>
                </div>
            </aside>

            <div x-show="sidebarOpen" x-transition:opacity @click="sidebarOpen = false"
                class="fixed inset-0 z-10 bg-black opacity-50 lg:hidden">
            </div>

            <main class="flex-1 p-6 md:p-8 h-screen-minus-nav w-100">
                <?php include $viewPath; ?>
            </main>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="<?= $script ?? "" ?>"></script>
    <script type="module" src="<?= $module ?? "" ?>"></script>
</body>

</html>