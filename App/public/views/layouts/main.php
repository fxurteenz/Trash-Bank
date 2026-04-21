<!DOCTYPE html>
<html lang="th">

<head>
    <title><?= $title ?? '' ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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

        /* Shared user layout styling (only applied when footer==='user') */
        .user-mode {
            background:
                radial-gradient(circle at 12% 10%, rgba(255, 255, 255, 0.1), transparent 30%),
                radial-gradient(circle at 88% 18%, rgba(255, 255, 255, 0.08), transparent 32%),
                linear-gradient(135deg, #0e8b53 0%, #0a6f43 55%, #065835 100%);
            display: flex;
            justify-content: center;
        }

        .user-page {
            width: 100%;
            max-width: 460px;
            padding: 14px;
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