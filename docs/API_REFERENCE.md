# 📡 API Documentation (REST)

**Базовый URL:** `https://api.aistory.engine/v1`  
**Аутентификация:** Bearer Token (JWT)  
**Формат данных:** JSON  
**Кодировка:** UTF-8

---

## 🔐 Аутентификация

### POST `/auth/register` — Регистрация пользователя

**Request:**
```json
{
  "username": "player123",
  "email": "player@example.com",
  "password": "SecurePass123!",
  "role": "player"
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "data": {
    "user_id": 42,
    "username": "player123",
    "access_token": "eyJhbGciOiJIUzI1NiIs...",
    "refresh_token": "dGhpcyBpcyBhIHJlZnJl...",
    "expires_in": 3600
  }
}
```

**Errors:**
- `400 Bad Request` — Email уже занят / слабый пароль
- `422 Unprocessable Entity` — Неверный формат email

---

### POST `/auth/login` — Вход

**Request:**
```json
{
  "email": "player@example.com",
  "password": "SecurePass123!"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "user_id": 42,
    "username": "player123",
    "is_subscriber": false,
    "currency_balance": 150,
    "access_token": "eyJhbGciOiJIUzI1NiIs...",
    "refresh_token": "dGhpcyBpcyBhIHJlZnJl...",
    "expires_in": 3600
  }
}
```

**Errors:**
- `401 Unauthorized` — Неверный email или пароль

---

### POST `/auth/refresh` — Обновление токена

**Headers:**
```
Authorization: Bearer <refresh_token>
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "access_token": "eyJhbGciOiJIUzI1NiIs...",
    "expires_in": 3600
  }
}
```

---

### POST `/auth/logout` — Выход

**Headers:**
```
Authorization: Bearer <access_token>
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Successfully logged out"
}
```

---

## 👤 Пользователи

### GET `/users/me` — Получить профиль текущего пользователя

**Headers:**
```
Authorization: Bearer <access_token>
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 42,
    "username": "player123",
    "email": "player@example.com",
    "role": "player",
    "is_subscriber": false,
    "subscription_expires_at": null,
    "currency_balance": 150,
    "created_at": "2024-06-01T12:00:00Z",
    "last_login_at": "2024-06-15T08:30:00Z"
  }
}
```

---

### PUT `/users/me` — Обновить профиль

**Headers:**
```
Authorization: Bearer <access_token>
```

**Request:**
```json
{
  "username": "new_username",
  "email": "newemail@example.com"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 42,
    "username": "new_username",
    "email": "newemail@example.com"
  }
}
```

---

### GET `/users/me/progress` — Прогресс пользователя по всем историям

**Headers:**
```
Authorization: Bearer <access_token>
```

**Query Parameters:**
- `limit` (int, default: 20)
- `offset` (int, default: 0)
- `status` (string: 'in_progress', 'completed', 'all')

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "items": [
      {
        "story_id": 10,
        "story_title": "Мистический лес",
        "current_scene_node_id": "scene_005",
        "completion_percentage": 45.5,
        "total_playtime_sec": 320,
        "last_played_at": "2024-06-14T20:15:00Z",
        "endings_unlocked": []
      }
    ],
    "pagination": {
      "total": 5,
      "limit": 20,
      "offset": 0
    }
  }
}
```

---

## 📚 Истории

### GET `/stories` — Список опубликованных историй

**Query Parameters:**
- `genre` (string, optional)
- `sort_by` (string: 'popular', 'newest', 'rating', default: 'popular')
- `limit` (int, default: 20)
- `offset` (int, default: 0)

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "items": [
      {
        "id": 10,
        "title": "Мистический лес",
        "description": "Тёмный лес хранит древние тайны...",
        "genre": "horror",
        "author_id": 5,
        "author_name": "StoryMaster",
        "cover_image_url": "https://cdn.aistory.engine/covers/10.jpg",
        "total_plays": 1250,
        "avg_completion_rate": 67.5,
        "published_at": "2024-06-01T10:00:00Z"
      }
    ],
    "pagination": {
      "total": 45,
      "limit": 20,
      "offset": 0
    }
  }
}
```

---

### GET `/stories/{id}` — Получить историю подробно

**Path Parameters:**
- `id` (int) — ID истории

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 10,
    "title": "Мистический лес",
    "description": "Тёмный лес хранит древние тайны...",
    "genre": "horror",
    "author_id": 5,
    "author_name": "StoryMaster",
    "cover_image_url": "https://cdn.aistory.engine/covers/10.jpg",
    "total_plays": 1250,
    "avg_completion_rate": 67.5,
    "characters": [
      {
        "id": 1,
        "name": "Алекс",
        "description": "Мужчина, 30 лет, детектив",
        "face_reference_grid": ["url1", "url2", "url3"]
      }
    ],
    "scenes_count": 25,
    "published_at": "2024-06-01T10:00:00Z"
  }
}
```

**Errors:**
- `404 Not Found` — История не найдена или не опубликована

---

### GET `/stories/{id}/start` — Начать прохождение истории

**Headers:**
```
Authorization: Bearer <access_token>
```

**Path Parameters:**
- `id` (int) — ID истории

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "progress_id": 100,
    "story_id": 10,
    "current_scene": {
      "scene_node_id": "scene_001",
      "title": "Вход в лес",
      "video_url": "https://cdn.aistory.engine/videos/10/scene_001_base.mp4",
      "thumbnail_url": "https://cdn.aistory.engine/thumbs/10/scene_001.jpg",
      "duration_sec": 60.0,
      "choices": [
        {
          "variant_number": 1,
          "button_text": "Пойти налево",
          "button_description": "Исследовать тропинку",
          "is_premium": false,
          "price_in_currency": null,
          "vip_only": false,
          "state": "available"
        },
        {
          "variant_number": 2,
          "button_text": "Пойти направо",
          "button_description": "Следовать за светом",
          "is_premium": false,
          "price_in_currency": null,
          "vip_only": false,
          "state": "available"
        },
        {
          "variant_number": 3,
          "button_text": "✨ Использовать артефакт",
          "button_description": "Магический выбор!",
          "is_premium": true,
          "price_in_currency": 100,
          "vip_only": false,
          "state": "locked",
          "lock_reason": "requires_payment"
        },
        {
          "variant_number": 4,
          "button_text": "👑 Путь избранного",
          "button_description": "Уникальная ветка сюжета",
          "is_premium": true,
          "price_in_currency": null,
          "vip_only": true,
          "state": "locked",
          "lock_reason": "requires_subscription"
        }
      ]
    }
  }
}
```

**Errors:**
- `404 Not Found` — История не найдена
- `403 Forbidden` — История ещё не опубликована

---

## 🎮 Игровой процесс

### POST `/play/{progress_id}/choice` — Сделать выбор в сцене

**Headers:**
```
Authorization: Bearer <access_token>
Content-Type: application/json
```

**Path Parameters:**
- `progress_id` (int) — ID прогресса прохождения

**Request:**
```json
{
  "scene_node_id": "scene_001",
  "chosen_variant": 2,
  "payment_method": null
}
```

**Response для бесплатного выбора (200 OK):**
```json
{
  "success": true,
  "data": {
    "choice_recorded": true,
    "next_scene": {
      "scene_node_id": "scene_003",
      "title": "На развилке",
      "video_url": "https://cdn.aistory.engine/videos/10/scene_003_base.mp4",
      "thumbnail_url": "https://cdn.aistory.engine/thumbs/10/scene_003.jpg",
      "duration_sec": 60.0,
      "choices": [...]
    },
    "user_currency_balance": 150,
    "transition_effect": "crossfade",
    "audio_crossfade_ms": 500
  }
}
```

**Response для платного выбора (требуется подтверждение) (202 Accepted):**
```json
{
  "success": true,
  "data": {
    "choice_pending": true,
    "payment_required": {
      "amount": 100,
      "currency": "coins",
      "current_balance": 80,
      "shortfall": 20
    },
    "message": "Недостаточно монет. Пополните баланс или посмотрите рекламу."
  }
}
```

**Errors:**
- `400 Bad Request` — Недоступный вариант выбора
- `404 Not Found` — Прогресс или сцена не найдены
- `409 Conflict` — Сцена уже пройдена

---

### POST `/play/{progress_id}/unlock-premium` — Разблокировать премиум-выбор

**Headers:**
```
Authorization: Bearer <access_token>
Content-Type: application/json
```

**Path Parameters:**
- `progress_id` (int)

**Request (вариант A: оплата монетами):**
```json
{
  "scene_node_id": "scene_001",
  "variant_number": 3,
  "payment_method": "currency"
}
```

**Request (вариант B: просмотр рекламы):**
```json
{
  "scene_node_id": "scene_001",
  "variant_number": 3,
  "payment_method": "ad",
  "ad_provider": "yandex_games",
  "ad_placement_id": "VI-12345"
}
```

**Response после успешной оплаты (200 OK):**
```json
{
  "success": true,
  "data": {
    "unlocked": true,
    "transaction_id": 500,
    "amount_charged": 100,
    "new_balance": 50,
    "next_scene": {
      "scene_node_id": "scene_004_premium",
      "title": "Тайная тропа",
      "video_url": "https://cdn.aistory.engine/videos/10/scene_004_premium.mp4",
      "thumbnail_url": "https://cdn.aistory.engine/thumbs/10/scene_004.jpg",
      "duration_sec": 60.0,
      "is_premium_content": true,
      "choices": [...]
    }
  }
}
```

**Errors:**
- `402 Payment Required` — Недостаточно средств
- `400 Bad Request` — Недоступный вариант

---

### GET `/play/{progress_id}/preload` — Предзагрузка следующей сцены

**Headers:**
```
Authorization: Bearer <access_token>
```

**Path Parameters:**
- `progress_id` (int)

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "preload_urls": [
      {
        "scene_node_id": "scene_005",
        "variant_type": "base_1",
        "video_url": "https://cdn.aistory.engine/videos/10/scene_005_base1.mp4",
        "priority": "high"
      },
      {
        "scene_node_id": "scene_005",
        "variant_type": "base_2",
        "video_url": "https://cdn.aistory.engine/videos/10/scene_005_base2.mp4",
        "priority": "medium"
      }
    ],
    "estimated_size_mb": 45.2
  }
}
```

---

## 💰 Платежи и валюта

### GET `/wallet/balance` — Баланс кошелька

**Headers:**
```
Authorization: Bearer <access_token>
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "currency_balance": 150,
    "currency_name": "coins",
    "pending_transactions": [],
    "last_updated": "2024-06-15T10:30:00Z"
  }
}
```

---

### POST `/wallet/purchase-currency` — Покупка валюты

**Headers:**
```
Authorization: Bearer <access_token>
Content-Type: application/json
```

**Request:**
```json
{
  "package_id": "coins_500",
  "payment_provider": "yookassa",
  "return_url": "https://aistory.engine/wallet/success"
}
```

**Доступные пакеты:**
```json
{
  "packages": [
    {"id": "coins_100", "amount": 100, "price_rub": 99},
    {"id": "coins_500", "amount": 500, "price_rub": 399},
    {"id": "coins_1200", "amount": 1200, "price_rub": 799}
  ]
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "transaction_id": 501,
    "payment_url": "https://yookassa.ru/payment/...",
    "confirmation_token": "conf_abc123",
    "expires_at": "2024-06-15T11:30:00Z"
  }
}
```

---

### POST `/wallet/webhook/{provider}` — Вебхук от платёжной системы

**Headers:**
```
X-Provider-Signature: <signature>
```

**Request (YooKassa example):**
```json
{
  "event": "payment.succeeded",
  "object": {
    "id": "2d3df78f-000f-500b-9000-1a5f8bde03c7",
    "status": "succeeded",
    "amount": {"value": "399.00", "currency": "RUB"},
    "metadata": {
      "user_id": "42",
      "package_id": "coins_500"
    }
  }
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "message": "Webhook processed"
}
```

---

### GET `/wallet/transactions` — История транзакций

**Headers:**
```
Authorization: Bearer <access_token>
```

**Query Parameters:**
- `type` (string: 'all', 'purchase', 'reward', 'spending')
- `limit` (int, default: 20)

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "items": [
      {
        "id": 500,
        "type": "spending",
        "amount": -100,
        "currency_type": "coins",
        "description": "Разблокировка премиум-выбора в истории «Мистический лес»",
        "status": "completed",
        "created_at": "2024-06-15T10:25:00Z"
      },
      {
        "id": 499,
        "type": "reward",
        "amount": 50,
        "currency_type": "coins",
        "description": "Награда за ежедневное задание",
        "status": "completed",
        "created_at": "2024-06-15T09:00:00Z"
      }
    ],
    "pagination": {
      "total": 25,
      "limit": 20,
      "offset": 0
    }
  }
}
```

---

## 📜 Подписка

### GET `/subscription/status` — Статус подписки

**Headers:**
```
Authorization: Bearer <access_token>
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "is_subscriber": false,
    "subscription_plan": null,
    "expires_at": null,
    "auto_renew": false,
    "vip_benefits": {
      "variant4_access": false,
      "ad_free": false,
      "priority_rendering": false,
      "currency_multiplier": 1.0
    }
  }
}
```

---

### POST `/subscription/purchase` — Оформить подписку

**Headers:**
```
Authorization: Bearer <access_token>
Content-Type: application/json
```

**Request:**
```json
{
  "plan": "monthly",
  "payment_provider": "yookassa",
  "return_url": "https://aistory.engine/subscription/success"
}
```

**Доступные планы:**
```json
{
  "plans": [
    {"id": "monthly", "price_rub": 299, "duration_days": 30},
    {"id": "quarterly", "price_rub": 749, "duration_days": 90},
    {"id": "yearly", "price_rub": 1999, "duration_days": 365}
  ]
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "transaction_id": 502,
    "payment_url": "https://yookassa.ru/payment/...",
    "confirmation_token": "sub_conf_xyz789",
    "expires_at": "2024-06-15T11:30:00Z"
  }
}
```

---

### POST `/subscription/cancel` — Отменить подписку

**Headers:**
```
Authorization: Bearer <access_token>
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "cancelled": true,
    "access_until": "2024-07-15T00:00:00Z",
    "message": "Подписка будет активна до конца оплаченного периода"
  }
}
```

---

## 🎯 Ежедневные задания

### GET `/quests/daily` — Список ежедневных заданий

**Headers:**
```
Authorization: Bearer <access_token>
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "resets_at": "2024-06-16T00:00:00Z",
    "quests": [
      {
        "id": 1001,
        "quest_type": "play_scene",
        "description": "Пройдите 5 сцен в любой истории",
        "target_value": 5,
        "current_value": 3,
        "reward_amount": 50,
        "is_completed": false,
        "is_claimed": false
      },
      {
        "id": 1002,
        "quest_type": "watch_ad",
        "description": "Посмотрите 3 рекламных ролика",
        "target_value": 3,
        "current_value": 3,
        "reward_amount": 75,
        "is_completed": true,
        "is_claimed": false
      }
    ]
  }
}
```

---

### POST `/quests/{id}/claim` — Забрать награду за задание

**Headers:**
```
Authorization: Bearer <access_token>
```

**Path Parameters:**
- `id` (int) — ID задания

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "claimed": true,
    "reward_amount": 75,
    "new_balance": 225,
    "quest_status": "claimed"
  }
}
```

**Errors:**
- `400 Bad Request` — Задание ещё не выполнено или награда уже забрана

---

## 🎬 Для авторов (Author API)

### POST `/author/stories` — Создать новую историю

**Headers:**
```
Authorization: Bearer <access_token>
Content-Type: application/json
```

**Request:**
```json
{
  "title": "Новое приключение",
  "description": "Захватывающая история о...",
  "genre": "adventure",
  "initial_scene": {
    "title": "Начало пути",
    "prompt_base": "Герой стоит на пороге старого замка..."
  }
}
```

**Response (201 Created):**
```json
{
  "success": true,
  "data": {
    "story_id": 15,
    "title": "Новое приключение",
    "status": "draft",
    "created_at": "2024-06-15T12:00:00Z"
  }
}
```

---

### PUT `/author/stories/{id}` — Обновить историю

**Headers:**
```
Authorization: Bearer <access_token>
Content-Type: application/json
```

**Path Parameters:**
- `id` (int) — ID истории

**Request:**
```json
{
  "title": "Обновлённое название",
  "description": "Новое описание",
  "genre": "mystery"
}
```

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "id": 15,
    "title": "Обновлённое название",
    "updated_at": "2024-06-15T12:30:00Z"
  }
}
```

---

### POST `/author/stories/{id}/characters` — Добавить персонажа

**Headers:**
```
Authorization: Bearer <access_token>
Content-Type: application/json
```

**Path Parameters:**
- `id` (int) — ID истории

**Request:**
```json
{
  "name": "Алекс",
  "description": "Мужчина, 30 лет, детектив со шрамом",
  "generate_references": true
}
```

**Response (202 Accepted):**
```json
{
  "success": true,
  "data": {
    "character_id": 20,
    "name": "Алекс",
    "generation_status": "queued",
    "estimated_time_sec": 120,
    "task_id": "gen_char_abc123"
  }
}
```

---

### GET `/author/stories/{id}/render-status` — Статус рендеринга сцен

**Headers:**
```
Authorization: Bearer <access_token>
```

**Path Parameters:**
- `id` (int) — ID истории

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "story_id": 15,
    "total_scenes": 10,
    "rendered": 7,
    "processing": 2,
    "queued": 1,
    "failed": 0,
    "scenes": [
      {
        "scene_node_id": "scene_001",
        "status": "completed",
        "video_url": "https://cdn.aistory.engine/videos/15/scene_001.mp4",
        "similarity_score": 0.89
      },
      {
        "scene_node_id": "scene_002",
        "status": "processing",
        "progress_percent": 65,
        "estimated_remaining_sec": 45
      }
    ]
  }
}
```

---

### POST `/author/stories/{id}/publish` — Опубликовать историю

**Headers:**
```
Authorization: Bearer <access_token>
```

**Path Parameters:**
- `id` (int) — ID истории

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "published": true,
    "story_id": 15,
    "public_url": "https://aistory.engine/play/15",
    "published_at": "2024-06-15T14:00:00Z"
  }
}
```

**Errors:**
- `400 Bad Request` — Не все сцены отрендерены
- `400 Bad Request` — Нет вариантов выбора для сцен

---

### GET `/author/stories/{id}/analytics` — Аналитика истории

**Headers:**
```
Authorization: Bearer <access_token>
```

**Path Parameters:**
- `id` (int) — ID истории

**Query Parameters:**
- `period` (string: '7d', '30d', 'all')

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "story_id": 15,
    "period": "7d",
    "metrics": {
      "total_plays": 350,
      "unique_players": 280,
      "avg_completion_rate": 62.5,
      "total_revenue_rub": 1250.00,
      "ad_views": 420,
      "premium_choices": {
        "variant3_purchases": 85,
        "variant4_unlocks": 45
      },
      "drop_off_scenes": [
        {"scene_node_id": "scene_005", "drop_off_rate": 35.2}
      ]
    }
  }
}
```

---

## 📊 Аналитика

### GET `/analytics/events` — События аналитики (для внутренних систем)

**Headers:**
```
Authorization: Bearer <admin_token>
```

**Query Parameters:**
- `event_type` (string, optional)
- `story_id` (int, optional)
- `from_date` (ISO 8601)
- `to_date` (ISO 8601)
- `limit` (int, default: 100)

**Response (200 OK):**
```json
{
  "success": true,
  "data": {
    "items": [
      {
        "id": 100001,
        "event_type": "choice_made",
        "user_id": 42,
        "story_id": 10,
        "scene_id": 5,
        "event_data": {
          "chosen_variant": 3,
          "payment_method": "currency"
        },
        "created_at": "2024-06-15T10:25:00Z"
      }
    ],
    "pagination": {
      "total": 5000,
      "limit": 100,
      "offset": 0
    }
  }
}
```

---

## ❌ Обработка ошибок

### Стандартный формат ошибок

```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Некорректные данные в запросе",
    "details": [
      {
        "field": "email",
        "message": "Неверный формат email"
      }
    ],
    "request_id": "req_abc123xyz"
  }
}
```

### Коды ошибок

| HTTP Status | Code | Описание |
|-------------|------|----------|
| 400 | `VALIDATION_ERROR` | Ошибка валидации входных данных |
| 401 | `UNAUTHORIZED` | Токен не предоставлен или истёк |
| 403 | `FORBIDDEN` | Нет доступа к ресурсу |
| 404 | `NOT_FOUND` | Ресурс не найден |
| 409 | `CONFLICT` | Конфликт состояния (уже пройдено) |
| 422 | `PROCESSING_ERROR` | Ошибка обработки данных |
| 429 | `RATE_LIMITED` | Слишком много запросов |
| 500 | `INTERNAL_ERROR` | Внутренняя ошибка сервера |
| 503 | `SERVICE_UNAVAILABLE` | Сервис временно недоступен |

---

## 🔧 Rate Limiting

| Endpoint | Лимит | Окно |
|----------|-------|------|
| `/auth/*` | 10 запросов | 1 минута |
| `/play/*` | 60 запросов | 1 минута |
| `/author/*` | 30 запросов | 1 минута |
| Все остальные | 100 запросов | 1 минута |

**Headers ответа:**
```
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1623764400
```

---

## 🌐 Webhooks (для внешних интеграций)

### События вебхуков

1. **story.published** — История опубликована
2. **render.completed** — Рендеринг завершён
3. **payment.received** — Платёж получен
4. **subscription.activated** — Подписка активирована

**Пример payload:**
```json
{
  "event": "story.published",
  "timestamp": "2024-06-15T14:00:00Z",
  "data": {
    "story_id": 15,
    "author_id": 5,
    "title": "Новое приключение",
    "public_url": "https://aistory.engine/play/15"
  },
  "signature": "sha256=abc123..."
}
```

---

*Версия API: 1.0 (MVP)*  
*Последнее обновление: 2024-06-15*
