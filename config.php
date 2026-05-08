<?php
/**
 * Конфигурация базы данных
 * Замените значения на ваши данные от хостинга или локального сервера
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name'); // Имя вашей базы данных
define('DB_USER', 'root');               // Имя пользователя БД (обычно root для локального)
define('DB_PASS', '');                   // Пароль БД (обычно пустой для локального XAMPP/OpenServer)
define('DB_CHARSET', 'utf8mb4');

// Параметры DSN для PDO
$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

// Опции PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Выбрасывать исключения при ошибках
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Возвращать массивы по умолчанию
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Использовать нативные подготовленные выражения
];

try {
    // Создание подключения
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (\PDOException $e) {
    // Если база данных не существует или доступ запрещен
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
