<?php

# Клас для управління викликами файлів та директорій

class Direct
{

    /**
     * Фільтрація даних з GET-запиту.
     *
     * @param string $get_name Назва GET-параметра.
     * @return string Повертає очищене значення GET-параметра або 'no_data', якщо дані відсутні.
     */

    public static function get($name)
    {
        // Фільтруємо вхідні дані з GET-параметра
        $filter = filter_input(INPUT_GET, $name, FILTER_SANITIZE_ENCODED);
        // Видаляємо зайві спецсимволи, повернуті функцією FILTER_SANITIZE_ENCODED
        $get = clearspecialchars($filter);
        // Перевіряємо, чи довжина отриманого значення більша за нуль.
        $data = (strlen($get) > 0) ? $get : 'no_data';
        // Повертаємо значення змінної $data
        return $data;
    }

    /**
     * Перевірка на існування файлу або директорії.
     *
     * @param string $path Шлях до файлу або директорії.
     * @param string $type Тип перевірки ('file' для файлу, 'dir' для директорії).
     * @return bool Повертає true, якщо файл або директорія існує, інакше false.
     */

    public static function existsPath($path, $type = 'file')
    {
        if ($type === 'file') {
            // Повертає true, якщо файл існує, інакше false
            return is_file(ROOT . '/' . $path);
        } elseif ($type === 'dir') {
            // Повертає true, якщо директорія існує, інакше false
            return is_dir(ROOT . '/' . $path);
        } else {
            // Якщо тип невідомий, повертаємо false
            return false;
        }
    }

    /**
     * Функція для виведення компонентів з вказаної папки.
     *
     * @param string $path Шлях до папки, де знаходяться компоненти.
     * @param int $count Кількість компонентів, які потрібно показати. За замовчуванням 1.
     * @param int $limit Максимальна кількість компонентів для виведення. За замовчуванням 999999.
     * @param string $ext Розширення файлів, які потрібно знайти. За замовчуванням 'php'.
     */

    public static function components($path, $count = 1, $limit = 999999, $ext = 'php')
    {
        global $account, $settings, $comm, $par, $list;
        // Перевірка існування директорії
        if (!is_dir($path)) {
            echo "Директорія не знайдена";
            // Вихід з функції, якщо директорія не існує
            return;
        }
        // Отримуємо список файлів і директорій у вказаній директорії, відсортований за зростанням
        $result = scandir($path, SCANDIR_SORT_ASCENDING);
        // Перевірка на помилку при відкритті директорії
        if ($result === false) {
            echo "Помилка при відкритті директорії";
            // Вихід з функції, якщо не вдалося відкрити директорію
            return;
        }
        // Инициализация переменной для подсчета количества файлов.
        $fileCount = 0;
        // Проходимо по кожному елементу в директорії
        foreach ($result as $file) {
            // Перевіряємо, чи є елемент файлом з вказаним розширенням
            if (preg_match('#\.' . $ext . '$#i', $file)) {
                $fileCount++;
                // Вихід, якщо досягнуто ліміт файлів
                if ($fileCount >= $limit) break;
                // Підключаємо файл, використовуючи безпечний шлях
                require($path . DIRECTORY_SEPARATOR . $file);
            }
        }
        // Якщо не знайдено файлів і потрібно мінімум один, виводимо повідомлення
        if ($fileCount === 0 && $count === 1) {
            echo "Поки пусто";
        }
    }
}
