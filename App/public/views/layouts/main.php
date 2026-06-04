<!DOCTYPE html>
<html lang="th">

<head>
    <title><?= $title ?? '' ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="/js/alpine-collapse.min.js"></script>
    <script defer src="/js/alpine.min.js"></script>
    <script type="text/javascript" src="/js/lucide.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');

        .noto-sans-thai {
            font-family: "Noto Sans Thai", sans-serif;
        }

        .open-sans {
            font-family: "Open Sans", sans-serif;
        }

        /* KBank-style user layout */
        .user-mode {
            background: #F4F5F7;
            display: flex;
            justify-content: center;
        }

        .user-page {
            width: 100%;
            max-width: 460px;
            padding: 0 0 16px;
            min-height: 100vh;
        }

        @media (min-width: 768px) {
            .user-page {
                padding: 0 0 16px;
            }
        }
    </style>
    <script type="text/javascript" src="<?= $script ?>"></script>
</head>

<body>
    <div
        class="min-h-screen min-w-screen noto-sans-thai <?= isset($footer) ? 'has-footer' : '' ?> <?= (isset($footer) && $footer === 'user') ? 'user-mode' : 'bg-gray-100' ?>">
        <div class="<?= (isset($footer) && $footer === 'user') ? 'user-page' : '' ?>">
            <?php include $viewPath; ?>
        </div>
    </div>
    <?php if (isset($footer) && $footer === 'user'): ?>
        <?php include 'views/layouts/partials/userFooter.php'; ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>