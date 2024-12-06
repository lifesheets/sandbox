<?php

#  Перевірка параметрів у запиті.
function hasInvalidParams(array $params): bool
{
    return array_reduce($params, fn($carry, $param) => $carry || str_contains(REQUEST_URI, "$param="), false);
}

# Очищення вхідних даних.
function sanitize(?string $data): ?string
{
    return $data ? clearspecialchars(trim($data), ENT_QUOTES, 'UTF-8') : null;
}

# Підключення файлу.
function includeFile(string $filePath): bool
{
    if (direct::existsPath($filePath)) {
        require_once ROOT . $filePath;
        exit;
    }
    return false;
}

# Обробка запиту.
function processRequest(?string $base, ?string $path, ?string $subpath, ?string $section): void
{
    $defaultModule = '/modules/main/index.php';
    if (direct::existsPath("/$base/", 'dir')) {
        if (direct::existsPath("/$base/$path/$subpath/", 'dir')) {
            includeFile("/$base/$path/$subpath/$section.php")
                ?: includeFile("/$base/$path/$subpath/index.php")
                ?: includeFile($defaultModule);
        } elseif (direct::existsPath("/$base/$path/", 'dir')) {
            includeFile("/$base/$path/$section.php")
                ?: includeFile("/$base/$path/index.php")
                ?: includeFile($defaultModule);
        } else {
            includeFile("/$base/$section.php")
                ?: includeFile("/$base/index.php")
                ?: includeFile($defaultModule);
        }
    } else {
        includeFile($defaultModule);
    }
}
