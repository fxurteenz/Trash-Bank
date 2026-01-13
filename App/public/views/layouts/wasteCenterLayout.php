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

        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .card-shadow {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07), 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        /* Smooth animations */
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .animate-slide-in {
            animation: slideInDown 0.3s ease-out;
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Badge styles */
        .badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #78350f;
        }

        .badge-danger {
            background-color: #fee2e2;
            color: #7f1d1d;
        }

        .badge-info {
            background-color: #dbeafe;
            color: #0c2340;
        }

        /* Input focus ring */
        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            ring: 2px solid var(--primary);
        }
    </style>
</head>

<body class="bg-gradient-to-b from-slate-50 to-green-50 min-h-screen">
    <div x-data="{ 
        sidebarOpen: window.innerWidth >= 1024,
        mobileMenuOpen: false,
        profileMenuOpen: false,
        notificationCount: 0
    }" class="flex flex-col min-h-screen">

        <!-- Header Navigation -->
        <header class="sticky top-0 z-40 glass-effect border-b border-slate-200">
            <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo & Brand -->
                    <div class="flex items-center gap-3">
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="hidden lg:flex items-center justify-center w-10 h-10 rounded-lg hover:bg-slate-100 transition-colors">
                            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <a href="/" class="flex items-center gap-2 group">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white font-bold text-lg">
                                ♻️
                            </div>
                            <div class="hidden sm:block">
                                <h1 class="text-xl font-bold text-emerald-700">Trash Bank</h1>
                                <p class="text-xs text-slate-500">ศูนย์บริหารขยะ</p>
                            </div>
                        </a>
                    </div>

                    <!-- Center - Page Title -->
                    <div class="hidden md:block text-center">
                        <h2 class="text-lg font-semibold text-slate-800"><?= $title ?? '' ?></h2>
                    </div>

                    <!-- Right - User Menu -->
                    <div class="flex items-center gap-4">
                        <!-- Notifications -->
                        <button class="relative p-2 hover:bg-slate-100 rounded-lg transition-colors">
                            <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span x-show="notificationCount > 0" class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center" x-text="notificationCount"></span>
                        </button>

                        <!-- Profile Dropdown -->
                        <div class="relative" @click.outside="profileMenuOpen = false">
                            <button @click="profileMenuOpen = !profileMenuOpen"
                                class="flex items-center gap-2 px-3 py-2 hover:bg-slate-100 rounded-lg transition-colors">
                                <img src="https://ui-avatars.com/api/?name=Admin&background=10b981&color=fff" 
                                    class="w-8 h-8 rounded-full">
                                <div class="hidden sm:block text-left">
                                    <p class="text-sm font-medium text-slate-900">ผู้ดูแล</p>
                                    <p class="text-xs text-slate-500">ศูนย์กลาง</p>
                                </div>
                                <svg class="w-4 h-4 text-slate-400 transition-transform" :class="{'rotate-180': profileMenuOpen}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="profileMenuOpen"
                                x-transition
                                class="absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white border border-slate-200 py-2 animate-slide-in">
                                <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">⚙️ ตั้งค่า</a>
                                <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">👤 โปรไฟล์</a>
                                <hr class="my-2">
                                <a href="/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">🚪 ออกจากระบบ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Wrapper -->
        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <aside x-show="sidebarOpen"
                class="hidden lg:block w-64 bg-white border-r border-slate-200 overflow-y-auto">
                <nav class="p-4 space-y-2">
                    <div class="mb-6">
                        <p class="px-4 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">เมนู</p>
                    </div>

                    <a href="/waste_center" class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        <span class="text-sm font-medium">หน้าหลัก</span>
                    </a>

                    <!-- Transactions Section -->
                    <div class="mt-6">
                        <p class="px-4 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">ธุรกรรม</p>
                        
                        <a href="/waste_center/transactions/waste_deposit_pos" 
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm font-medium">🏪 ฝากขยะ POS</span>
                        </a>

                        <a href="/waste_center/transactions/waste_sale" 
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                            </svg>
                            <span class="text-sm font-medium">💰 ขายขยะ POS</span>
                        </a>

                        <a href="/waste_center/transactions/waste_sale_history" 
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000-2H3a1 1 0 00-1 1v12a1 1 0 001 1h14a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 000 2 2 2 0 012 2v3H4V5zm12 4H4v7h12V9z" />
                            </svg>
                            <span class="text-sm font-medium">📋 ประวัติการขาย</span>
                        </a>
                    </div>

                    <!-- Management Section -->
                    <div class="mt-6">
                        <p class="px-4 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">จัดการ</p>

                        <a href="/waste_center/manage/waste_type" 
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2 4a1 1 0 011-1h6a1 1 0 011 1v12a1 1 0 11-2 0V5H3a1 1 0 01-1-1zm8 0a1 1 0 011-1h6a1 1 0 011 1v12a1 1 0 11-2 0V5h-4a1 1 0 01-1-1z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm font-medium">🏷️ ประเภทขยะ</span>
                        </a>

                        <a href="/waste_center/manage/waste_transaction" 
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.5 1.5H5.75A2.25 2.25 0 003.5 3.75v12.5A2.25 2.25 0 005.75 18.5h8.5a2.25 2.25 0 002.25-2.25V6.5" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                            <span class="text-sm font-medium">📊 ประวัติการฝาก</span>
                        </a>

                        <a href="/waste_center/manage/rewards" 
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                            <span class="text-sm font-medium">🎁 รางวัล</span>
                        </a>
                    </div>
                </nav>
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-auto">
                <div class="p-4 sm:p-6 lg:p-8">
                    <?= $view ?? '' ?>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Close mobile menu when navigation happens
        document.addEventListener('click', function(event) {
            if (!event.target.closest('nav')) {
                document.querySelector('[x-data]')?.dispatchEvent(new Event('click'));
            }
        });
    </script>
</body>

</html>
