<!DOCTYPE html>
<html lang="th" class="h-full w-full scroll-smooth">

<head>
    <title><?= $title ?? 'Trash Bank' ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- <link href="/assets/output.css" rel="stylesheet"> -->
    <script defer src="/js/alpine-collapse.min.js"></script>
    <script defer src="/js/alpine.min.js"></script>
    <script type="text/javascript" src="/js/lucide.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&family=Noto+Serif+Thai:wght@100..900&family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');

        .bg-alabuster {
            background-color: #FAFAFA;
        }

        .bg-smoke {
            background-color: #F5F5F5;
        }

        html,
        body {
            font-family: "Sarabun", sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        button,
        a {
            font-family: "Noto Sans Thai", sans-serif;
            font-optical-sizing: auto;
        }

        input,
        select,
        textarea {
            font-family: "Sarabun", sans-serif;
        }

        body {
            /* background-image: linear-gradient(135deg, #f8fafc 20%, #FBF6F6 100%); */
            background-color: #FAFAFA;
        }

        aside,
        header {
            background-color: #ffffff;
        }

        [x-cloak] {
            display: none !important;
        }

        .nav-item {
            transition: transform 0.15s ease-in-out, background-color 0.15s ease-in-out, color 0.15s ease-in-out, border-color 0.15s ease-in-out;
        }

        .nav-item:hover {
            transform: translateX(4px);
        }
    </style>
</head>

<body class="min-h-screen bg-fixed">

    <div x-data="{ sidebarOpen: window.innerWidth > 1366, profileMenuOpen: false }"
        @resize.window="sidebarOpen = window.innerWidth > 1366" class="min-h-screen flex flex-col">

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
                    <img src="/assets/images/waste_bankFullLogo.png" class="h-10" alt="BRU Waste Bank">
                </a>
            </div>

            <div class="hidden md:block text-center">
                <h2 class="text-lg font-semibold text-slate-800"><?= $title ?? '' ?></h2>
            </div>

            <div class="relative" @click.away="profileMenuOpen = false">
                <button @click="profileMenuOpen = !profileMenuOpen"
                    class="flex items-center gap-2 px-3 py-2 hover:bg-slate-100 rounded-lg transition-colors">
                    <i data-lucide="circle-user-round" class="w-6 h-6"></i>
                    <div class="hidden sm:block text-left">
                        <p class="text-md font-medium text-slate-900">
                            <?= $user->member_name ?? 'เพิ่มชื่อผู้ใช่งาน' ?>
                        </p>
                        <p class="text-xs text-slate-500">
                            <?php echo $user->role_name_th ?? "ผู้ดูแลระบบ"; ?>
                        </p>
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
                    <!-- <a href="#"
                        class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 flex justify-between px-3"><i
                            data-lucide="settings"></i> ตั้งค่า</a>
                    <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 flex justify-between"><i
                            data-lucide="user"></i> โปรไฟล์</a>
                    <hr class="my-1"> -->
                    <a href="/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex justify-between">
                        <i data-lucide="log-out"></i>ออกจากระบบ
                    </a>
                </div>
            </div>
        </header>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed top-16 bottom-0 left-0 z-20 w-64 bg-white border-r border-slate-200 transform transition-transform duration-150 ease-in-out overflow-auto scrollbar-[5px] scrollbar-thumb-gray-300 scrollbar-track-gray-400">
            <!-- <div class="border-b-2 border-slate-400 w-full p-2 text-center">
                <h3 class="text-gray-600 text-lg flex items-center gap-2 justify-center">
                    <i data-lucide="square-user-round"></i>
                    <?php echo $user->member_name ?? "ตั้งชื่อผู้ใช้งาน"; ?>
                </h3>
                <span class="text-xs text-gray-400">
                    ผู้ดูแลระบบ
                </span>
            </div> -->
            <nav class="space-y-2">
                <div class="mb-6 mt-6">
                    <p class="px-4 text-xs font-semibold text-slate-500">เมนู</p>
                </div>

                <?php if ($page === "dashboard"): ?>
                    <a href="/admin"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg bg-gray-50 text-emerald-700 transition-colors group border-l-8 border-emerald-600">
                        <svg class="w-5 h-5 text-emerald-600 group-hover:text-emerald-700" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        <span class="text-sm font-medium">หน้าหลัก</span>
                    </a>
                <?php else: ?>
                    <a href="/admin"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg hover:bg-gray-100 text-slate-700 hover:text-emerald-700 transition-colors group border-l-8 border-transparent hover:border-emerald-600">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                        </svg>
                        <span class="text-sm font-medium">หน้าหลัก</span>
                    </a>
                <?php endif; ?>

                <div class="mt-6">
                    <p class="px-4 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">ธุรกรรม</p>
                    <?php if ($page === "wasteTransaction"): ?>
                        <a href="/admin/transactions/waste"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg bg-gray-50 text-emerald-700 transition-colors group border-l-8 border-emerald-600">
                            <svg class="w-5 h-5 text-emerald-600 group-hover:text-emerald-700"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5">
                                    <path stroke-linejoin="round"
                                        d="M12 22c-.818 0-1.6-.325-3.163-.974C4.946 19.41 3 18.602 3 17.243V7.745M12 22c.818 0 1.6-.325 3.163-.974C19.054 19.41 21 18.602 21 17.243V7.745M12 22v-9.831M3 7.745c0 .603.802.985 2.405 1.747l2.92 1.39C10.13 11.74 11.03 12.17 12 12.17M3 7.745c0-.604.802-.986 2.405-1.748L7.5 5M21 7.745c0 .603-.802.985-2.405 1.747l-2.92 1.39C13.87 11.74 12.97 12.17 12 12.17m9-4.424c0-.604-.802-.986-2.405-1.748L16.5 5M6 13.152l2 .983" />
                                    <path
                                        d="M12.004 2v7m0 0c.263.004.522-.18.714-.405L14 7.062M12.004 9c-.254-.003-.511-.186-.714-.405L10 7.062" />
                                </g>
                            </svg>
                            <span class="text-sm font-medium">ฝากขยะ</span>
                        </a>
                    <?php else: ?>
                        <a href="/admin/transactions/waste"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg hover:bg-gray-100 text-slate-700 hover:text-emerald-700 transition-colors group border-l-8 border-transparent hover:border-emerald-600">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5">
                                    <path stroke-linejoin="round"
                                        d="M12 22c-.818 0-1.6-.325-3.163-.974C4.946 19.41 3 18.602 3 17.243V7.745M12 22c.818 0 1.6-.325 3.163-.974C19.054 19.41 21 18.602 21 17.243V7.745M12 22v-9.831M3 7.745c0 .603.802.985 2.405 1.747l2.92 1.39C10.13 11.74 11.03 12.17 12 12.17M3 7.745c0-.604.802-.986 2.405-1.748L7.5 5M21 7.745c0 .603-.802.985-2.405 1.747l-2.92 1.39C13.87 11.74 12.97 12.17 12 12.17m9-4.424c0-.604-.802-.986-2.405-1.748L16.5 5M6 13.152l2 .983" />
                                    <path
                                        d="M12.004 2v7m0 0c.263.004.522-.18.714-.405L14 7.062M12.004 9c-.254-.003-.511-.186-.714-.405L10 7.062" />
                                </g>
                            </svg>
                            <span class="text-sm font-medium">ฝากขยะ</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($page === "wasteClearance"): ?>
                        <a href="/admin/transactions/clear_waste"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg bg-gray-50 text-amber-700 transition-colors group border-l-8 border-amber-600">
                            <svg class="w-5 h-5 text-amber-600 group-hover:text-amber-700"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M5 19V5zv-.112zm0 2q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h14q.825 0 1.413.588T21 5v7q0 .425-.288.713T20 13t-.712-.288T19 12V5H5v14h6q.425 0 .713.288T12 20t-.288.713T11 21zm12.35-1.825l3.525-3.55q.3-.3.713-.3t.712.3t.3.713t-.3.712l-4.25 4.25q-.3.3-.712.3t-.713-.3L14.5 19.175q-.275-.3-.275-.712t.3-.713t.7-.3t.7.3zM8 13q.425 0 .713-.288T9 12t-.288-.712T8 11t-.712.288T7 12t.288.713T8 13m0-4q.425 0 .713-.288T9 8t-.288-.712T8 7t-.712.288T7 8t.288.713T8 9m8 4q.425 0 .713-.288T17 12t-.288-.712T16 11h-4q-.425 0-.712.288T11 12t.288.713T12 13zm0-4q.425 0 .713-.288T17 8t-.288-.712T16 7h-4q-.425 0-.712.288T11 8t.288.713T12 9z"
                                    stroke-width="0.5" stroke="currentColor" />
                            </svg>
                            <span class="text-sm font-medium">เคลียร์ขยะ</span>
                        </a>
                    <?php else: ?>
                        <a href="/admin/transactions/clear_waste"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg hover:bg-gray-50 text-slate-700 hover:text-amber-700 transition-colors group border-l-8 border-transparent hover:border-amber-600">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-amber-600"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M5 19V5zv-.112zm0 2q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h14q.825 0 1.413.588T21 5v7q0 .425-.288.713T20 13t-.712-.288T19 12V5H5v14h6q.425 0 .713.288T12 20t-.288.713T11 21zm12.35-1.825l3.525-3.55q.3-.3.713-.3t.712.3t.3.713t-.3.712l-4.25 4.25q-.3.3-.712.3t-.713-.3L14.5 19.175q-.275-.3-.275-.712t.3-.713t.7-.3t.7.3zM8 13q.425 0 .713-.288T9 12t-.288-.712T8 11t-.712.288T7 12t.288.713T8 13m0-4q.425 0 .713-.288T9 8t-.288-.712T8 7t-.712.288T7 8t.288.713T8 9m8 4q.425 0 .713-.288T17 12t-.288-.712T16 11h-4q-.425 0-.712.288T11 12t.288.713T12 13zm0-4q.425 0 .713-.288T17 8t-.288-.712T16 7h-4q-.425 0-.712.288T11 8t.288.713T12 9z"
                                    stroke-width="0.5" stroke="currentColor" />
                            </svg>
                            <span class="text-sm font-medium">เคลียร์ขยะ</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($page === "wasteSale"): ?>
                        <a href="/admin/transactions/waste_sale"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg bg-gray-50 text-sky-700 transition-colors group border-l-8 border-sky-600">
                            <svg class="w-5 h-5 text-sky-600 group-hover:text-sky-700" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                            </svg>
                            <span class="text-sm font-medium">จำหน่ายขยะ</span>
                        </a>
                    <?php else: ?>
                        <a href="/admin/transactions/waste_sale"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg hover:bg-gray-50 text-slate-700 hover:text-sky-700 transition-colors group border-l-8 border-transparent hover:border-sky-600">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-sky-600" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                            </svg>
                            <span class="text-sm font-medium">จำหน่ายขยะ</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($page === "donationTransaction"): ?>
                        <a href="/admin/transactions/donation"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg bg-gray-50 text-purple-700 transition-colors group border-l-8 border-purple-600">
                            <svg class="w-5 h-5 text-purple-600 group-hover:text-purple-700"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M4 21h9.62a4 4 0 0 0 3.037-1.397l5.102-5.952a1 1 0 0 0-.442-1.6l-1.968-.656a3.04 3.04 0 0 0-2.823.503l-3.185 2.547l-.617-1.235A3.98 3.98 0 0 0 9.146 11H4c-1.103 0-2 .897-2 2v6c0 1.103.897 2 2 2m0-8h5.146c.763 0 1.448.423 1.789 1.105l.447.895H7v2h6.014a1 1 0 0 0 .442-.11l.003-.001l.004-.002h.003l.002-.001h.004l.001-.001c.009.003.003-.001.003-.001c.01 0 .002-.001.002-.001h.001l.002-.001l.003-.001l.002-.001l.002-.001l.003-.001l.002-.001c.003 0 .001-.001.002-.001l.003-.002l.002-.001l.002-.001l.003-.001l.002-.001h.001l.002-.001h.001l.002-.001l.002-.001c.009-.001.003-.001.003-.001l.002-.001a1 1 0 0 0 .11-.078l4.146-3.317c.262-.208.623-.273.94-.167l.557.186l-4.133 4.823a2.03 2.03 0 0 1-1.52.688H4zM16 2h-.017c-.163.002-1.006.039-1.983.705c-.951-.648-1.774-.7-1.968-.704L12.002 2h-.004c-.801 0-1.555.313-2.119.878C9.313 3.445 9 4.198 9 5s.313 1.555.861 2.104l3.414 3.586a1.006 1.006 0 0 0 1.45-.001l3.396-3.568C18.688 6.555 19 5.802 19 5s-.313-1.555-.878-2.121A2.98 2.98 0 0 0 16.002 2zm1 3c0 .267-.104.518-.311.725L14 8.55l-2.707-2.843C11.104 5.518 11 5.267 11 5s.104-.518.294-.708A.98.98 0 0 1 11.979 4c.025.001.502.032 1.067.485q.121.098.247.222l.707.707l.707-.707q.126-.124.247-.222c.529-.425.976-.478 1.052-.484a1 1 0 0 1 .701.292c.189.189.293.44.293.707"
                                    stroke-width="0.5" stroke="currentColor" />
                            </svg>
                            <span class="text-sm font-medium">รับของบริจาค</span>
                        </a>
                    <?php else: ?>
                        <a href="/admin/transactions/donation"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg hover:bg-gray-100 text-slate-700 hover:text-purple-700 transition-colors group border-l-8 border-transparent hover:border-purple-600">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-purple-600"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M4 21h9.62a4 4 0 0 0 3.037-1.397l5.102-5.952a1 1 0 0 0-.442-1.6l-1.968-.656a3.04 3.04 0 0 0-2.823.503l-3.185 2.547l-.617-1.235A3.98 3.98 0 0 0 9.146 11H4c-1.103 0-2 .897-2 2v6c0 1.103.897 2 2 2m0-8h5.146c.763 0 1.448.423 1.789 1.105l.447.895H7v2h6.014a1 1 0 0 0 .442-.11l.003-.001l.004-.002h.003l.002-.001h.004l.001-.001c.009.003.003-.001.003-.001c.01 0 .002-.001.002-.001h.001l.002-.001l.003-.001l.002-.001l.002-.001l.003-.001l.002-.001c.003 0 .001-.001.002-.001l.003-.002l.002-.001l.002-.001l.003-.001l.002-.001h.001l.002-.001h.001l.002-.001l.002-.001c.009-.001.003-.001.003-.001l.002-.001a1 1 0 0 0 .11-.078l4.146-3.317c.262-.208.623-.273.94-.167l.557.186l-4.133 4.823a2.03 2.03 0 0 1-1.52.688H4zM16 2h-.017c-.163.002-1.006.039-1.983.705c-.951-.648-1.774-.7-1.968-.704L12.002 2h-.004c-.801 0-1.555.313-2.119.878C9.313 3.445 9 4.198 9 5s.313 1.555.861 2.104l3.414 3.586a1.006 1.006 0 0 0 1.45-.001l3.396-3.568C18.688 6.555 19 5.802 19 5s-.313-1.555-.878-2.121A2.98 2.98 0 0 0 16.002 2zm1 3c0 .267-.104.518-.311.725L14 8.55l-2.707-2.843C11.104 5.518 11 5.267 11 5s.104-.518.294-.708A.98.98 0 0 1 11.979 4c.025.001.502.032 1.067.485q.121.098.247.222l.707.707l.707-.707q.126-.124.247-.222c.529-.425.976-.478 1.052-.484a1 1 0 0 1 .701.292c.189.189.293.44.293.707"
                                    stroke-width="0.5" stroke="currentColor" />
                            </svg>
                            <span class="text-sm font-medium">รับของบริจาค</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($page === "redeemItemTransaction"): ?>
                        <a href="/admin/transactions/redeem_item"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg bg-gray-50 text-pink-700 transition-colors group border-l-8 border-pink-600">
                            <svg class="w-5 h-5 text-pink-600 group-hover:text-pink-700" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M11.3 8.3L9.2 6.2q-.3-.3-.3-.7t.3-.7l2.1-2.1q.3-.3.7-.3t.7.3l2.1 2.1q.3.3.3.7t-.3.7l-2.1 2.1q-.3.3-.7.3t-.7-.3M2 20q-.425 0-.712-.288T1 19v-3q0-.85.588-1.425T3 14h3.275q.5 0 .95.25t.725.675q.725.975 1.788 1.525T12 17q1.225 0 2.288-.55t1.762-1.525q.325-.425.763-.675t.912-.25H21q.85 0 1.425.575T23 16v3q0 .425-.288.713T22 20h-5q-.425 0-.712-.288T16 19v-1.275q-.875.625-1.888.95T12 19q-1.075 0-2.1-.337T8 17.7V19q0 .425-.288.713T7 20zm2-7q-1.25 0-2.125-.875T1 10q0-1.275.875-2.137T4 7q1.275 0 2.138.863T7 10q0 1.25-.862 2.125T4 13m16 0q-1.25 0-2.125-.875T17 10q0-1.275.875-2.137T20 7q1.275 0 2.138.863T23 10q0 1.25-.862 2.125T20 13"
                                    stroke-width="0.5" stroke="currentColor" />
                            </svg>
                            <span class="text-sm font-medium">แลกของรางวัล</span>
                        </a>
                    <?php else: ?>
                        <a href="/admin/transactions/redeem_item"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg hover:bg-gray-100 text-slate-700 hover:text-pink-700 transition-colors group border-l-8 border-transparent hover:border-pink-600">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-pink-600" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M11.3 8.3L9.2 6.2q-.3-.3-.3-.7t.3-.7l2.1-2.1q.3-.3.7-.3t.7.3l2.1 2.1q.3.3.3.7t-.3.7l-2.1 2.1q-.3.3-.7.3t-.7-.3M2 20q-.425 0-.712-.288T1 19v-3q0-.85.588-1.425T3 14h3.275q.5 0 .95.25t.725.675q.725.975 1.788 1.525T12 17q1.225 0 2.288-.55t1.762-1.525q.325-.425.763-.675t.912-.25H21q.85 0 1.425.575T23 16v3q0 .425-.288.713T22 20h-5q-.425 0-.712-.288T16 19v-1.275q-.875.625-1.888.95T12 19q-1.075 0-2.1-.337T8 17.7V19q0 .425-.288.713T7 20zm2-7q-1.25 0-2.125-.875T1 10q0-1.275.875-2.137T4 7q1.275 0 2.138.863T7 10q0 1.25-.862 2.125T4 13m16 0q-1.25 0-2.125-.875T17 10q0-1.275.875-2.137T20 7q1.275 0 2.138.863T23 10q0 1.25-.862 2.125T20 13"
                                    stroke-width="0.5" stroke="currentColor" />
                            </svg>
                            <span class="text-sm font-medium">แลกของรางวัล</span>
                        </a>
                    <?php endif; ?>
                </div>


                <div class="mt-6">
                    <p class="px-4 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">จัดการ</p>
                    <?php if ($page === "manageUsers" || $page === "manageUserDetail"): ?>
                        <a href="/admin/manage/users"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg bg-gray-50 text-pink-700 transition-colors group border-l-8 border-pink-600">
                            <svg class="w-5 h-5 text-pink-600 group-hover:text-pink-700" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 16 16">
                                <path fill="currentColor"
                                    d="M7.5 9a2 2 0 0 1 2 2c0 .965-.592 1.73-1.411 2.23C7.27 13.728 6.175 14 5 14s-2.27-.272-3.089-.77C1.091 12.73.5 11.965.5 11a2 2 0 0 1 2-2zm-5 1a1 1 0 0 0-1 1c0 .508.304.992.932 1.375S3.966 13 5 13s1.94-.242 2.568-.625S8.5 11.508 8.5 11a1 1 0 0 0-1-1zm11.652-.992A1.5 1.5 0 0 1 15.5 10.5c0 .771-.47 1.409-1.101 1.83c-.636.424-1.486.67-2.399.67c-.699 0-1.36-.146-1.917-.403c.16-.287.28-.601.35-.943c.423.21.964.346 1.567.346c.743 0 1.394-.202 1.844-.502c.453-.302.656-.665.656-.998a.5.5 0 0 0-.4-.49L14 10h-3.674a3 3 0 0 0-.575-.979A1.5 1.5 0 0 1 9.999 9h4zm-1.92-5.5a2.253 2.253 0 0 1 2.022 2.241l-.012.23A2.253 2.253 0 0 1 12.002 8l-.23-.012a2.25 2.25 0 0 1-2.01-2.01l-.012-.23a2.25 2.25 0 0 1 2.252-2.252zM5 2.5A2.75 2.75 0 1 1 5 8a2.75 2.75 0 0 1 0-5.5m7.002 1.997a1.252 1.252 0 1 0 0 2.504a1.252 1.252 0 0 0 0-2.504M5 3.5A1.75 1.75 0 1 0 5 7a1.75 1.75 0 0 0 0-3.5"
                                    stroke-width="0.5" stroke="currentColor" />
                            </svg>
                            <span class="text-sm font-medium">ผู้ใช้งาน</span>
                            <?php if ($page === "manageUserDetail"): ?>
                                <a class="flex items-center gap-3 px-8 py-1 rounded-lg text-pink-600">
                                    <span class="text-xs font-regular"> - รายละเอียดผู้ใช้งาน</span>
                                </a>
                            <?php endif; ?>
                        </a>
                    <?php else: ?>
                        <a href="/admin/manage/users"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg hover:bg-gray-100 text-slate-700 hover:text-pink-700 transition-colors group border-l-8 border-transparent hover:border-pink-600">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-pink-600" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 16 16">
                                <path fill="currentColor"
                                    d="M7.5 9a2 2 0 0 1 2 2c0 .965-.592 1.73-1.411 2.23C7.27 13.728 6.175 14 5 14s-2.27-.272-3.089-.77C1.091 12.73.5 11.965.5 11a2 2 0 0 1 2-2zm-5 1a1 1 0 0 0-1 1c0 .508.304.992.932 1.375S3.966 13 5 13s1.94-.242 2.568-.625S8.5 11.508 8.5 11a1 1 0 0 0-1-1zm11.652-.992A1.5 1.5 0 0 1 15.5 10.5c0 .771-.47 1.409-1.101 1.83c-.636.424-1.486.67-2.399.67c-.699 0-1.36-.146-1.917-.403c.16-.287.28-.601.35-.943c.423.21.964.346 1.567.346c.743 0 1.394-.202 1.844-.502c.453-.302.656-.665.656-.998a.5.5 0 0 0-.4-.49L14 10h-3.674a3 3 0 0 0-.575-.979A1.5 1.5 0 0 1 9.999 9h4zm-1.92-5.5a2.253 2.253 0 0 1 2.022 2.241l-.012.23A2.253 2.253 0 0 1 12.002 8l-.23-.012a2.25 2.25 0 0 1-2.01-2.01l-.012-.23a2.25 2.25 0 0 1 2.252-2.252zM5 2.5A2.75 2.75 0 1 1 5 8a2.75 2.75 0 0 1 0-5.5m7.002 1.997a1.252 1.252 0 1 0 0 2.504a1.252 1.252 0 0 0 0-2.504M5 3.5A1.75 1.75 0 1 0 5 7a1.75 1.75 0 0 0 0-3.5"
                                    stroke-width="0.5" stroke="currentColor" />
                            </svg>
                            <span class="text-sm font-medium">ผู้ใช้งาน</span>
                        </a>
                    <?php endif; ?>
                    <?php if ($page === "manageFaculty"): ?>
                        <a href="/admin/manage/faculty"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg bg-gray-50 text-purple-700 transition-colors group border-l-8 border-purple-600">
                            <svg class="w-5 h-5 text-purple-600 group-hover:text-purple-700"
                                xmlns="http://www.w3.org/2000/svg" width="30" height="24" viewBox="0 0 640 512">
                                <path fill="currentColor"
                                    d="m335.5 4l288 160c15.4 8.6 21 28.1 12.4 43.5s-28.1 21-43.5 12.4L320 68.6L47.5 220c-15.4 8.6-34.9 3-43.5-12.4s-3-34.9 12.4-43.5L304.5 4c9.7-5.4 21.4-5.4 31.1 0zM320 160a40 40 0 1 1 0 80a40 40 0 1 1 0-80m-176 96a40 40 0 1 1 0 80a40 40 0 1 1 0-80m312 40a40 40 0 1 1 80 0a40 40 0 1 1-80 0M226.9 491.4L200 441.5V480c0 17.7-14.3 32-32 32h-48c-17.7 0-32-14.3-32-32v-38.5l-26.9 49.9c-6.3 11.7-20.8 16-32.5 9.8s-16-20.8-9.8-32.5l37.9-70.3c15.3-28.5 45.1-46.3 77.5-46.3h19.5c16.3 0 31.9 4.5 45.4 12.6l33.6-62.3c15.3-28.5 45.1-46.3 77.5-46.3h19.5c32.4 0 62.1 17.8 77.5 46.3l33.6 62.3c13.5-8.1 29.1-12.6 45.4-12.6h19.5c32.4 0 62.1 17.8 77.5 46.3l37.9 70.3c6.3 11.7 1.9 26.2-9.8 32.5s-26.2 1.9-32.5-9.8L552 441.5V480c0 17.7-14.3 32-32 32h-48c-17.7 0-32-14.3-32-32v-38.5l-26.9 49.9c-6.3 11.7-20.8 16-32.5 9.8s-16-20.8-9.8-32.5l36.3-67.5c-1.7-1.7-3.2-3.6-4.3-5.8L376 345.5V400c0 17.7-14.3 32-32 32h-48c-17.7 0-32-14.3-32-32v-54.5l-26.9 49.9c-1.2 2.2-2.6 4.1-4.3 5.8l36.3 67.5c6.3 11.7 1.9 26.2-9.8 32.5s-26.2 1.9-32.5-9.8z"
                                    stroke-width="13" stroke="currentColor" />
                            </svg>
                            <span class="text-sm font-medium">คณะ/สาขา</span>
                        </a>
                    <?php else: ?>
                        <a href="/admin/manage/faculty"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg hover:bg-gray-100 text-slate-700 hover:text-purple-700 transition-colors group border-l-8 border-transparent hover:border-purple-600">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-purple-600"
                                xmlns="http://www.w3.org/2000/svg" width="30" height="24" viewBox="0 0 640 512">
                                <path fill="currentColor"
                                    d="m335.5 4l288 160c15.4 8.6 21 28.1 12.4 43.5s-28.1 21-43.5 12.4L320 68.6L47.5 220c-15.4 8.6-34.9 3-43.5-12.4s-3-34.9 12.4-43.5L304.5 4c9.7-5.4 21.4-5.4 31.1 0zM320 160a40 40 0 1 1 0 80a40 40 0 1 1 0-80m-176 96a40 40 0 1 1 0 80a40 40 0 1 1 0-80m312 40a40 40 0 1 1 80 0a40 40 0 1 1-80 0M226.9 491.4L200 441.5V480c0 17.7-14.3 32-32 32h-48c-17.7 0-32-14.3-32-32v-38.5l-26.9 49.9c-6.3 11.7-20.8 16-32.5 9.8s-16-20.8-9.8-32.5l37.9-70.3c15.3-28.5 45.1-46.3 77.5-46.3h19.5c16.3 0 31.9 4.5 45.4 12.6l33.6-62.3c15.3-28.5 45.1-46.3 77.5-46.3h19.5c32.4 0 62.1 17.8 77.5 46.3l33.6 62.3c13.5-8.1 29.1-12.6 45.4-12.6h19.5c32.4 0 62.1 17.8 77.5 46.3l37.9 70.3c6.3 11.7 1.9 26.2-9.8 32.5s-26.2 1.9-32.5-9.8L552 441.5V480c0 17.7-14.3 32-32 32h-48c-17.7 0-32-14.3-32-32v-38.5l-26.9 49.9c-6.3 11.7-20.8 16-32.5 9.8s-16-20.8-9.8-32.5l36.3-67.5c-1.7-1.7-3.2-3.6-4.3-5.8L376 345.5V400c0 17.7-14.3 32-32 32h-48c-17.7 0-32-14.3-32-32v-54.5l-26.9 49.9c-1.2 2.2-2.6 4.1-4.3 5.8l36.3 67.5c6.3 11.7 1.9 26.2-9.8 32.5s-26.2 1.9-32.5-9.8z"
                                    stroke-width="13" stroke="currentColor" />
                            </svg>
                            <span class="text-sm font-medium">คณะ/สาขา</span>
                        </a>
                    <?php endif; ?>
                    <?php if ($page === "manageWasteType"): ?>
                        <a href="/admin/manage/waste_category"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg bg-gray-50 text-blue-700 transition-colors group border-l-8 border-blue-600">
                            <svg class="w-5 h-5 text-blue-600 group-hover:text-blue-700" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M21.03 3L18 20.31c-.17.96-1 1.69-2 1.69H8c-1 0-1.83-.73-2-1.69L2.97 3zM5.36 5L8 20h8l2.64-15zM9 18v-4h4v4zm4-4.82L9.82 10L13 6.82L16.18 10z"
                                    stroke-width="0.5" stroke="currentColor" />
                            </svg>
                            <span class="text-sm font-medium">หมวดหมู่ขยะ</span>
                        </a>
                    <?php else: ?>
                        <a href="/admin/manage/waste_category"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg hover:bg-gray-100 text-slate-700 hover:text-blue-700 transition-colors group border-l-8 border-transparent hover:border-blue-600">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-600" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M21.03 3L18 20.31c-.17.96-1 1.69-2 1.69H8c-1 0-1.83-.73-2-1.69L2.97 3zM5.36 5L8 20h8l2.64-15zM9 18v-4h4v4zm4-4.82L9.82 10L13 6.82L16.18 10z"
                                    stroke-width="0.5" stroke="currentColor" />
                            </svg>
                            <span class="text-sm font-medium">หมวดหมู่ขยะ</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($page === "manageRewardCategories"): ?>
                        <a href="/admin/manage/reward_category"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg bg-gray-50 text-rose-700 transition-colors group border-l-8 border-rose-600">
                            <svg class="w-5 h-5 text-rose-600 group-hover:text-rose-700" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <g transform="translate(1, 2) scale(0.85)">
                                    <path
                                        d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z" />
                                </g>
                                <rect x="15.5" y="14.5" width="8" height="8" rx="1" />
                                <path d="M19.5 14.5v8" />
                                <path d="M15.5 18.5h8" />
                                <path d="M19.5 14.5c-0.8-1.6 -2.4-1.6 -2.4 0 Z" />
                                <path d="M19.5 14.5c0.8-1.6 2.4-1.6 2.4 0 Z" />
                            </svg>
                            <span class="text-sm font-medium">หมวดหมู่ของรางวัล</span>
                        </a>
                    <?php else: ?>
                        <a href="/admin/manage/reward_category"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg hover:bg-gray-100 text-slate-700 hover:text-rose-700 transition-colors group border-l-8 border-transparent hover:border-rose-600">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-rose-600" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <g transform="translate(1, 2) scale(0.85)">
                                    <path
                                        d="M4 20h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.93a2 2 0 0 1-1.66-.9l-.82-1.2A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13c0 1.1.9 2 2 2Z" />
                                </g>
                                <rect x="15.5" y="14.5" width="8" height="8" rx="1" />
                                <path d="M19.5 14.5v8" />
                                <path d="M15.5 18.5h8" />
                                <path d="M19.5 14.5c-0.8-1.6 -2.4-1.6 -2.4 0 Z" />
                                <path d="M19.5 14.5c0.8-1.6 2.4-1.6 2.4 0 Z" />
                            </svg>
                            <span class="text-sm font-medium">หมวดหมู่ของรางวัล</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($page === "manageRewards" || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/admin/manage/reward') === 0 && strpos($_SERVER['REQUEST_URI'], '_category') === false)): ?>
                        <a href="/admin/manage/reward"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg bg-gray-50 text-orange-700 transition-colors group border-l-8 border-orange-600">
                            <svg class="w-5 h-5 text-orange-600 group-hover:text-orange-700"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 56 56">
                                <path fill="currentColor"
                                    d="M9.66 16.094c-2.742 0-4.453 1.804-4.453 4.664v5.906c0 2.461 1.195 4.148 3.375 4.57v14.578c0 4.43 2.414 6.75 6.844 6.75h25.148c4.43 0 6.844-2.32 6.844-6.75V31.235c2.203-.422 3.375-2.109 3.375-4.57v-5.906c0-2.86-1.57-4.664-4.453-4.664h-4.97c1.313-1.29 2.086-2.977 2.086-4.875c0-4.547-3.586-7.781-8.133-7.781c-3.351 0-6.094 1.851-7.312 5.156c-1.22-3.305-3.985-5.156-7.336-5.156c-4.524 0-8.133 3.234-8.133 7.78c0 1.9.75 3.587 2.062 4.876Zm12.773 0c-3.867 0-5.906-2.274-5.906-4.711c0-2.531 1.875-4.031 4.383-4.031c2.883 0 5.156 2.226 5.156 5.953v2.789Zm11.133 0h-3.633v-2.79c0-3.726 2.274-5.952 5.157-5.952c2.508 0 4.406 1.5 4.406 4.03c0 2.438-2.11 4.712-5.93 4.712m-22.945 3.539h15.305v8.156H10.62c-1.172 0-1.64-.492-1.64-1.664v-4.852c0-1.171.468-1.64 1.64-1.64m34.781 0c1.172 0 1.617.469 1.617 1.64v4.852c0 1.172-.445 1.664-1.617 1.664H30.074v-8.156Zm-30 29.414c-1.968 0-3.046-1.102-3.046-3.047V31.328h13.57v17.719ZM43.645 46c0 1.945-1.079 3.047-3.024 3.047H30.074V31.328h13.57Z"
                                    stroke-width="1.5" stroke="currentColor" />
                            </svg>
                            <span class="text-sm font-medium">รางวัล</span>
                        </a>
                    <?php else: ?>
                        <a href="/admin/manage/reward"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg hover:bg-gray-100 text-slate-700 hover:text-orange-700 transition-colors group border-l-8 border-transparent hover:border-orange-600">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-orange-600"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 56 56">
                                <path fill="currentColor"
                                    d="M9.66 16.094c-2.742 0-4.453 1.804-4.453 4.664v5.906c0 2.461 1.195 4.148 3.375 4.57v14.578c0 4.43 2.414 6.75 6.844 6.75h25.148c4.43 0 6.844-2.32 6.844-6.75V31.235c2.203-.422 3.375-2.109 3.375-4.57v-5.906c0-2.86-1.57-4.664-4.453-4.664h-4.97c1.313-1.29 2.086-2.977 2.086-4.875c0-4.547-3.586-7.781-8.133-7.781c-3.351 0-6.094 1.851-7.312 5.156c-1.22-3.305-3.985-5.156-7.336-5.156c-4.524 0-8.133 3.234-8.133 7.78c0 1.9.75 3.587 2.062 4.876Zm12.773 0c-3.867 0-5.906-2.274-5.906-4.711c0-2.531 1.875-4.031 4.383-4.031c2.883 0 5.156 2.226 5.156 5.953v2.789Zm11.133 0h-3.633v-2.79c0-3.726 2.274-5.952 5.157-5.952c2.508 0 4.406 1.5 4.406 4.03c0 2.438-2.11 4.712-5.93 4.712m-22.945 3.539h15.305v8.156H10.62c-1.172 0-1.64-.492-1.64-1.664v-4.852c0-1.171.468-1.64 1.64-1.64m34.781 0c1.172 0 1.617.469 1.617 1.64v4.852c0 1.172-.445 1.664-1.617 1.664H30.074v-8.156Zm-30 29.414c-1.968 0-3.046-1.102-3.046-3.047V31.328h13.57v17.719ZM43.645 46c0 1.945-1.079 3.047-3.024 3.047H30.074V31.328h13.57Z"
                                    stroke-width="1.5" stroke="currentColor" />
                            </svg>
                            <span class="text-sm font-medium">รางวัล</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($page === "manageBadges" && (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/admin/manage/badge') === 0)): ?>
                        <a href="/admin/manage/badge"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg bg-gray-50 text-yellow-700 transition-colors group border-l-8 border-yellow-500">
                            <svg class="w-5 h-5 text-yellow-600 group-hover:text-yellow-700"
                                xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="8" r="7" />
                                <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88" />
                            </svg>
                            <span class="text-sm font-medium">เหรียญตรา</span>
                        </a>
                    <?php else: ?>
                        <a href="/admin/manage/badge"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg hover:bg-gray-100 text-slate-700 hover:text-yellow-700 transition-colors group border-l-8 border-transparent hover:border-yellow-500">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-yellow-600"
                                xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="8" r="7" />
                                <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88" />
                            </svg>
                            <span class="text-sm font-medium">เหรียญตรา</span>
                        </a>
                    <?php endif; ?>

                </div>

                <div class="mt-6">
                    <p class="px-4 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">คลังขยะ</p>
                    <?php if ($page === "manageWasteStock" && (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/admin/stock/branchwaste') === 0)): ?>
                        <a href="/admin/stock/branchwaste"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg bg-gray-50 text-cyan-700 transition-colors group border-l-8 border-cyan-600">
                            <svg class="w-5 h-5 text-cyan-600 group-hover:text-cyan-700" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 15 15">
                                <path fill="currentColor"
                                    d="M7.5 0c4 0 7.5 3.5 7.5 7.5S11.5 15 7.5 15S0 11.5 0 7.5S3.5 0 7.5 0m0 1C4 1 1 4 1 7.5S4 14 7.5 14S14 11 14 7.5S11 1 7.5 1M2.84 6.98c.24.03 1.43.15 1.72.18c.29.04.17.89-.09.86c-.27-.02-1.44-.14-1.72-.18c-.29-.03-.16-.89.09-.86m7.48 0c.35-.04 1.32-.13 1.7-.16c.39-.04.4.87.06.9c-.35.04-1.28.12-1.64.16c-.37.03-.47-.85-.12-.9m-3.02.11c.09-.27.86-.03.76.28c-.09.32-.42 1.36-.52 1.63c-.1.28-.95.08-.84-.23c.12-.3.52-1.45.6-1.68m2.17 2.42c.29.11 1.37.52 1.61.62c.24.11-.05.93-.31.81c-.32-.11-1.32-.49-1.61-.62c-.3-.13.01-.91.31-.81m-4.77.08c.08.19.54 1.44.61 1.69c.08.25-.68.56-.8.29c-.11-.28-.44-1.34-.54-1.62c-.1-.27.66-.54.73-.36m2.11 1.37c.28.11 1.36.45 1.65.54c.28.09.04.92-.27.82s-1.36-.43-1.65-.53c-.28-.11-.01-.93.27-.83M13 5c-1 1-1.75 1-2.75 0c-1 1-1.75 1-2.75 0c-1 1-1.7 1-2.7 0C3.8 6 3 6 2 5c-.5 1-.5 2-.5 2.5c0 3 2.5 6 6 6s6-3 6-6c0-.5 0-1.5-.5-2.5"
                                    stroke-width="0.5" stroke="currentColor" />
                            </svg>
                            <span class="text-sm font-medium">คลังหน่วยบริการ/ศูนย์ของคุณ</span>
                        </a>
                    <?php else: ?>
                        <a href="/admin/stock/branchwaste"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg hover:bg-gray-100 text-slate-700 hover:text-cyan-700 transition-colors group border-l-8 border-transparent hover:border-cyan-600">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-cyan-600" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 15 15">
                                <path fill="currentColor"
                                    d="M7.5 0c4 0 7.5 3.5 7.5 7.5S11.5 15 7.5 15S0 11.5 0 7.5S3.5 0 7.5 0m0 1C4 1 1 4 1 7.5S4 14 7.5 14S14 11 14 7.5S11 1 7.5 1M2.84 6.98c.24.03 1.43.15 1.72.18c.29.04.17.89-.09.86c-.27-.02-1.44-.14-1.72-.18c-.29-.03-.16-.89.09-.86m7.48 0c.35-.04 1.32-.13 1.7-.16c.39-.04.4.87.06.9c-.35.04-1.28.12-1.64.16c-.37.03-.47-.85-.12-.9m-3.02.11c.09-.27.86-.03.76.28c-.09.32-.42 1.36-.52 1.63c-.1.28-.95.08-.84-.23c.12-.3.52-1.45.6-1.68m2.17 2.42c.29.11 1.37.52 1.61.62c.24.11-.05.93-.31.81c-.32-.11-1.32-.49-1.61-.62c-.3-.13.01-.91.31-.81m-4.77.08c.08.19.54 1.44.61 1.69c.08.25-.68.56-.8.29c-.11-.28-.44-1.34-.54-1.62c-.1-.27.66-.54.73-.36m2.11 1.37c.28.11 1.36.45 1.65.54c.28.09.04.92-.27.82s-1.36-.43-1.65-.53c-.28-.11-.01-.93.27-.83M13 5c-1 1-1.75 1-2.75 0c-1 1-1.75 1-2.75 0c-1 1-1.7 1-2.7 0C3.8 6 3 6 2 5c-.5 1-.5 2-.5 2.5c0 3 2.5 6 6 6s6-3 6-6c0-.5 0-1.5-.5-2.5"
                                    stroke-width="0.5" stroke="currentColor" />
                            </svg>
                            <span class="text-sm font-medium">คลังหน่วยบริการ/ศูนย์ของคุณ</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($page === "manageWasteStock" && (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/admin/stock/centerwaste') === 0)): ?>
                        <a href="/admin/stock/centerwaste"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg bg-gray-50 text-indigo-700 transition-colors group border-l-8 border-indigo-600">
                            <svg class="w-5 h-5 text-indigo-600 group-hover:text-indigo-700"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 15 15">
                                <path fill="currentColor"
                                    d="M7.5 0c4 0 7.5 3.5 7.5 7.5S11.5 15 7.5 15S0 11.5 0 7.5S3.5 0 7.5 0m0 1C4 1 1 4 1 7.5S4 14 7.5 14S14 11 14 7.5S11 1 7.5 1M2.84 6.98c.24.03 1.43.15 1.72.18c.29.04.17.89-.09.86c-.27-.02-1.44-.14-1.72-.18c-.29-.03-.16-.89.09-.86m7.48 0c.35-.04 1.32-.13 1.7-.16c.39-.04.4.87.06.9c-.35.04-1.28.12-1.64.16c-.37.03-.47-.85-.12-.9m-3.02.11c.09-.27.86-.03.76.28c-.09.32-.42 1.36-.52 1.63c-.1.28-.95.08-.84-.23c.12-.3.52-1.45.6-1.68m2.17 2.42c.29.11 1.37.52 1.61.62c.24.11-.05.93-.31.81c-.32-.11-1.32-.49-1.61-.62c-.3-.13.01-.91.31-.81m-4.77.08c.08.19.54 1.44.61 1.69c.08.25-.68.56-.8.29c-.11-.28-.44-1.34-.54-1.62c-.1-.27.66-.54.73-.36m2.11 1.37c.28.11 1.36.45 1.65.54c.28.09.04.92-.27.82s-1.36-.43-1.65-.53c-.28-.11-.01-.93.27-.83M13 5c-1 1-1.75 1-2.75 0c-1 1-1.75 1-2.75 0c-1 1-1.7 1-2.7 0C3.8 6 3 6 2 5c-.5 1-.5 2-.5 2.5c0 3 2.5 6 6 6s6-3 6-6c0-.5 0-1.5-.5-2.5"
                                    stroke-width="0.5" stroke="currentColor" />
                            </svg>
                            <span class="text-sm font-medium">คลังศูนย์รวบรวมขยะ</span>
                        </a>
                    <?php else: ?>
                        <a href="/admin/stock/centerwaste"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg hover:bg-gray-100 text-slate-700 hover:text-indigo-700 transition-colors group border-l-8 border-transparent hover:border-indigo-600">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-600"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 15 15">
                                <path fill="currentColor"
                                    d="M7.5 0c4 0 7.5 3.5 7.5 7.5S11.5 15 7.5 15S0 11.5 0 7.5S3.5 0 7.5 0m0 1C4 1 1 4 1 7.5S4 14 7.5 14S14 11 14 7.5S11 1 7.5 1M2.84 6.98c.24.03 1.43.15 1.72.18c.29.04.17.89-.09.86c-.27-.02-1.44-.14-1.72-.18c-.29-.03-.16-.89.09-.86m7.48 0c.35-.04 1.32-.13 1.7-.16c.39-.04.4.87.06.9c-.35.04-1.28.12-1.64.16c-.37.03-.47-.85-.12-.9m-3.02.11c.09-.27.86-.03.76.28c-.09.32-.42 1.36-.52 1.63c-.1.28-.95.08-.84-.23c.12-.3.52-1.45.6-1.68m2.17 2.42c.29.11 1.37.52 1.61.62c.24.11-.05.93-.31.81c-.32-.11-1.32-.49-1.61-.62c-.3-.13.01-.91.31-.81m-4.77.08c.08.19.54 1.44.61 1.69c.08.25-.68.56-.8.29c-.11-.28-.44-1.34-.54-1.62c-.1-.27.66-.54.73-.36m2.11 1.37c.28.11 1.36.45 1.65.54c.28.09.04.92-.27.82s-1.36-.43-1.65-.53c-.28-.11-.01-.93.27-.83M13 5c-1 1-1.75 1-2.75 0c-1 1-1.75 1-2.75 0c-1 1-1.7 1-2.7 0C3.8 6 3 6 2 5c-.5 1-.5 2-.5 2.5c0 3 2.5 6 6 6s6-3 6-6c0-.5 0-1.5-.5-2.5"
                                    stroke-width="0.5" stroke="currentColor" />
                            </svg>
                            <span class="text-sm font-medium">คลังศูนย์รวบรวมขยะ</span>
                        </a>
                    <?php endif; ?>
                </div>

                <div class="mt-6">
                    <p class="px-4 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        ประวัติการทำรายการ</p>

                    <?php if ($page === "wasteTransactionHistory"): ?>
                        <a href="/admin/history/waste_transaction"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg bg-gray-50 text-emerald-700 transition-colors group border-l-8 border-emerald-600">
                            <svg class="w-5 h-5 text-emerald-600 group-hover:text-emerald-700"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M11 22c-.818 0-1.6-.33-3.163-.99C3.946 19.366 2 18.543 2 17.16V7m9 15V11.355M11 22c.34 0 .646-.057 1-.172M20 7v4.5M18 18l.906-.905M22 18a4 4 0 1 0-8 0a4 4 0 0 0 8 0M7.326 9.691L4.405 8.278C2.802 7.502 2 7.114 2 6.5s.802-1.002 2.405-1.778l2.92-1.413C9.13 2.436 10.03 2 11 2s1.871.436 3.674 1.309l2.921 1.413C19.198 5.498 20 5.886 20 6.5s-.802 1.002-2.405 1.778l-2.92 1.413C12.87 10.564 11.97 11 11 11s-1.871-.436-3.674-1.309M5 12l2 1m9-9L6 9" />
                            </svg>
                            <span class="text-sm font-medium">ประวัติการฝากขยะ</span>
                        </a>
                    <?php else: ?>
                        <a href="/admin/history/waste_transaction"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg hover:bg-gray-100 text-slate-700 hover:text-emerald-700 transition-colors group border-l-8 border-transparent hover:border-emerald-600">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M11 22c-.818 0-1.6-.33-3.163-.99C3.946 19.366 2 18.543 2 17.16V7m9 15V11.355M11 22c.34 0 .646-.057 1-.172M20 7v4.5M18 18l.906-.905M22 18a4 4 0 1 0-8 0a4 4 0 0 0 8 0M7.326 9.691L4.405 8.278C2.802 7.502 2 7.114 2 6.5s.802-1.002 2.405-1.778l2.92-1.413C9.13 2.436 10.03 2 11 2s1.871.436 3.674 1.309l2.921 1.413C19.198 5.498 20 5.886 20 6.5s-.802 1.002-2.405 1.778l-2.92 1.413C12.87 10.564 11.97 11 11 11s-1.871-.436-3.674-1.309M5 12l2 1m9-9L6 9" />
                            </svg>
                            <span class="text-sm font-medium">ประวัติการฝากขยะ</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($page === "clearWasteHistory"): ?>
                        <a href="/admin/history/clear_waste"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg bg-gray-50 text-amber-700 transition-colors group border-l-8 border-amber-600">
                            <svg class="w-5 h-5 text-amber-600 group-hover:text-amber-700" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            <span class="text-sm font-medium">ประวัติเคลียร์ยอดฝาก</span>
                        </a>
                    <?php else: ?>
                        <a href="/admin/history/clear_waste"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg hover:bg-gray-50 text-slate-700 hover:text-amber-700 transition-colors group border-l-8 border-transparent hover:border-amber-600">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-amber-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            <span class="text-sm font-medium">ประวัติเคลียร์ยอดฝาก</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($page === "wasteSaleHistory"): ?>
                        <a href="/admin/history/waste_sale"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg bg-gray-50 text-sky-700 transition-colors group border-l-8 border-sky-600">
                            <svg class="w-5 h-5 text-sky-600 group-hover:text-sky-700" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                            </svg>
                            <span class="text-sm font-medium">ประวัติการจำหน่ายขยะ</span>
                        </a>
                    <?php else: ?>
                        <a href="/admin/history/waste_sale"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg hover:bg-gray-50 text-slate-700 hover:text-sky-700 transition-colors group border-l-8 border-transparent hover:border-sky-600">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-sky-600" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                            </svg>
                            <span class="text-sm font-medium">ประวัติการจำหน่ายขยะ</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($page === "DonationHistory" && (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/admin/history/donation') === 0)): ?>
                        <a href="/admin/history/donation"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg bg-gray-50 text-purple-700 transition-colors group border-l-8 border-purple-600">
                            <svg class="w-5 h-5 text-purple-600 group-hover:text-purple-700"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M4 21h9.62a4 4 0 0 0 3.037-1.397l5.102-5.952a1 1 0 0 0-.442-1.6l-1.968-.656a3.04 3.04 0 0 0-2.823.503l-3.185 2.547l-.617-1.235A3.98 3.98 0 0 0 9.146 11H4c-1.103 0-2 .897-2 2v6c0 1.103.897 2 2 2m0-8h5.146c.763 0 1.448.423 1.789 1.105l.447.895H7v2h6.014a1 1 0 0 0 .442-.11l.003-.001l.004-.002h.003l.002-.001h.004l.001-.001c.009.003.003-.001.003-.001c.01 0 .002-.001.002-.001h.001l.002-.001l.003-.001l.002-.001l.002-.001l.003-.001l.002-.001c.003 0 .001-.001.002-.001l.003-.002l.002-.001l.002-.001l.003-.001l.002-.001h.001l.002-.001h.001l.002-.001l.002-.001c.009-.001.003-.001.003-.001l.002-.001a1 1 0 0 0 .11-.078l4.146-3.317c.262-.208.623-.273.94-.167l.557.186l-4.133 4.823a2.03 2.03 0 0 1-1.52.688H4zM16 2h-.017c-.163.002-1.006.039-1.983.705c-.951-.648-1.774-.7-1.968-.704L12.002 2h-.004c-.801 0-1.555.313-2.119.878C9.313 3.445 9 4.198 9 5s.313 1.555.861 2.104l3.414 3.586a1.006 1.006 0 0 0 1.45-.001l3.396-3.568C18.688 6.555 19 5.802 19 5s-.313-1.555-.878-2.121A2.98 2.98 0 0 0 16.002 2zm1 3c0 .267-.104.518-.311.725L14 8.55l-2.707-2.843C11.104 5.518 11 5.267 11 5s.104-.518.294-.708A.98.98 0 0 1 11.979 4c.025.001.502.032 1.067.485q.121.098.247.222l.707.707l.707-.707q.126-.124.247-.222c.529-.425.976-.478 1.052-.484a1 1 0 0 1 .701.292c.189.189.293.44.293.707"
                                    stroke-width="0.5" stroke="currentColor" />
                            </svg>
                            <span class="text-sm font-medium">ประวัติการรับสิ่งของ</span>
                        </a>
                    <?php else: ?>
                        <a href="/admin/history/donation"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg hover:bg-gray-100 text-slate-700 hover:text-purple-700 transition-colors group border-l-8 border-transparent hover:border-purple-600">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-purple-600"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M4 21h9.62a4 4 0 0 0 3.037-1.397l5.102-5.952a1 1 0 0 0-.442-1.6l-1.968-.656a3.04 3.04 0 0 0-2.823.503l-3.185 2.547l-.617-1.235A3.98 3.98 0 0 0 9.146 11H4c-1.103 0-2 .897-2 2v6c0 1.103.897 2 2 2m0-8h5.146c.763 0 1.448.423 1.789 1.105l.447.895H7v2h6.014a1 1 0 0 0 .442-.11l.003-.001l.004-.002h.003l.002-.001h.004l.001-.001c.009.003.003-.001.003-.001c.01 0 .002-.001.002-.001h.001l.002-.001l.003-.001l.002-.001l.002-.001l.003-.001l.002-.001c.003 0 .001-.001.002-.001l.003-.002l.002-.001l.002-.001l.003-.001l.002-.001h.001l.002-.001h.001l.002-.001l.002-.001c.009-.001.003-.001.003-.001l.002-.001a1 1 0 0 0 .11-.078l4.146-3.317c.262-.208.623-.273.94-.167l.557.186l-4.133 4.823a2.03 2.03 0 0 1-1.52.688H4zM16 2h-.017c-.163.002-1.006.039-1.983.705c-.951-.648-1.774-.7-1.968-.704L12.002 2h-.004c-.801 0-1.555.313-2.119.878C9.313 3.445 9 4.198 9 5s.313 1.555.861 2.104l3.414 3.586a1.006 1.006 0 0 0 1.45-.001l3.396-3.568C18.688 6.555 19 5.802 19 5s-.313-1.555-.878-2.121A2.98 2.98 0 0 0 16.002 2zm1 3c0 .267-.104.518-.311.725L14 8.55l-2.707-2.843C11.104 5.518 11 5.267 11 5s.104-.518.294-.708A.98.98 0 0 1 11.979 4c.025.001.502.032 1.067.485q.121.098.247.222l.707.707l.707-.707q.126-.124.247-.222c.529-.425.976-.478 1.052-.484a1 1 0 0 1 .701.292c.189.189.293.44.293.707"
                                    stroke-width="0.5" stroke="currentColor" />
                            </svg>
                            <span class="text-sm font-medium">ประวัติการรับสิ่งของ</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($page === "DonationHistory" && (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/admin/history/redeem') === 0)): ?>
                        <a href="/admin/history/redeem"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg bg-gray-50 text-pink-700 transition-colors group border-l-8 border-pink-600">
                            <svg class="w-5 h-5 text-pink-600 group-hover:text-pink-700" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M12.984 15a1 1 0 0 0 1.848.53l2.688-2.687a1 1 0 0 0-1.415-1.414l-1.12 1.12V5a1 1 0 0 0-2 0zm-1.969-6a1 1 0 0 0-1.848-.53L6.48 11.157a1 1 0 1 0 1.414 1.414l1.121-1.12V19a1 1 0 1 0 2 0z"
                                    stroke-width="0.5" stroke="currentColor" />
                            </svg>
                            <span class="text-sm font-medium">ประวัติการแลกของรางวัล</span>
                        </a>
                    <?php else: ?>
                        <a href="/admin/history/redeem"
                            class="nav-item flex items-center gap-3 px-4 py-3 rounded-r-lg hover:bg-gray-100 text-slate-700 hover:text-pink-700 transition-colors group border-l-8 border-transparent hover:border-pink-600">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-pink-600" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24">
                                <path fill="currentColor"
                                    d="M12.984 15a1 1 0 0 0 1.848.53l2.688-2.687a1 1 0 0 0-1.415-1.414l-1.12 1.12V5a1 1 0 0 0-2 0zm-1.969-6a1 1 0 0 0-1.848-.53L6.48 11.157a1 1 0 1 0 1.414 1.414l1.121-1.12V19a1 1 0 1 0 2 0z"
                                    stroke-width="0.5" stroke="currentColor" />
                            </svg>
                            <span class="text-sm font-medium">ประวัติการแลกของรางวัล</span>
                        </a>
                    <?php endif; ?>
                </div>
            </nav>
        </aside>

        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" x-transition:opacity
            class="fixed inset-0 z-10 bg-black opacity-50  lg:hidden"></div>

        <main :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-0'"
            class="flex-1 px-8 py-4 mt-16 min-h-[calc(100vh-4rem)] max-h-[calc(100vh-4rem)]">
            <?php include $viewPath; ?>
        </main>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="<?= $script ?? "" ?>"></script>
    <script type="module" src="<?= $module ?? "" ?>"></script>
    <script>
        lucide.createIcons();
    </script>
</body>

</html>