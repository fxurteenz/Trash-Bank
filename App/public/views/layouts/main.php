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

        /* Shared user layout styling (only applied when footer==='user') */
        .user-mode {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
        }

        .user-page {
            width: 100%;
            max-width: 430px;
            /* comfortable phone width */
            padding: 16px;
            min-height: 100vh;
        }

        @media (min-width: 768px) {
            .user-page {
                padding: 18px;
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