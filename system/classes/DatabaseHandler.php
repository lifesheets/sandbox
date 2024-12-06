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
     * Виконує INSERT-запит і повертає ID вставленого запису.
     *
     * @param string $query SQL-запит.
     * @param array $parameters Параметри запиту.
     * @return int ID вставленого запису або 0 у разі помилки.
     */

    public static function insertRecord(string $query, array $parameters = []): int
    {
        $stmt = self::prepareAndExecute($query, $parameters);
        return $stmt ? (int)self::$connection->lastInsertId() : 0;
    }

    /**
     * Виконує UPDATE-запит і повертає кількість оновлених рядків.
     *
     * @param string $query SQL-запит.
     * @param array $parameters Параметри запиту.
     * @return int Кількість оновлених рядків.
     */

    public static function updateRecord(string $query, array $parameters = []): int
    {
        $stmt = self::prepareAndExecute($query, $parameters);
        return $stmt ? $stmt->rowCount() : 0;
    }

    /**
     * Виконує DELETE-запит і повертає кількість видалених рядків.
     *
     * @param string $query SQL-запит.
     * @param array $parameters Параметри запиту.
     * @return int Кількість видалених рядків.
     */

    public static function deleteRecord(string $query, array $parameters = []): int
    {
        $stmt = self::prepareAndExecute($query, $parameters);
        return $stmt ? $stmt->rowCount() : 0;
    }

    /**
     * Виконує SQL-запит для перейменування таблиці.
     *
     * @param string $query SQL-запит.
     * @return bool true при успіху, false у разі помилки.
     */

    public static function renameTable(string $query): bool
    {
        return self::prepareAndExecute($query) !== null;
    }

    /**
     * Виконує SELECT-запит і повертає одну строку.
     *
     * @param string $query SQL-запит.
     * @param array $parameters Параметри запиту.
     * @return array|null Результат або null.
     */

    public static function fetchSingleRow(string $query, array $parameters = []): ?array
    {
        $stmt = self::prepareAndExecute($query, $parameters);
        return $stmt?->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Виконує SELECT-запит і повертає всі строки.
     *
     * @param string $query SQL-запит.
     * @param array $parameters Параметри запиту.
     * @return array|null Масив результатів або null.
     */

    public static function fetchAllRows(string $query, array $parameters = []): ?array
    {
        $stmt = self::prepareAndExecute($query, $parameters);
        return $stmt?->fetchAll(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Виконує SQL-запити з файлу.
     *
     * @param string $filePath Шлях до SQL-файлу.
     * @return bool true при успіху, false у разі помилки.
     */

    public static function executeSqlQueriesFromFile(string $filePath): bool
    {
        if (!file_exists($filePath)) {
            error_log('Файл SQL не знайдено: ' . $filePath);
            return false;
        }

        $sql = file_get_contents($filePath);
        $queries = array_filter(array_map('trim', explode(';', $sql)));

        foreach ($queries as $query) {
            if (self::prepareAndExecute($query) === null) {
                return false;
            }
        }
        return true;
    }

    /**
     * Повертає кількість строк у таблиці.
     *
     * @param string $tableName Назва таблиці.
     * @return int Кількість строк.
     */

    public static function getRowCount(string $tableName): int
    {
        $result = self::fetchSingleRow("SELECT COUNT(*) AS count FROM `$tableName`");
        return (int)($result['count'] ?? 0);
    }

    /**
     * Закриває підключення до бази даних.
     */

    public static function closeConnection(): void
    {
        self::$connection = null;
    }
}
