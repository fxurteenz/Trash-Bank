<!DOCTYPE html>
<html lang="th" class="h-full w-full scroll-smooth">

<head>
    <title><?= $title ?? 'Trash Bank' ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="/assets/output.css" rel="stylesheet">
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
        label,
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
    </style>
</head>

<body class="min-h-screen bg-fixed">

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
                <a href="/admin" class="flex items-center gap-2 group">
                    <img src="/assets/images/waste_bankFullLogo.png" class="h-10" alt="BRU Waste Bank">
                </a>
            </div>

            <div class="hidden md:block text-center">
                <h2 class="text-lg font-semibold text-slate-800"><?= $title ?? '' ?></h2>
            </div>

            <div class="relative" @click.away="profileMenuOpen = false">
                <button @click="profileMenuOpen = !profileMenuOpen"
                    class="flex items-center gap-2 px-3 py-2 hover:bg-slate-100 rounded-lg transition-colors cursor-pointer">
                    <i data-lucide="circle-user-round" class="w-6 h-6"></i>
                    <div class="hidden sm:block text-left">
                        <p class="text-md font-medium text-slate-900">
                            <?= $user->member_name ?? 'เพิ่มชื่อผู้ใช่งาน' ?>
                        </p>
                        <p class="text-xs text-slate-500">
                            <?php echo $user->role_name_th ?? "เจ้าหน้าที่ศูนย์"; ?>
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
                    <a href="#"
                        class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 flex justify-between px-3"><i
                            data-lucide="settings"></i> ตั้งค่า</a>
                    <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 flex justify-between"><i
                            data-lucide="user"></i> โปรไฟล์</a>
                    <hr class="my-1">
                    <a href="/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex justify-between">
                        <i data-lucide="log-out"></i>ออกจากระบบ
                    </a>
                </div>
            </div>
        </header>

        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed top-16 bottom-0 left-0 z-20 w-64 bg-white border-r border-slate-200 transform transition-transform duration-150 ease-in-out overflow-y-auto">
            <div class="border-b-2 border-slate-400 w-full p-2 text-center">
                <p class="text-sm text-slate-500">
                    เจ้าหน้าที่ศูนย์ธนาคารขยะ
                </p>
                <h3 class="text-slate-600 text-lg flex items-center gap-2 justify-center">
                    <i data-lucide="square-user-round"></i>
                    <?php echo $user->member_name ?? "ตั้งชื่อผู้ใช้งาน"; ?>
                </h3>
            </div>
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

                </div>
                <div class="mt-6">
                    <p class="px-4 py-2 text-xs font-semibold text-slate-500 uppercase tracking-wider">คลังขยะ</p>
                    <a href="/waste_center/stock/centerwaste"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 15 15">
                            <path fill="currentColor"
                                d="M7.5 0c4 0 7.5 3.5 7.5 7.5S11.5 15 7.5 15S0 11.5 0 7.5S3.5 0 7.5 0m0 1C4 1 1 4 1 7.5S4 14 7.5 14S14 11 14 7.5S11 1 7.5 1M2.84 6.98c.24.03 1.43.15 1.72.18c.29.04.17.89-.09.86c-.27-.02-1.44-.14-1.72-.18c-.29-.03-.16-.89.09-.86m7.48 0c.35-.04 1.32-.13 1.7-.16c.39-.04.4.87.06.9c-.35.04-1.28.12-1.64.16c-.37.03-.47-.85-.12-.9m-3.02.11c.09-.27.86-.03.76.28c-.09.32-.42 1.36-.52 1.63c-.1.28-.95.08-.84-.23c.12-.3.52-1.45.6-1.68m2.17 2.42c.29.11 1.37.52 1.61.62c.24.11-.05.93-.31.81c-.32-.11-1.32-.49-1.61-.62c-.3-.13.01-.91.31-.81m-4.77.08c.08.19.54 1.44.61 1.69c.08.25-.68.56-.8.29c-.11-.28-.44-1.34-.54-1.62c-.1-.27.66-.54.73-.36m2.11 1.37c.28.11 1.36.45 1.65.54c.28.09.04.92-.27.82s-1.36-.43-1.65-.53c-.28-.11-.01-.93.27-.83M13 5c-1 1-1.75 1-2.75 0c-1 1-1.75 1-2.75 0c-1 1-1.7 1-2.7 0C3.8 6 3 6 2 5c-.5 1-.5 2-.5 2.5c0 3 2.5 6 6 6s6-3 6-6c0-.5 0-1.5-.5-2.5"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                        <span class="text-sm font-medium">คลังศูนย์รวบรวมขยะ</span>
                    </a>
                    <a href="/waste_center/stock/branchwaste"
                        class="nav-item flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 transition-colors group">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-600"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 15 15">
                            <path fill="currentColor"
                                d="M7.5 0c4 0 7.5 3.5 7.5 7.5S11.5 15 7.5 15S0 11.5 0 7.5S3.5 0 7.5 0m0 1C4 1 1 4 1 7.5S4 14 7.5 14S14 11 14 7.5S11 1 7.5 1M2.84 6.98c.24.03 1.43.15 1.72.18c.29.04.17.89-.09.86c-.27-.02-1.44-.14-1.72-.18c-.29-.03-.16-.89.09-.86m7.48 0c.35-.04 1.32-.13 1.7-.16c.39-.04.4.87.06.9c-.35.04-1.28.12-1.64.16c-.37.03-.47-.85-.12-.9m-3.02.11c.09-.27.86-.03.76.28c-.09.32-.42 1.36-.52 1.63c-.1.28-.95.08-.84-.23c.12-.3.52-1.45.6-1.68m2.17 2.42c.29.11 1.37.52 1.61.62c.24.11-.05.93-.31.81c-.32-.11-1.32-.49-1.61-.62c-.3-.13.01-.91.31-.81m-4.77.08c.08.19.54 1.44.61 1.69c.08.25-.68.56-.8.29c-.11-.28-.44-1.34-.54-1.62c-.1-.27.66-.54.73-.36m2.11 1.37c.28.11 1.36.45 1.65.54c.28.09.04.92-.27.82s-1.36-.43-1.65-.53c-.28-.11-.01-.93.27-.83M13 5c-1 1-1.75 1-2.75 0c-1 1-1.75 1-2.75 0c-1 1-1.7 1-2.7 0C3.8 6 3 6 2 5c-.5 1-.5 2-.5 2.5c0 3 2.5 6 6 6s6-3 6-6c0-.5 0-1.5-.5-2.5"
                                stroke-width="0.5" stroke="currentColor" />
                        </svg>
                        <span class="text-sm font-medium">คลังหน่วย/ศูนย์ของคุณ</span>
                    </a>
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
    <script>
        lucide.createIcons();
    </script>
</body>

</html>