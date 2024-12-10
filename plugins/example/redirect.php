<?php

use LiveCMS\Helpers\Redirect;

// Якщо була натиснута кнопка, виконуємо перенаправлення.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['redirect_button'])) {
    Redirect::to('https://github.com/lifesheets/sandbox');
}

?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редирект після натискання кнопки</title>
</head>
<body>
    <!-- Форма з кнопкою -->
    <form method="POST">
        <button type="submit" name="redirect_button">Перейти на GitHub</button>
    </form>
</body>
</html>
