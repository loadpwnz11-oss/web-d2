# 🔧 ENV Reference — Переменные окружения

**Версия:** 1.0 (MVP)  
**Стек:** PHP 8.2+, MySQL 8.0+, Redis 7.0+

---

## 📁 Расположение файлов

| Среда | Файл | Примечание |
|-------|------|------------|
| **Development** | `.env.local` | Локальная разработка, не коммитить |
| **Testing** | `.env.test` | Автотесты, изолированная БД |
| **Staging** | `.env.staging` | Пре-продакшн среда |
| **Production** | `.env.production` | Продакшн (загружается в secrets manager) |

**Шаблон для копирования:** `.env.example` — всегда коммитить в репозиторий (без чувствительных данных!)

---

## 🔐 Обязательные переменные

### Приложение

| Переменная | Тип | Пример | Описание |
|------------|-----|--------|----------|
| `APP_NAME` | string | `AI Story Engine` | Название проекта |
| `APP_ENV` | enum | `local`, `staging`, `production` | Режим работы |
| `APP_DEBUG` | boolean | `true`, `false` | Режим отладки (не включать на prod!) |
| `APP_URL` | URL | `https://aistory.engine` | Базовый URL приложения |
| `APP_TIMEZONE` | string | `UTC`, `Europe/Moscow` | Часовой пояс |
| `APP_LOCALE` | string | `ru`, `en` | Язык по умолчанию |

### База данных (MySQL)

| Переменная | Тип | Пример | Описание |
|------------|-----|--------|----------|
| `DB_CONNECTION` | string | `mysql` | Драйвер БД |
| `DB_HOST` | hostname | `127.0.0.1`, `db.internal` | Хост БД |
| `DB_PORT` | integer | `3306` | Порт БД |
| `DB_DATABASE` | string | `ai_story_db` | Имя базы данных |
| `DB_USERNAME` | string | `ai_story_user` | Пользователь БД |
| `DB_PASSWORD` | string | `secure_password_here` | Пароль БД |
| `DB_CHARSET` | string | `utf8mb4` | Кодировка |
| `DB_COLLATION` | string | `utf8mb4_unicode_ci` | Сравнение строк |
| `DB_PREFIX` | string | `as_` | Префикс таблиц (опционально) |

###Redis (очереди и кэш)

| Переменная | Тип | Пример | Описание |
|------------|-----|--------|----------|
| `REDIS_HOST` | hostname | `127.0.0.1`, `redis.internal` | Хост Redis |
| `REDIS_PORT` | integer | `6379` | Порт Redis |
| `REDIS_PASSWORD` | string | `redis_secret` | Пароль (опционально) |
| `REDIS_DB` | integer | `0` | Номер базы данных (0-15) |
| `REDIS_PREFIX` | string | `ai_story:` | Префикс ключей |

### Аутентификация и безопасность

| Переменная | Тип | Пример | Описание |
|------------|-----|--------|----------|
| `APP_KEY` | base64 | `base64:random32bytes` | Ключ шифрования приложения |
| `JWT_SECRET` | string | `your_jwt_secret_key_min_32_chars` | Секрет для JWT токенов |
| `JWT_TTL` | integer | `60` | Время жизни access токена (минуты) |
| `JWT_REFRESH_TTL` | integer | `10080` | Время жизни refresh токена (минуты, 7 дней) |
| `BCRYPT_ROUNDS` | integer | `12` | Стоимость хеширования паролей |
| `SESSION_LIFETIME` | integer | `120` | Время жизни сессии (минуты) |

---

## 🎨 AI / Генерация контента

| Переменная | Тип | Пример | Описание |
|------------|-----|--------|----------|
| `AI_PROVIDER` | enum | `stability_ai`, `replicate`, `local` | Провайдер AI моделей |
| `STABILITY_API_KEY` | string | `sk_abc123...` | API ключ Stability AI |
| `REPLICATE_API_TOKEN` | string | `r8_abc123...` | API токен Replicate |
| `DEFAULT_MODEL` | string | `stable-video-diffusion-img2vid-xt` | Модель по умолчанию |
| `FACE_CONSISTENCY_THRESHOLD` | float | `0.75` | Порог сходства лица (0.0-1.0) |
| `MAX_RENDER_RETRIES` | integer | `3` | Максимум попыток рендера |
| `MOCK_RENDER` | boolean | `true`, `false` | Режим заглушек (для тестов без GPU) |

### Параметры генерации

| Переменная | Тип | Пример | Описание |
|------------|-----|--------|----------|
| `DEFAULT_RESOLUTION` | enum | `480p`, `720p`, `1080p` | Разрешение видео по умолчанию |
| `DEFAULT_DURATION` | float | `60.0` | Длительность сцены (секунды) |
| `DEFAULT_MOTION_BUCKET` | integer | `50` | Уровень движения (1-255) |
| `CFG_SCALE` | float | `7.0` | Guidance scale для генерации |
| `SEED_FIXED` | boolean | `false` | Фиксировать seed для воспроизводимости |
| `DEFAULT_SEED` | integer | `-1` | Seed (-1 = случайный) |

---

## 💾 Хранение данных (Storage)

| Переменная | Тип | Пример | Описание |
|------------|-----|--------|----------|
| `STORAGE_PROVIDER` | enum | `local`, `s3`, `minio`, `r2` | Провайдер хранения |
| `STORAGE_DEFAULT_ACL` | string | `private`, `public-read` | Права доступа по умолчанию |

### Local Storage

| Переменная | Тип | Пример | Описание |
|------------|-----|--------|----------|
| `STORAGE_LOCAL_ROOT` | path | `/var/www/storage` | Корневая папка локального хранилища |
| `STORAGE_LOCAL_URL` | URL | `https://cdn.aistory.engine` | Публичный URL для файлов |

### S3 / R2 / MinIO

| Переменная | Тип | Пример | Описание |
|------------|-----|--------|----------|
| `AWS_ACCESS_KEY_ID` | string | `AKIAIOSFODNN7EXAMPLE` | Access Key |
| `AWS_SECRET_ACCESS_KEY` | string | `wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY` | Secret Key |
| `AWS_DEFAULT_REGION` | string | `us-east-1`, `auto` | Регион (или `auto` для R2) |
| `AWS_BUCKET` | string | `ai-story-videos` | Имя бакета |
| `AWS_ENDPOINT` | URL | `https://accountid.r2.cloudflarestorage.com` | Endpoint (для S3-compatible) |
| `AWS_USE_PATH_STYLE_ENDPOINT` | boolean | `false` | Использовать path-style endpoint |

### Уровни хранения (Storage Tiers)

| Переменная | Тип | Пример | Описание |
|------------|-----|--------|----------|
| `STORAGE_HOT_TTL_DAYS` | integer | `7` | Срок хранения в hot tier (дней) |
| `STORAGE_WARM_TTL_DAYS` | integer | `90` | Срок хранения в warm tier (дней) |
| `STORAGE_COLD_ENABLED` | boolean | `true` | Включить холодное хранилище |
| `STORAGE_COLD_GLACIER_CLASS` | string | `GLACIER` | Класс хранения для cold tier |

---

## 📨 Очереди задач (Queues)

| Переменная | Тип | Пример | Описание |
|------------|-----|--------|----------|
| `QUEUE_DRIVER` | enum | `redis`, `database`, `sqs` | Драйвер очередей |
| `QUEUE_DEFAULT` | string | `default` | Очередь по умолчанию |
| `QUEUE_PRIORITY_FREE` | integer | `1` | Приоритет бесплатных задач |
| `QUEUE_PRIORITY_PREMIUM` | integer | `5` | Приоритет премиум задач |
| `QUEUE_PRIORITY_VIP` | integer | `10` | Приоритет VIP задач |
| `QUEUE_BATCH_SIZE` | integer | `10` | Размер пакета задач для воркера |
| `QUEUE_WORKER_TIMEOUT` | integer | `600` | Таймаут задачи (секунды) |

---

## 💰 Монетизация

| Переменная | Тип | Пример | Описание |
|------------|-----|--------|----------|
| `CURRENCY_NAME` | string | `coins` | Название внутренней валюты |
| `CURRENCY_ICON` | string | `🪙` | Иконка валюты |
| `PRICE_VARIANT3` | integer | `100` | Цена варианта 3 (монеты) |
| `SUBSCRIPTION_MONTHLY_PRICE` | float | `4.99` | Цена месячной подписки (USD/RUB) |
| `SUBSCRIPTION_QUARTERLY_PRICE` | float | `11.99` | Цена квартальной подписки |
| `SUBSCRIPTION_YEARLY_PRICE` | float | `39.99` | Цена годовой подписки |
| `AD_REWARD_AMOUNT` | integer | `50` | Награда за просмотр рекламы |
| `DAILY_QUEST_BASE_REWARD` | integer | `25` | Базовая награда за задание |
| `VIP_CURRENCY_MULTIPLIER` | float | `2.0` | Множитель заработка для подписчиков |

### Платёжные провайдеры

| Переменная | Тип | Пример | Описание |
|------------|-----|--------|----------|
| `PAYMENT_PROVIDER` | enum | `yookassa`, `stripe`, `manual` | Основной платёжный провайдер |
| `YOOKASSA_SHOP_ID` | string | `12345` | ID магазина в ЮKassa |
| `YOOKASSA_SECRET_KEY` | string | `test_abc123...` | Секретный ключ ЮKassa |
| `STRIPE_SECRET_KEY` | string | `sk_test_abc123...` | Secret key Stripe |
| `STRIPE_PUBLISHABLE_KEY` | string | `pk_test_abc123...` | Publishable key Stripe |
| `STRIPE_WEBHOOK_SECRET` | string | `whsec_abc123...` | Секрет вебхука Stripe |

---

## 📊 Аналитика и логирование

| Переменная | Тип | Пример | Описание |
|------------|-----|--------|----------|
| `ANALYTICS_ENABLED` | boolean | `true` | Включить сбор аналитики |
| `ANALYTICS_DRIVER` | enum | `database`, `clickhouse`, `external` | Драйвер аналитики |
| `LOG_CHANNEL` | enum | `stack`, `single`, `daily`, `syslog` | Канал логирования |
| `LOG_LEVEL` | enum | `debug`, `info`, `notice`, `warning`, `error` | Уровень логирования |
| `LOG_MAX_FILES` | integer | `30` | Максимум файлов логов (для daily) |

### External Analytics (опционально)

| Переменная | Тип | Пример | Описание |
|------------|-----|--------|----------|
| `GOOGLE_ANALYTICS_ID` | string | `G-XXXXXXXXXX` | ID Google Analytics |
| `YANDEX_METRICA_ID` | string | `12345678` | ID Яндекс.Метрики |
| `SENTRY_DSN` | URL | `https://abc@sentry.io/123` | DSN для Sentry (ошибки) |
| `SENTRY_ENVIRONMENT` | string | `production` | Среда для Sentry |
| `SENTRY_TRACES_SAMPLE_RATE` | float | `0.1` | Семплирование трейсов (0.0-1.0) |

---

## 🌐 CDN и контент

| Переменная | Тип | Пример | Описание |
|------------|-----|--------|----------|
| `CDN_URL` | URL | `https://cdn.aistory.engine` | Базовый URL CDN |
| `CDN_ENABLED` | boolean | `true` | Включить CDN |
| `CDN_CACHE_TTL` | integer | `86400` | TTL кэша CDN (секунды) |
| `VIDEO_STREAMING_TYPE` | enum | `progressive`, `hls` | Тип стриминга видео |
| `HLS_SEGMENT_DURATION` | integer | `4` | Длительность сегмента HLS (секунды) |

---

## 🔒 Безопасность и CORS

| Переменная | Тип | Пример | Описание |
|------------|-----|--------|----------|
| `ALLOWED_ORIGINS` | CSV | `https://aistory.engine,https://www.aistory.engine` | Разрешённые CORS origin |
| `ALLOWED_IPS` | CSV | `127.0.0.1,10.0.0.0/8` | Разрешённые IP (для админки) |
| `TRUSTED_PROXIES` | CSV | `10.0.0.1,10.0.0.2` | Доверенные прокси |
| `RATE_LIMIT_ENABLED` | boolean | `true` | Включить rate limiting |
| `RATE_LIMIT_MAX_REQUESTS` | integer | `100` | Максимум запросов в минуту |
| `CSRF_ENABLED` | boolean | `true` | Включить CSRF защиту |
| `CONTENT_ENCRYPTION_ENABLED` | boolean | `true` | Шифровать story.json |
| `ENCRYPTION_KEY` | base64 | `base64:32bytekey` | Ключ шифрования контента |

---

## 👥 Роли и разрешения

| Переменная | Тип | Пример | Описание |
|------------|-----|--------|----------|
| `DEFAULT_USER_ROLE` | enum | `player`, `author` | Роль по умолчанию при регистрации |
| `ADMIN_EMAILS` | CSV | `admin@aistory.engine,mod@aistory.engine` | Email администраторов |
| `MODERATION_ENABLED` | boolean | `true` | Включить премодерацию контента |
| `AUTO_PUBLISH_STORIES` | boolean | `false` | Автопубликация историй (без модерации) |

---

## 🧪 Feature Flags

| Переменная | Тип | Пример | Описание |
|------------|-----|--------|----------|
| `FEATURE_NEW_PLAYER_UI` | boolean | `false` | Новый интерфейс плеера |
| `FEATURE_AI_COPILOT` | boolean | `false` | AI помощник для авторов |
| `FEATURE_MARKETPLACE` | boolean | `false` | Маркетплейс ассетов |
| `FEATURE_MOBILE_APP` | boolean | `false` | Поддержка мобильного приложения |
| `FEATURE_BETA_TESTING` | boolean | `false` | Режим бета-тестирования |

---

## 🛠️ Разработка и отладка

| Переменная | Тип | Пример | Описание |
|------------|-----|--------|----------|
| `TELESCOPE_ENABLED` | boolean | `false` | Включить Laravel Telescope (dev) |
| `DEBUGBAR_ENABLED` | boolean | `false` | Включить Debug Bar (dev) |
| `IGNORED_PACKAGES` | CSV | `vendor/package1` | Игнорируемые пакеты (для анализа) |
| `XDEBUG_ENABLED` | boolean | `false` | Включить Xdebug |
| `XDEBUG_REMOTE_PORT` | integer | `9003` | Порт для отладчика |

---

## 📦 Переменные для Docker

| Переменная | Тип | Пример | Описание |
|------------|-----|--------|----------|
| `DOCKER_PHP_VERSION` | string | `8.2` | Версия PHP в контейнере |
| `DOCKER_NODE_VERSION` | string | `18` | Версия Node.js (для фронтенда) |
| `DOCKER_COMPOSE_PROJECT_NAME` | string | `ai_story` | Имя проекта Docker Compose |
| `DOCKER_NETWORK_MODE` | string | `bridge` | Сетевой режим |
| `DOCKER_VOLUME_DRIVER` | string | `local` | Драйвер томов |

---

## 📋 Полный шаблон `.env.example`

```ini
# === Приложение ===
APP_NAME="AI Story Engine"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_TIMEZONE=UTC
APP_LOCALE=ru

# === База данных ===
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ai_story_db
DB_USERNAME=root
DB_PASSWORD=
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci

# === Redis ===
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=
REDIS_DB=0
REDIS_PREFIX=ai_story:

# === Безопасность ===
APP_KEY=
JWT_SECRET=change_me_in_production
JWT_TTL=60
JWT_REFRESH_TTL=10080
BCRYPT_ROUNDS=12

# === AI / Генерация ===
AI_PROVIDER=stability_ai
STABILITY_API_KEY=
DEFAULT_MODEL=stable-video-diffusion-img2vid-xt
FACE_CONSISTENCY_THRESHOLD=0.75
MOCK_RENDER=true

# === Хранение ===
STORAGE_PROVIDER=local
STORAGE_LOCAL_ROOT=./storage
STORAGE_LOCAL_URL=http://localhost:8000/storage

# === Очереди ===
QUEUE_DRIVER=redis
QUEUE_PRIORITY_FREE=1
QUEUE_PRIORITY_PREMIUM=5
QUEUE_PRIORITY_VIP=10

# === Монетизация ===
CURRENCY_NAME=coins
PRICE_VARIANT3=100
SUBSCRIPTION_MONTHLY_PRICE=4.99
AD_REWARD_AMOUNT=50

# === Платежи ===
PAYMENT_PROVIDER=manual
YOOKASSA_SHOP_ID=
YOOKASSA_SECRET_KEY=

# === Логирование ===
LOG_CHANNEL=single
LOG_LEVEL=debug

# === CORS ===
ALLOWED_ORIGINS=http://localhost:3000

# === Feature Flags ===
FEATURE_NEW_PLAYER_UI=false
FEATURE_AI_COPILOT=false
```

---

## 🔐 Генерация ключей

### APP_KEY (для шифрования)

```bash
# Для PHP/Laravel
php artisan key:generate --show

# Или через OpenSSL
openssl rand -base64 32
```

### JWT_SECRET

```bash
# Генерация случайной строки
openssl rand -base64 48

# Или через PHP
php -r "echo base64_encode(random_bytes(48));"
```

### ENCRYPTION_KEY (для контента)

```bash
# 32 байта для AES-256
openssl rand -base64 32
```

---

## ⚠️ Рекомендации по безопасности

1. **Никогда не коммитьте `.env` файлы с чувствительными данными в Git!**
2. Используйте разные ключи для каждой среды (dev/staging/prod)
3. Регулярно ротируйте секреты (минимум раз в 90 дней)
4. На production используйте secrets manager (HashiCorp Vault, AWS Secrets Manager)
5. Ограничьте доступ к переменным окружения на уровне процессов
6. Включите `APP_DEBUG=false` на production
7. Используйте HTTPS для всех внешних соединений

---

## 🔄 Применение изменений

После изменения `.env`:

```bash
# Очистить кэш конфигурации
php artisan config:clear
php artisan cache:clear

# Перезапустить очереди
php artisan queue:restart

# Перезапустить воркеров
supervisorctl restart all
```

Для Docker:

```bash
docker compose down
docker compose up -d
```

---

*Документация актуальна для версии 1.0 (MVP)*  
*Последнее обновление: 2024-06-15*
