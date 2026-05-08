<?php
/**
 * Скрипт установки базы данных
 * Запустите этот файл один раз в браузере (например: http://localhost/install.php)
 * для создания всех таблиц и начальных данных.
 * 
 * ПРЕДУПРЕЖДЕНИЕ: Удалите этот файл после успешного выполнения!
 */

require 'config.php';

// Массив SQL запросов для создания всех таблиц
$tables = [
    // Таблица users
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        role ENUM('user', 'moderator', 'admin') DEFAULT 'user',
        is_active TINYINT(1) DEFAULT 1,
        avatar VARCHAR(255) DEFAULT NULL,
        bio TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        last_login_at TIMESTAMP NULL DEFAULT NULL,
        INDEX idx_username (username),
        INDEX idx_email (email),
        INDEX idx_role (role)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Таблица categories
    "CREATE TABLE IF NOT EXISTS categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL UNIQUE,
        slug VARCHAR(100) NOT NULL UNIQUE,
        description TEXT,
        parent_id INT DEFAULT NULL,
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL ON UPDATE CASCADE,
        INDEX idx_slug (slug),
        INDEX idx_parent (parent_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Таблица posts
    "CREATE TABLE IF NOT EXISTS posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        category_id INT DEFAULT NULL,
        title VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL UNIQUE,
        content TEXT NOT NULL,
        excerpt TEXT,
        image VARCHAR(255) DEFAULT NULL,
        status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
        views_count INT DEFAULT 0,
        likes_count INT DEFAULT 0,
        comments_count INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        published_at TIMESTAMP NULL DEFAULT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL ON UPDATE CASCADE,
        INDEX idx_slug (slug),
        INDEX idx_user (user_id),
        INDEX idx_category (category_id),
        INDEX idx_status (status),
        INDEX idx_published_at (published_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Таблица tags
    "CREATE TABLE IF NOT EXISTS tags (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL UNIQUE,
        slug VARCHAR(50) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_slug (slug)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Таблица post_tags
    "CREATE TABLE IF NOT EXISTS post_tags (
        post_id INT NOT NULL,
        tag_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (post_id, tag_id),
        FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE ON UPDATE CASCADE,
        INDEX idx_post (post_id),
        INDEX idx_tag (tag_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Таблица comments
    "CREATE TABLE IF NOT EXISTS comments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        post_id INT NOT NULL,
        user_id INT DEFAULT NULL,
        parent_id INT DEFAULT NULL,
        author_name VARCHAR(100) DEFAULT NULL,
        author_email VARCHAR(100) DEFAULT NULL,
        content TEXT NOT NULL,
        status ENUM('pending', 'approved', 'spam', 'deleted') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE,
        FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE ON UPDATE CASCADE,
        INDEX idx_post (post_id),
        INDEX idx_user (user_id),
        INDEX idx_status (status),
        INDEX idx_parent (parent_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Таблица sessions
    "CREATE TABLE IF NOT EXISTS sessions (
        id VARCHAR(128) PRIMARY KEY,
        user_id INT DEFAULT NULL,
        ip_address VARCHAR(45) NOT NULL,
        user_agent TEXT,
        payload TEXT NOT NULL,
        last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
        INDEX idx_user (user_id),
        INDEX idx_last_activity (last_activity)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    // Таблица settings
    "CREATE TABLE IF NOT EXISTS settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(100) NOT NULL UNIQUE,
        setting_value TEXT,
        setting_type ENUM('string', 'number', 'boolean', 'json', 'text') DEFAULT 'string',
        description VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_key (setting_key)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
];

// Начальные данные
$initialData = [
    // Администратор по умолчанию (пароль: admin123)
    "INSERT IGNORE INTO users (username, email, password_hash, role, is_active) VALUES
    ('admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1)",

    // Категории
    "INSERT IGNORE INTO categories (name, slug, description, sort_order) VALUES
    ('Новости', 'news', 'Последние новости и события', 1),
    ('Статьи', 'articles', 'Полезные статьи и руководства', 2),
    ('Обзоры', 'reviews', 'Обзоры продуктов и сервисов', 3)",

    // Теги
    "INSERT IGNORE INTO tags (name, slug) VALUES
    ('php', 'php'),
    ('mysql', 'mysql'),
    ('javascript', 'javascript'),
    ('tutorial', 'tutorial'),
    ('news', 'news')",

    // Настройки сайта
    "INSERT IGNORE INTO settings (setting_key, setting_value, setting_type, description) VALUES
    ('site_name', 'Мой Сайт', 'string', 'Название сайта'),
    ('site_description', 'Описание моего сайта', 'text', 'Краткое описание сайта'),
    ('posts_per_page', '10', 'number', 'Количество записей на странице'),
    ('allow_comments', '1', 'boolean', 'Разрешить комментарии'),
    ('maintenance_mode', '0', 'boolean', 'Режим обслуживания')"
];

$createdTables = [];
$insertedData = [];
$errors = [];

echo "<h1>Установка базы данных</h1>";
echo "<p>База данных: <strong>" . DB_NAME . "</strong></p>";
echo "<hr>";

// Создание таблиц
echo "<h2>1. Создание таблиц</h2>";
foreach ($tables as $index => $sql) {
    try {
        $pdo->exec($sql);
        // Извлекаем имя таблицы из SQL запроса
        preg_match('/CREATE TABLE IF NOT EXISTS (\w+)/i', $sql, $matches);
        $tableName = $matches[1] ?? 'unknown_' . $index;
        $createdTables[] = $tableName;
        echo "<p style='color:green;'>✓ Таблица <strong>{$tableName}</strong> создана успешно</p>";
    } catch (PDOException $e) {
        $errors[] = "Ошибка создания таблицы: " . $e->getMessage();
        echo "<p style='color:red;'>✗ Ошибка создания таблицы: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

// Вставка начальных данных
echo "<h2>2. Добавление начальных данных</h2>";
foreach ($initialData as $sql) {
    try {
        $pdo->exec($sql);
        // Определяем тип данных
        if (strpos($sql, 'INSERT IGNORE INTO users') !== false) {
            $dataType = 'Пользователь admin';
        } elseif (strpos($sql, 'INSERT IGNORE INTO categories') !== false) {
            $dataType = 'Категории';
        } elseif (strpos($sql, 'INSERT IGNORE INTO tags') !== false) {
            $dataType = 'Теги';
        } elseif (strpos($sql, 'INSERT IGNORE INTO settings') !== false) {
            $dataType = 'Настройки';
        } else {
            $dataType = 'Данные';
        }
        $insertedData[] = $dataType;
        echo "<p style='color:green;'>✓ {$dataType} добавлены успешно</p>";
    } catch (PDOException $e) {
        $errors[] = "Ошибка вставки данных: " . $e->getMessage();
        echo "<p style='color:red;'>✗ Ошибка вставки данных: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

// Итоговый отчет
echo "<hr>";
echo "<h2>Итоговый отчет</h2>";

if (empty($errors)) {
    echo "<h3 style='color:green;'>✓ Установка завершена успешно!</h3>";
    echo "<p>Создано таблиц: <strong>" . count($createdTables) . "</strong></p>";
    echo "<ul>";
    foreach ($createdTables as $table) {
        echo "<li>{$table}</li>";
    }
    echo "</ul>";
    
    echo "<p><strong>Данные для входа администратора:</strong></p>";
    echo "<ul>";
    echo "<li>Логин: <code>admin</code></li>";
    echo "<li>Email: <code>admin@example.com</code></li>";
    echo "<li>Пароль: <code>admin123</code></li>";
    echo "</ul>";
    
    echo "<p style='color:red; font-weight:bold;'>⚠️ ВАЖНО: Удалите файл install.php из соображений безопасности!</p>";
} else {
    echo "<h3 style='color:red;'>✗ Установка завершена с ошибками</h3>";
    echo "<p>Количество ошибок: <strong>" . count($errors) . "</strong></p>";
}

// Проверка структуры созданных таблиц
echo "<hr>";
echo "<h2>Структура созданных таблиц</h2>";

foreach ($createdTables as $table) {
    echo "<h3>Таблица: {$table}</h3>";
    try {
        $stmt = $pdo->query("DESCRIBE {$table}");
        echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Поле</th><th>Тип</th><th>Null</th><th>Ключ</th><th>По умолчанию</th><th>Extra</th></tr>";
        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td><code>" . htmlspecialchars($row['Field']) . "</code></td>";
            echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
            echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Не удалось получить структуру: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Установка БД</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 1200px; margin: 0 auto; }
        h1 { color: #333; }
        h2 { color: #555; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        h3 { color: #666; }
        table { border-collapse: collapse; margin-top: 10px; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; }
        tr:nth-child(even) { background-color: #fafafa; }
        code { background: #f0f0f0; padding: 2px 6px; border-radius: 3px; font-family: monospace; }
        ul { line-height: 1.8; }
    </style>
</head>
<body>
</body>
</html>
