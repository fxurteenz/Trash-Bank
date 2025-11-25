<!DOCTYPE html>
<html lang="th">
<head>
    <title><?= $title ?? '' ?></title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap');
        .noto-sans-thai {
            font-family: "Noto Sans Thai", sans-serif;
        }
        .open-sans{
            font-family: "Open Sans", sans-serif;
        }
    </style>
    <script type="text/javascript" src="<?= $script ?>"></script>
</head>
<body>
    <div class="bg-gray-100 min-h-screen min-w-screen noto-sans-thai">
        <?php include $viewPath; ?>
    </div>

</body>
</html>