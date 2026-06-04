<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="/js/alpine-collapse.min.js"></script>
    <script defer src="/js/alpine.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600&display=swap');

        body {
            font-family: 'Sarabun', sans-serif;
            padding: 20px;
            color: #333;
        }

        .header-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .header-container h2 {
            margin: 0 0 5px 0;
            font-size: 24px;
        }

        .header-container p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
            font-size: 13px;
        }

        th {
            background-color: #f4f4f5;
            font-weight: 600;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        @media print {
            @page {
                size: A4;
                margin: 1.5cm;
            }

            body {
                -webkit-print-color-adjust: exact;
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <?php include $viewPath; ?>
</body>

</html>