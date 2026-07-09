<!DOCTYPE html>
<html lang="th" class="snap-y snap-proximity scroll-smooth">

<head>
    <title><?= $title ?? '' ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- <link href="/assets/output.css" rel="stylesheet"> -->
    <script defer src="/js/alpine-collapse.min.js"></script>
    <script defer src="/js/alpine.min.js"></script>
    <script src="/js/chart.js"></script>

    <script type="text/javascript" src="js/lucide.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&family=Noto+Serif+Thai:wght@100..900&family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');

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
    </style>
    <script type="text/javascript" src="<?= $script ?? '' ?>"></script>
</head>

<body class="noto-sans-thai bg-gray-50 text-gray-800 selection:bg-green-200 selection:text-green-900">

    <main class="">
        <?php include $viewPath; ?>
    </main>

    <script src="/js/swal.min.js"></script>
    <script> lucide.createIcons();</script>
</body>

</html>