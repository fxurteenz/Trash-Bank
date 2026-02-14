<!DOCTYPE html>
<html lang="th">

<head>
    <title><?= $title ?? '' ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> -->
    <link href="/assets/output.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');

        .noto-sans-thai {
            font-family: "Noto Sans Thai", sans-serif;
        }

        .open-sans {
            font-family: "Open Sans", sans-serif;
        }

        /* * {
            outline: 1px solid red !important;
        } */


        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out forwards;
        }

        @keyframes slide-to-right {
            from {
                background-position: 50% 0;
                opacity: 0;
            }

            to {
                background-position: 100% 0;
                opacity: 1;
            }
        }

        @keyframes slide-to-left {
            from {
                background-position: 100% 0;
                opacity: 0;
            }

            to {
                background-position: 50% 0;
                opacity: 1;
            }
        }

        .animate-bg {
            width: 100%;
            height: 300px;
            background-image: url(/assets/images/waste_bank1.png);
            background-position: 0px 0px;
            background-repeat: repeat-x;
            background-position: right;
            background-size: contain;
            background-repeat: no-repeat;
            background-attachment: scroll;
            animation: slide-to-right 0.5s ease-in-out backwards;
        }

        .introduce-section {
            background-image: url(/assets/images/waste_bank1.png);
            background-position: right;
            background-size: contain;
            background-repeat: no-repeat;
            background-attachment: scroll;
        }

        .second-section {
            background-image: url(/assets/images/waste_bank3.png);
            background-position: left;
            background-size: contain;
            background-repeat: no-repeat;
            background-attachment: scroll;
            animation: slide-to-left 0.5s ease-in-out backwards;
        }
    </style>
    <script type="text/javascript" src="<?= $script ?>"></script>
</head>

<body class="min-h-screen noto-sans-thai" x-data="{ open: false }">
    <header class="bg-white shadow-md sticky top-0 z-50 h-16">
        <nav class="container mx-auto px-6 py-3 flex justify-between items-center">
            <a href="/" class="text-xl font-bold text-gray-800">
                ระบบธนาคารขยะ
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="/" class="text-gray-600 hover:text-blue-500 px-3 py-2">หน้าแรก</a>
                <a href="/about" class="text-gray-600 hover:text-blue-500 px-3 py-2">เกี่ยวกับ</a>
                <a href="/contact" class="text-gray-600 hover:text-blue-500 px-3 py-2">ติดต่อ</a>
                <a href="/register" class="text-gray-600 hover:text-blue-500 px-3 py-2">สมัครสมาชิก</a>
                <a href="/login"
                    class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-500 transition-colors">เข้าสู่ระบบ</a>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button @click="open = !open" class="text-gray-800 focus:outline-none">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path x-show="!open" d="M4 6H20M4 12H20M4 18H14" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path x-show="open" d="M6 18L18 6M6 6L12 12" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </nav>

        <!-- Mobile Menu -->
        <div x-show="open" @click.away="open = false" class="md:hidden" x-transition>
            <a href="/" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-200">หน้าแรก</a>
            <a href="/about" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-200">เกี่ยวกับ</a>
            <a href="/contact" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-200">ติดต่อ</a>
            <a href="/register" class="block py-2 px-4 text-sm text-gray-700 hover:bg-gray-200">สมัครสมาชิก</a>
            <a href="/login" class="block py-2 px-4 text-sm text-white bg-blue-600">เข้าสู่ระบบ</a>
        </div>
    </header>

    <main class="">
        <?php include $viewPath; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>