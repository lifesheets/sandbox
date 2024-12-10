<?php

# Підключення необхідних бібліотек.
use LiveCMS\Helpers\Direct;

?>

<!DOCTYPE html>
<html lang="uk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Модуль Example</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        header {
            background-color: #007BFF;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        main {
            padding: 20px;
            max-width: 800px;
            margin: auto;
        }

        section {
            margin-bottom: 30px;
        }

        h2 {
            color: #007BFF;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #ddd;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <header>
        <h1>Ласкаво просимо до модуля "Example"</h1>
    </header>

    <main>
        <?= Direct::components(ROOT . '/plugins/example/components/main') ?>
    </main>

    <footer>
        <p>LiveCMS © 2024. Усі права захищені.</p>
    </footer>
</body>

</html>