<!DOCTYPE html>
<html lang="th">

<head>
    <title><?= $title ?? '' ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');

        .noto-sans-thai {
            font-family: "Noto Sans Thai", sans-serif;
        }

        .open-sans {
            font-family: "Open Sans", sans-serif;
        }
    </style>

</head>

<body>
    <div class="flex bg-gray-100 min-h-screen min-w-screen noto-sans-thai">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-white transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">

            <div class="flex items-center justify-between h-16 px-6 bg-white shadow-sm">
                <h2 class="text-xl font-semibold ">TRASH BANK</h2>
                <button id="closeSidebar" class="text-gray-700 lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24">
                        <g fill="none" stroke="#959595ff" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2">
                            <path d="M5 12H3l9-9l9 9h-2M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-7" />
                            <path d="M9 21v-6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v6" />
                        </g>
                    </svg>
                </button>
            </div>

            <nav class="mt-8 px-4">
                <ul class="space-y-2">
                    <li>
                        <a href="#"
                            class="flex space-x-1 items-center px-4 py-3 text-gray-600 hover:bg-gray-800  bg-gray-200 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                                <g fill="none" stroke="#959595ff" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2">
                                    <path d="M5 12H3l9-9l9 9h-2M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-7" />
                                    <path d="M9 21v-6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v6" />
                                </g>
                            </svg>
                            <span>แดชบอร์ด</span>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="flex space-x-1 items-center px-4 py-3 text-gray-700 hover:bg-gray-800 hover:text-white rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1.5em" height="1.5em" viewBox="0 0 24 24">
                                <path fill="none" stroke="#959595ff" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0-8 0M6 21v-2a4 4 0 0 1 4-4h3.5m4.92.61a2.1 2.1 0 0 1 2.97 2.97L18 22h-3v-3z" />
                            </svg>
                            <span>จัดการผู้ใช้งาน</span>
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-800 hover:text-white rounded-lg transition">
                            รายงาน1
                        </a>
                    </li>
                    <li>
                        <a href="#"
                            class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-800 hover:text-white rounded-lg transition">
                            ตั้งค่า
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Overlay (มือถือ) -->
        <div id="overlay" class="fixed inset-0 bg-black transition-all duration-300 z-40 lg:hidden 
            opacity-20 pointer-events-none">
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">

            <!-- Header -->
            <header class="bg-white shadow-sm">
                <div class="flex items-center justify-between h-16 px-6 py-4">
                    <button id="openSidebar" class="text-gray-600 lg:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="text-2xl font-semibold text-gray-800">แดชบอร์ด</h1>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-600">สวัสดี, ผู้ดูแลระบบ</span>
                        <img class="w-10 h-10 rounded-full" src="https://randomuser.me/api/portraits/men/32.jpg"
                            alt="avatar">
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <?php include $viewPath; ?>
            </main>
        </div>
    </div>
    <script type="text/javascript" src="<?= $script ?>"></script>
</body>

</html>