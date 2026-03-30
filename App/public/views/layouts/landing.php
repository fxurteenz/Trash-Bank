<!DOCTYPE html>
<html lang="th" class="snap-y snap-proximity scroll-smooth">

<head>
    <title><?= $title ?? '' ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- <link href="/assets/output.css" rel="stylesheet"> -->
    <script defer src="/js/alpine.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');

        .noto-sans-thai {
            font-family: "Noto Sans Thai", sans-serif;
        }

        .open-sans {
            font-family: "Open Sans", sans-serif;
        }

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

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @keyframes slideInFromTop {
            0% {
                transform: translateY(-100%);
                opacity: 0;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* นำไปใช้กับ Class ที่ต้องการ */
        .slide-down-item {
            animation: slideInFromTop 1.2s ease-out forwards;
        }
    </style>
    <script type="text/javascript" src="<?= $script ?? '' ?>"></script>
</head>

<body class="min-h-screen noto-sans-thai m-0 p-0" x-data="{ open: false, scrollY: 0 }"
    @scroll.window="scrollY = window.scrollY">
    <header class="fixed top-0 w-full z-50 transition-all duration-500"
        :class="scrollY >= (document.documentElement.scrollHeight * 0.28) ? 'opacity-100 pointer-events-auto' : 'opacity-0 pointer-events-none'">
        <nav class="container mx-auto px-6 py-3 flex justify-between items-center">
            <a href="/" class="text-xl font-bold text-gray-800 drop-shadow-sm">
                <img src="assets/images/bru_gogreen_logo.png" alt="BRU Go Green Logo" class="h-12 w-auto"></a>
        </nav>
    </header>

    <main class="">
        <?php include $viewPath; ?>
    </main>

    <script src="/js/swal.min.js"></script>
</body>

</html>