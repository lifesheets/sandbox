<?php

/**
 * Класс для работы с базой даних.
 * Забезпечує підключення до бази даних, виконання SQL-запитів та обробку помилок.
 * Файл: DatabaseHandler.php
 * Автор: Микола Довгопол (ua.livefox)
 */

class DatabaseHandler
{
    private static ?PDO $connection = null;

    /**
     * Встановлює підключення до бази даних.
     *
     * @return PDO|null Підключення до бази даних або null у разі помилки.
     */

    public static function connect($host = 'mariadb-11.2', $base = 'sandbox', $user = 'root', $pass = ''): ?PDO
    {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO('mysql:host=' . $host . ';dbname=' . $base . ';charset=utf8mb4',  $user, $pass, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'"]);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                error_log('Помилка підключення до бази даних: ' . $e->getMessage());
            }
        }
        return self::$connection;
    }

    /**
     * Перевіряє, чи є підключення до бази даних.
     *
     * @return bool true, якщо підключення встановлене, інакше false.
     */

    public static function isConnected(): bool
    {
        return self::$connection !== null;
    }

    /**
     * Виконує підготовлений запит.
     *
     * @param string $query SQL-запит.
     * @param array $parameters Параметри запиту.
     * @return PDOStatement|null Підготовлений і виконаний запит або null.
     */

    private static function prepareAndExecute(string $query, array $parameters = []): ?PDOStatement
    {
        if (!self::connect()) {
            error_log('Неможливо підключитися до бази даних.');
            return null;
        }

        try {
            $stmt = self::$connection->prepare($query);
            $stmt->execute($parameters);
            return $stmt;
        } catch (PDOException $e) {
            error_log('Помилка виконання запиту: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Підготовка запиту, без виконання.
     *
     * @param string $query SQL-запит.
     * @return PDOStatement|null Підготовлений запит або null.
     */

    public static function prepareStatement(string $query): ?PDOStatement
    {
        if (!self::connect()) {
            error_log('Неможливо підключитися до бази даних.');
            return null;
        }

        try {
            return self::$connection->prepare($query);
        } catch (PDOException $e) {
            error_log('Помилка підготовки запиту: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Повертає інформацію про базу даних.
     *
     * @return array Інформація про базу даних.
     */

    public static function getDatabaseInfo(): array
    {
        if (!self::connect()) {
            error_log('Неможливо підключитися до бази даних.');
            return [];
        }

        try {
            $version = self::$connection->getAttribute(PDO::ATTR_SERVER_VERSION);
            $driverName = self::$connection->getAttribute(PDO::ATTR_DRIVER_NAME);
            $host = self::$connection->getAttribute(PDO::ATTR_CONNECTION_STATUS);

            return [
                'version' => $version,
                'driver' => $driverName,
                'host' => $host
            ];
        } catch (PDOException $e) {
            error_log('Помилка отримання інформації про базу даних: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Закриває підключення до бази даних.
     */

    public static function closeConnection(): void
    {
        self::$connection = null;
    }
}
