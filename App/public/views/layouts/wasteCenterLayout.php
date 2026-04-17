<?php
$UserName = $user["user_data"]->member_name;
?>
<!DOCTYPE html>
<html lang="th" class="h-full w-full scroll-smooth">

<head>
    <title><?= $title ?? 'Trash Bank' ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="/assets/output.css" rel="stylesheet">
    <script src="/js/alpine.min.js" defer></script>

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

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gradient-to-b from-green-100 to-slate-50 min-h-screen bg-fixed">

    <div x-data="{ sidebarOpen: window.innerWidth > 1024, profileMenuOpen: false }"
        @resize.window="sidebarOpen = window.innerWidth > 1024" class="min-h-screen flex flex-col">

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
                <a href="/waste_center" class="flex items-center gap-2 group">
                    <div
                        class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white font-bold text-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="m10.95 2.054l-.005-.007l-.013.01C5.912 2.59 2 6.837 2 12c0 2.312.785 4.441 2.103 6.135v.002h.002a10 10 0 0 0 6.92 3.816c.047-.476.163-.974.337-1.477a8.48 8.48 0 0 1-5.631-2.736v-.001c.055-.24.08-.518.1-.76l.009-.118c.018-.22.035-.432.063-.646c.07-.527.19-.879.377-1.078c.24-.257.486-.3 1.001-.391l.093-.017c.264-.046.617-.117.957-.302c.363-.198.67-.5.901-.945c.233-.448.216-.91.152-1.275a7 7 0 0 0-.23-.863l-.046-.148c-.205-.668-.23-.993-.061-1.25c.184-.28.436-.344 1.092-.514l.085-.021c.666-.172 1.568-.46 2.09-1.573c.566-1.205.247-2.564-.1-3.486a9 9 0 0 0-.373-.85Q11.92 3.5 12 3.5a8.46 8.46 0 0 1 4.535 1.31c-.192.255-.449.591-.736.95c-.63.787-1.34 1.597-1.824 1.96a1.4 1.4 0 0 0-.486.648a1.46 1.46 0 0 0-.055.734c.07.416.291.806.487 1.104c.163.249.355.5.516.712l.11.143q.14.185.227.317q.043.065.064.104l.02.039q.059.165.133.54l.022.106c.444-.198.925-.35 1.44-.445a5 5 0 0 0-.188-.72c-.11-.297-.349-.616-.521-.844l-.13-.17a11 11 0 0 1-.439-.605c-.155-.236-.23-.399-.255-.498c.65-.502 1.448-1.436 2.05-2.187c.296-.37.56-.715.757-.979a8.48 8.48 0 0 1 2.76 5.818c.58.046 1.08.122 1.51.211C21.863 6.342 17.439 2 12 2q-.532 0-1.05.054M4.436 15.881a8.503 8.503 0 0 1 5.796-12.197q.05.084.116.203c.139.25.313.597.462.993c.315.835.442 1.691.147 2.32c-.225.478-.545.613-1.108.758l-.162.04c-.532.129-1.371.331-1.893 1.123c-.601.912-.305 1.911-.12 2.515l.06.192c.08.255.14.45.172.636c.038.217.01.297-.004.325c-.1.193-.2.272-.288.32c-.11.06-.254.1-.502.143q-.06.012-.13.022c-.463.076-1.199.197-1.798.84c-.486.52-.663 1.21-.748 1.767m7.571 6.324c.06-.965.495-2.142 1.233-3.251c.239-.406.534-.817.886-1.218a8.9 8.9 0 0 1 4.496-2.75a.5.5 0 0 0-.243-.971a9.9 9.9 0 0 0-5.005 3.061c-.267.305-.51.622-.726.943A3.6 3.6 0 0 1 12.5 17c0-.917.467-1.913 1.207-2.703a5.33 5.33 0 0 1 3.195-1.634c2.8-.37 4.538-.06 5.563.227a.75.75 0 0 1 .211 1.348c-.057.038-.149.13-.26.34a5 5 0 0 0-.32.825c-.087.284-.17.591-.26.925l-.058.212c-.111.41-.234.85-.382 1.286c-.292.862-.704 1.782-1.397 2.488c-.719.733-1.695 1.19-2.999 1.19c-1.329 0-2.257-.539-2.85-1.159c-.404.74-.614 1.437-.646 1.951a.75.75 0 0 1-1.497-.091"
                                stroke-width="0.2" stroke="currentColor" />
                        </svg>
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="text-xl font-bold text-emerald-700">BRU Waste Bank</h1>
                        <p class="text-xs text-slate-500">Waste Center Dashboard</p>
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
                        <p class="text-sm font-medium text-slate-900"><?= $UserName ?? 'ไม่มีชื่อ' ?></p>
                        <p class="text-xs text-slate-500"></p>
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

                <a href="/waste_center"
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

                    <a href="/waste_center/transactions/waste"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
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

                    <a href="/waste_center/transactions/clear_waste"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M5 19V5zv-.112zm0 2q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h14q.825 0 1.413.588T21 5v7q0 .425-.288.713T20 13t-.712-.288T19 12V5H5v14h6q.425 0 .713.288T12 20t-.288.713T11 21zm12.35-1.825l3.525-3.55q.3-.3.713-.3t.712.3t.3.713t-.3.712l-4.25 4.25q-.3.3-.712.3t-.713-.3L14.5 19.175q-.275-.3-.275-.712t.3-.713t.7-.3t.7.3zM8 13q.425 0 .713-.288T9 12t-.288-.712T8 11t-.712.288T7 12t.288.713T8 13m0-4q.425 0 .713-.288T9 8t-.288-.712T8 7t-.712.288T7 8t.288.713T8 9m8 4q.425 0 .713-.288T17 12t-.288-.712T16 11h-4q-.425 0-.712.288T11 12t.288.713T12 13zm0-4q.425 0 .713-.288T17 8t-.288-.712T16 7h-4q-.425 0-.712.288T11 8t.288.713T12 9z"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                        <span class="text-sm font-medium">เคลียร์ขยะ</span>
                    </a>

                    <a href="/waste_center/transactions/waste_sale"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                        </svg>
                        <span class="text-sm font-medium">จำหน่ายขยะ</span>
                    </a>

                    <a href="/waste_center/transactions/donation"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M4 21h9.62a4 4 0 0 0 3.037-1.397l5.102-5.952a1 1 0 0 0-.442-1.6l-1.968-.656a3.04 3.04 0 0 0-2.823.503l-3.185 2.547l-.617-1.235A3.98 3.98 0 0 0 9.146 11H4c-1.103 0-2 .897-2 2v6c0 1.103.897 2 2 2m0-8h5.146c.763 0 1.448.423 1.789 1.105l.447.895H7v2h6.014a1 1 0 0 0 .442-.11l.003-.001l.004-.002h.003l.002-.001h.004l.001-.001c.009.003.003-.001.003-.001c.01 0 .002-.001.002-.001h.001l.002-.001l.003-.001l.002-.001l.002-.001l.003-.001l.002-.001c.003 0 .001-.001.002-.001l.003-.002l.002-.001l.002-.001l.003-.001l.002-.001h.001l.002-.001h.001l.002-.001l.002-.001c.009-.001.003-.001.003-.001l.002-.001a1 1 0 0 0 .11-.078l4.146-3.317c.262-.208.623-.273.94-.167l.557.186l-4.133 4.823a2.03 2.03 0 0 1-1.52.688H4zM16 2h-.017c-.163.002-1.006.039-1.983.705c-.951-.648-1.774-.7-1.968-.704L12.002 2h-.004c-.801 0-1.555.313-2.119.878C9.313 3.445 9 4.198 9 5s.313 1.555.861 2.104l3.414 3.586a1.006 1.006 0 0 0 1.45-.001l3.396-3.568C18.688 6.555 19 5.802 19 5s-.313-1.555-.878-2.121A2.98 2.98 0 0 0 16.002 2zm1 3c0 .267-.104.518-.311.725L14 8.55l-2.707-2.843C11.104 5.518 11 5.267 11 5s.104-.518.294-.708A.98.98 0 0 1 11.979 4c.025.001.502.032 1.067.485q.121.098.247.222l.707.707l.707-.707q.126-.124.247-.222c.529-.425.976-.478 1.052-.484a1 1 0 0 1 .701.292c.189.189.293.44.293.707"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                        <span class="text-sm font-medium">รับสิ่งของ</span>
                    </a>

                    <a href="/waste_center/transactions/redeem_item"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M11.3 8.3L9.2 6.2q-.3-.3-.3-.7t.3-.7l2.1-2.1q.3-.3.7-.3t.7.3l2.1 2.1q.3.3.3.7t-.3.7l-2.1 2.1q-.3.3-.7.3t-.7-.3M2 20q-.425 0-.712-.288T1 19v-3q0-.85.588-1.425T3 14h3.275q.5 0 .95.25t.725.675q.725.975 1.788 1.525T12 17q1.225 0 2.288-.55t1.762-1.525q.325-.425.763-.675t.912-.25H21q.85 0 1.425.575T23 16v3q0 .425-.288.713T22 20h-5q-.425 0-.712-.288T16 19v-1.275q-.875.625-1.888.95T12 19q-1.075 0-2.1-.337T8 17.7V19q0 .425-.288.713T7 20zm2-7q-1.25 0-2.125-.875T1 10q0-1.275.875-2.137T4 7q1.275 0 2.138.863T7 10q0 1.25-.862 2.125T4 13m16 0q-1.25 0-2.125-.875T17 10q0-1.275.875-2.137T20 7q1.275 0 2.138.863T23 10q0 1.25-.862 2.125T20 13"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                        <span class="text-sm font-medium">แลกของ</span>
                    </a>

                    <!-- <a href="/waste_center/transactions/redeem_reward"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M12.984 15a1 1 0 0 0 1.848.53l2.688-2.687a1 1 0 0 0-1.415-1.414l-1.12 1.12V5a1 1 0 0 0-2 0zm-1.969-6a1 1 0 0 0-1.848-.53L6.48 11.157a1 1 0 1 0 1.414 1.414l1.121-1.12V19a1 1 0 1 0 2 0z"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                        <span class="text-sm font-medium">แลกของรางวัล</span>
                    </a> -->

                </div>

                <div class="mt-6">
                    <p class="px-4 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        ประวัติการทำรายการ</p>

                    <a href="/waste_center/history/waste_deposit"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path class="w-5 h-5 text-slate-400 group-hover:text-emerald-600" fill="none"
                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M11 22c-.818 0-1.6-.33-3.163-.99C3.946 19.366 2 18.543 2 17.16V7m9 15V11.355M11 22c.34 0 .646-.057 1-.172M20 7v4.5M18 18l.906-.905M22 18a4 4 0 1 0-8 0a4 4 0 0 0 8 0M7.326 9.691L4.405 8.278C2.802 7.502 2 7.114 2 6.5s.802-1.002 2.405-1.778l2.92-1.413C9.13 2.436 10.03 2 11 2s1.871.436 3.674 1.309l2.921 1.413C19.198 5.498 20 5.886 20 6.5s-.802 1.002-2.405 1.778l-2.92 1.413C12.87 10.564 11.97 11 11 11s-1.871-.436-3.674-1.309M5 12l2 1m9-9L6 9" />
                        </svg>
                        <span class="text-sm font-medium">ประวัติการฝากขยะ</span>
                    </a>

                    <a href="/waste_center/history/clear_waste"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <span class="text-sm font-medium">ประวัติเคลียร์ยอดฝาก</span>
                    </a>

                    <a href="/waste_center/history/waste_sale"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" />
                        </svg>
                        <span class="text-sm font-medium">ประวัติการจำหน่ายขยะ</span>
                    </a>

                    <a href="/waste_center/history/donation"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M4 21h9.62a4 4 0 0 0 3.037-1.397l5.102-5.952a1 1 0 0 0-.442-1.6l-1.968-.656a3.04 3.04 0 0 0-2.823.503l-3.185 2.547l-.617-1.235A3.98 3.98 0 0 0 9.146 11H4c-1.103 0-2 .897-2 2v6c0 1.103.897 2 2 2m0-8h5.146c.763 0 1.448.423 1.789 1.105l.447.895H7v2h6.014a1 1 0 0 0 .442-.11l.003-.001l.004-.002h.003l.002-.001h.004l.001-.001c.009.003.003-.001.003-.001c.01 0 .002-.001.002-.001h.001l.002-.001l.003-.001l.002-.001l.002-.001l.003-.001l.002-.001c.003 0 .001-.001.002-.001l.003-.002l.002-.001l.002-.001l.003-.001l.002-.001h.001l.002-.001h.001l.002-.001l.002-.001c.009-.001.003-.001.003-.001l.002-.001a1 1 0 0 0 .11-.078l4.146-3.317c.262-.208.623-.273.94-.167l.557.186l-4.133 4.823a2.03 2.03 0 0 1-1.52.688H4zM16 2h-.017c-.163.002-1.006.039-1.983.705c-.951-.648-1.774-.7-1.968-.704L12.002 2h-.004c-.801 0-1.555.313-2.119.878C9.313 3.445 9 4.198 9 5s.313 1.555.861 2.104l3.414 3.586a1.006 1.006 0 0 0 1.45-.001l3.396-3.568C18.688 6.555 19 5.802 19 5s-.313-1.555-.878-2.121A2.98 2.98 0 0 0 16.002 2zm1 3c0 .267-.104.518-.311.725L14 8.55l-2.707-2.843C11.104 5.518 11 5.267 11 5s.104-.518.294-.708A.98.98 0 0 1 11.979 4c.025.001.502.032 1.067.485q.121.098.247.222l.707.707l.707-.707q.126-.124.247-.222c.529-.425.976-.478 1.052-.484a1 1 0 0 1 .701.292c.189.189.293.44.293.707"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                        <span class="text-sm font-medium">ประวัติการรับสิ่งของ</span>
                    </a>

                    <!-- <a href="/waste_center/history/redeem_reward"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M12.984 15a1 1 0 0 0 1.848.53l2.688-2.687a1 1 0 0 0-1.415-1.414l-1.12 1.12V5a1 1 0 0 0-2 0zm-1.969-6a1 1 0 0 0-1.848-.53L6.48 11.157a1 1 0 1 0 1.414 1.414l1.121-1.12V19a1 1 0 1 0 2 0z"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                        <span class="text-sm font-medium">ประวัติการแลกของรางวัล</span>
                    </a> -->
                </div>

                <div class="mt-6">
                    <p class="px-4 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">จัดการ</p>

                    <a href="/waste_center/manage/members"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 16 16">
                            <path fill="currentColor"
                                d="M7.5 9a2 2 0 0 1 2 2c0 .965-.592 1.73-1.411 2.23C7.27 13.728 6.175 14 5 14s-2.27-.272-3.089-.77C1.091 12.73.5 11.965.5 11a2 2 0 0 1 2-2zm-5 1a1 1 0 0 0-1 1c0 .508.304.992.932 1.375S3.966 13 5 13s1.94-.242 2.568-.625S8.5 11.508 8.5 11a1 1 0 0 0-1-1zm11.652-.992A1.5 1.5 0 0 1 15.5 10.5c0 .771-.47 1.409-1.101 1.83c-.636.424-1.486.67-2.399.67c-.699 0-1.36-.146-1.917-.403c.16-.287.28-.601.35-.943c.423.21.964.346 1.567.346c.743 0 1.394-.202 1.844-.502c.453-.302.656-.665.656-.998a.5.5 0 0 0-.4-.49L14 10h-3.674a3 3 0 0 0-.575-.979A1.5 1.5 0 0 1 9.999 9h4zm-1.92-5.5a2.253 2.253 0 0 1 2.022 2.241l-.012.23A2.253 2.253 0 0 1 12.002 8l-.23-.012a2.25 2.25 0 0 1-2.01-2.01l-.012-.23a2.25 2.25 0 0 1 2.252-2.252zM5 2.5A2.75 2.75 0 1 1 5 8a2.75 2.75 0 0 1 0-5.5m7.002 1.997a1.252 1.252 0 1 0 0 2.504a1.252 1.252 0 0 0 0-2.504M5 3.5A1.75 1.75 0 1 0 5 7a1.75 1.75 0 0 0 0-3.5"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                        <span class="text-sm font-medium">ผู้ใช้งาน</span>
                    </a>

                    <!-- <a href="/admin/manage/faculty"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600"
                            xmlns="http://www.w3.org/2000/svg" width="30" height="24" viewBox="0 0 640 512">
                            <path fill="currentColor"
                                d="m335.5 4l288 160c15.4 8.6 21 28.1 12.4 43.5s-28.1 21-43.5 12.4L320 68.6L47.5 220c-15.4 8.6-34.9 3-43.5-12.4s-3-34.9 12.4-43.5L304.5 4c9.7-5.4 21.4-5.4 31.1 0zM320 160a40 40 0 1 1 0 80a40 40 0 1 1 0-80m-176 96a40 40 0 1 1 0 80a40 40 0 1 1 0-80m312 40a40 40 0 1 1 80 0a40 40 0 1 1-80 0M226.9 491.4L200 441.5V480c0 17.7-14.3 32-32 32h-48c-17.7 0-32-14.3-32-32v-38.5l-26.9 49.9c-6.3 11.7-20.8 16-32.5 9.8s-16-20.8-9.8-32.5l37.9-70.3c15.3-28.5 45.1-46.3 77.5-46.3h19.5c16.3 0 31.9 4.5 45.4 12.6l33.6-62.3c15.3-28.5 45.1-46.3 77.5-46.3h19.5c32.4 0 62.1 17.8 77.5 46.3l33.6 62.3c13.5-8.1 29.1-12.6 45.4-12.6h19.5c32.4 0 62.1 17.8 77.5 46.3l37.9 70.3c6.3 11.7 1.9 26.2-9.8 32.5s-26.2 1.9-32.5-9.8L552 441.5V480c0 17.7-14.3 32-32 32h-48c-17.7 0-32-14.3-32-32v-38.5l-26.9 49.9c-6.3 11.7-20.8 16-32.5 9.8s-16-20.8-9.8-32.5l36.3-67.5c-1.7-1.7-3.2-3.6-4.3-5.8L376 345.5V400c0 17.7-14.3 32-32 32h-48c-17.7 0-32-14.3-32-32v-54.5l-26.9 49.9c-1.2 2.2-2.6 4.1-4.3 5.8l36.3 67.5c6.3 11.7 1.9 26.2-9.8 32.5s-26.2 1.9-32.5-9.8z"
                                stroke-width="13" stroke="currentColor" />
                        </svg>
                        <span class="text-sm font-medium">คณะ/สาขา</span>
                    </a>

                    <a href="/admin/manage/waste_type"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M21.03 3L18 20.31c-.17.96-1 1.69-2 1.69H8c-1 0-1.83-.73-2-1.69L2.97 3zM5.36 5L8 20h8l2.64-15zM9 18v-4h4v4zm4-4.82L9.82 10L13 6.82L16.18 10z"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                        <span class="text-sm font-medium">หมวดหมู่ขยะ</span>
                    </a>

                    <a href="/admin/manage/rewards"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor" fill-rule="evenodd"
                                d="M12 2.75a6.25 6.25 0 1 0 0 12.5a6.25 6.25 0 0 0 0-12.5M4.25 9a7.75 7.75 0 1 1 15.025 2.677l2.288 2.368c.257.267.471.489.631.674c.158.182.32.39.415.632c.334.845.066 1.845-.739 2.337c-.23.142-.494.2-.72.238c-.232.04-.526.07-.873.107l-.024.002c-.459.049-.546.064-.605.087a.68.68 0 0 0-.397.417c-.026.07-.041.173-.088.644l-.002.022c-.036.361-.066.664-.103.902c-.036.23-.09.494-.223.725c-.47.822-1.459 1.13-2.317.767c-.241-.102-.446-.273-.62-.435c-.18-.166-.393-.388-.65-.655l-.02-.02L12 17.09l-3.232 3.405l-.015.015c-.257.267-.471.489-.65.655c-.175.162-.38.333-.62.435c-.86.363-1.848.055-2.318-.767c-.132-.231-.187-.494-.223-.726c-.037-.237-.067-.54-.102-.9l-.003-.023c-.046-.471-.062-.573-.087-.644a.68.68 0 0 0-.397-.417c-.06-.023-.147-.038-.606-.087l-.023-.002c-.347-.037-.641-.068-.873-.107c-.226-.037-.49-.096-.721-.238c-.804-.492-1.072-1.492-.739-2.337c.096-.242.257-.45.415-.632c.16-.185.374-.407.632-.674l2.287-2.368A7.7 7.7 0 0 1 4.25 9m1.178 4.109l-1.896 1.963c-.276.286-.462.478-.592.629a1.2 1.2 0 0 0-.154.2c-.09.23 0 .424.119.503c.009.003.06.02.194.043c.18.03.43.057.806.097l.075.008c.34.035.641.067.91.17c.599.23 1.057.71 1.272 1.312c.096.269.126.573.16.927l.008.075c.038.389.064.649.094.84c.026.162.046.214.047.217c.08.135.244.201.425.125c0 0 .05-.027.186-.154c.145-.134.33-.325.605-.61l.002-.002l2.72-2.866a7.76 7.76 0 0 1-4.981-3.477m8.163 3.478a7.76 7.76 0 0 0 4.982-3.478l1.896 1.963c.276.286.461.478.591.629c.123.14.151.195.154.2c.09.23 0 .424-.118.503c-.01.003-.06.02-.194.043c-.181.03-.43.057-.807.097l-.075.008c-.339.035-.641.067-.91.17c-.598.23-1.057.71-1.272 1.312c-.096.269-.126.573-.16.927l-.008.075c-.038.389-.064.649-.094.84c-.025.162-.046.214-.046.217c-.08.135-.245.202-.427.125h.002s-.05-.027-.187-.154a17 17 0 0 1-.605-.61l-.002-.002zm-1.59-9.553q-.087.15-.2.354l-.098.176l-.022.04c-.079.144-.209.382-.426.547c-.221.168-.488.226-.643.26l-.043.009l-.191.043c-.176.04-.318.072-.44.103c.079.097.182.219.316.376l.13.152l.03.034c.108.125.283.325.363.585c.08.256.052.52.035.686l-.005.047l-.02.203a23 23 0 0 0-.041.46c.104-.046.222-.1.363-.165l.179-.082l.04-.02c.144-.067.394-.184.672-.184c.279 0 .528.117.672.185l.04.019l.18.082c.14.065.258.12.363.165l-.042-.46l-.02-.203l-.005-.047c-.017-.167-.044-.43.035-.686c.08-.26.255-.46.363-.585l.03-.034l.13-.152c.134-.157.237-.279.317-.376c-.122-.03-.265-.063-.44-.103l-.191-.043l-.043-.01c-.156-.033-.422-.091-.644-.26c-.217-.164-.347-.402-.425-.545l-.023-.041l-.098-.176q-.112-.204-.199-.354M11.013 5.8c.172-.225.485-.55.986-.55c.502 0 .815.325.987.55c.164.214.33.511.5.816l.022.041l.099.177l.056.1l.099.023l.19.043l.048.01c.328.075.653.148.903.247c.277.109.65.32.795.785c.142.455-.037.841-.193 1.09c-.145.23-.364.486-.59.749l-.03.035l-.13.153l-.082.097l.012.135l.02.203l.004.046c.035.352.068.692.055.964c-.012.286-.08.718-.468 1.011c-.4.304-.84.238-1.12.157c-.258-.073-.562-.214-.87-.355l-.043-.02l-.179-.083l-.085-.039l-.085.04l-.178.082l-.044.02c-.307.141-.612.282-.87.355c-.28.08-.72.147-1.12-.157c-.387-.293-.455-.725-.468-1.01c-.012-.273.02-.613.055-.965l.005-.046l.02-.203l.012-.135l-.083-.097l-.13-.153l-.03-.035c-.225-.263-.445-.52-.59-.75c-.156-.248-.334-.634-.193-1.09c.145-.463.519-.675.795-.784c.25-.099.576-.172.904-.246l.046-.01l.191-.044l.1-.023l.056-.1l.098-.177l.023-.041c.17-.305.335-.602.5-.816"
                                clip-rule="evenodd" stroke-width="0.5" stroke="currentColor" />
                        </svg>
                        <span class="text-sm font-medium">ของรางวัล</span>
                    </a> -->

                </div>
            </nav>
        </aside>

        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" x-transition:opacity
            class="fixed inset-0 z-10 bg-black opacity-50  lg:hidden"></div>

        <main :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-0'"
            class="flex-1 px-8 py-4 mt-16 min-h-[calc(100vh-4rem)] transition-all duration-150 ease-in-out">
            <?php include $viewPath; ?>
        </main>

    </div>
    <script src="/js/swal.min.js"></script>
    <script type="text/javascript" src="<?= $script ?? "" ?>"></script>
    <script type="module" src="<?= $module ?? "" ?>"></script>
</body>

</html>