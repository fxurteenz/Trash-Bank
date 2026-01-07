<!DOCTYPE html>
<html lang="th" class="h-full w-full">

<head>
    <title><?= $title ?? '' ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');

        .h-screen-minus-nav {
            min-height: calc(100vh - 4rem);
        }

        .noto-sans-thai {
            font-family: "Noto Sans Thai", sans-serif;
        }

        .open-sans {
            font-family: "Open Sans", sans-serif;
        }
    </style>
</head>

<body>
    <div class="bg-gray-100 noto-sans-thai"
        x-data="{ sidebarOpen: window.innerWidth >= 1024, profileDropdownOpen: false }">

        <header
            class="flex items-center justify-between h-16 bg-white shadow-md fixed top-0 left-0 right-0 z-30 px-4 w-full">
            <div class="flex items-center space-x-4">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="text-gray-500 focus:outline-none lg:hidden p-2 hover:bg-gray-100 rounded">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <a href="#" class="flex items-center space-x-2">
                    <span
                        class="text-xl font-bold bg-gradient-to-r from-lime-400 to-lime-600 text-transparent bg-clip-text">Waste-Bank</span>
                </a>

                <span
                    class="hidden md:block text-lg text-gray-700 ml-6 border-l pl-4 font-semibold italic"><?= $title ?></span>
            </div>

            <div class="relative" @click.away="profileDropdownOpen = false">
                <button @click="profileDropdownOpen = !profileDropdownOpen"
                    class="flex items-center focus:outline-none">
                    <img class="h-10 w-10 rounded-full object-cover border-2 border-lime-500"
                        src="/assets/images/4042171.png" alt="Profile">
                </button>

                <div x-show="profileDropdownOpen" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-40">
                    <a href="#"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-lime-50 hover:text-lime-600">ดูโปรไฟล์</a>
                    <div class="border-t border-gray-100 my-1"></div>
                    <a href="/logout"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600">ออกจากระบบ</a>
                </div>
            </div>
        </header>

        <div class="flex pt-16 w-full">
            <aside :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
                class="fixed inset-y-0 left-0 z-20 w-50 bg-stone-800 text-white transform transition-transform duration-300 lg:translate-x-0 lg:static lg:min-h-screen lg:block lg:shadow-none shadow-xl">
                <div class="p-4 mt-16 lg:mt-0">
                    <nav class="space-y-1">
                        <a href="/waste_center"
                            class="flex text-nowrap items-center p-2 rounded-md transition duration-200 <?= $pages === "home" ? "bg-gradient-to-r from-lime-400 to-lime-600" : "hover:bg-gray-700" ?>">
                            <span class="<?= $pages === "home" ? "font-semibold" : "font-light" ?>">หน้าหลัก</span>
                        </a>
                        <!-- ส่วนเมนูการดำเนินการ -->
                        <div class="flex text-nowrap items-center p-1 my-1 border-y-2 border-white text-center">
                            <span class="font-bold italic w-full text-center">การดำเนินการ</span>
                        </div>
                        <a href="/waste_center/transactions/waste"
                            class="flex text-nowrap items-center p-2 rounded-md transition duration-200 <?= $pages === "wasteTransaction" ? "bg-gradient-to-r from-lime-400 to-lime-600" : "hover:bg-gray-700" ?> ">
                            <span
                                class="<?= $pages === "wasteTransaction" ? "font-semibold" : "font-light" ?>">ฝากขยะ</span>
                        </a>
                        <a href="/waste_center/transactions/clear_waste"
                            class="flex text-nowrap items-center p-2 rounded-md transition duration-200 <?= $pages === "clearWasteTransaction" ? "bg-gradient-to-r from-lime-400 to-lime-600" : "hover:bg-gray-700" ?> ">
                            <span
                                class="<?= $pages === "clearWasteTransaction" ? "font-semibold" : "font-light" ?>">เคลียร์ยอดฝากขยะ</span>
                        </a>
                        <!-- ส่วนเมนูการจัดการข้อมูล -->
                        <div class="flex text-nowrap items-center p-1 my-1 border-y-2 border-white text-center">
                            <span class="font-bold italic w-full text-center">การจัดการข้อมูล</span>
                        </div>
                        <a href="/waste_center/manage/waste_type"
                            class="flex text-nowrap items-center p-2 rounded-md transition duration-200 <?= $pages === "manageWasteType" ? "bg-gradient-to-r from-lime-400 to-lime-600" : "hover:bg-gray-700" ?>  ">
                            <span
                                class="<?= $pages === "manageWasteType" ? "font-semibold" : "font-light" ?>">จัดการหมวดหมู่ขยะ</span>
                        </a>
                        <a href="/waste_center/manage/waste_transaction"
                            class="flex text-nowrap items-center p-2 rounded-md transition duration-200 <?= $pages === "manageWasteTransaction" ? "bg-gradient-to-r from-lime-400 to-lime-600" : "hover:bg-gray-700" ?>  ">
                            <span
                                class="<?= $pages === "manageWasteTransaction" ? "font-semibold" : "font-light" ?>">รายการฝาก</span>
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