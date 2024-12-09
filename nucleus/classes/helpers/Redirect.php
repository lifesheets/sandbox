<?php

namespace Nucleus\Helpers;

/**
 * Клас для керування редиректами.
 */
class Redirect
{
    /**
     * Виконує перенаправлення на вказаний URL.
     *
     * @param string $url URL для перенаправлення.
     * @param int $refresh Час оновлення сторінки в секундах (за замовчуванням 0 — миттєве перенаправлення).
     *
     * @throws \InvalidArgumentException Якщо URL є порожнім або недійсним.
     */

    public static function to(string $url, int $refresh = 0): void
    {
        try {
            # Перевіряємо, чи URL є коректним.
            if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
                throw new \InvalidArgumentException("Неприпустимий або порожній URL: $url");
            }

            # Якщо задано час оновлення, встановлюємо Refresh-заголовок.
            if ($refresh > 0) {
                header("Refresh: $refresh; url=$url");
            } else {
                # Виконуємо миттєве перенаправлення.
                header("Location: $url");
            }

            # Завершуємо виконання скрипта.
            exit;
        } catch (\InvalidArgumentException $e) {
            # Логування помилки.
            error_log("Помилка редиректу: " . $e->getMessage());

            # Виводимо повідомлення про помилку та автоматично перенаправляємо через 5 секунд.
            echo "<p>Виникла помилка: " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p>Ви будете перенаправлені на головну сторінку через 5 секунд...</p>";
            echo "<script>setTimeout(function() { window.location.href = '/'; }, 5000);</script>";
            
            // Завершуємо виконання скрипта.
            die();
        } catch (\Exception $e) {
            # Логування загальної помилки.
            error_log("Непередбачувана помилка редиректу: " . $e->getMessage());

            # Повідомляємо користувача про загальну помилку.
            echo "<p>Виникла помилка при виконанні перенаправлення. Будь ласка, зверніться до адміністратора.</p>";
            echo "<p>Ви будете перенаправлені на головну сторінку через 5 секунд...</p>";
            echo "<script>setTimeout(function() { window.location.href = '/'; }, 5000);</script>";

            // Завершуємо виконання скрипта.
            die();
        }
    }
}
