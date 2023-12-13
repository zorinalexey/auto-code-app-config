# auto-code/app-config

Библиотека классов для инициализации окружения и конфигурации приложения
Также в состав входят вспомогательные функции (хелперы) в глобальной области видимости
для легкого получения значения переменной окружения (например: env('APP_NAME', 'new name')) или 
конфигурации части приложения (например config('app', null, [])) 
либо значения по ключу в части переменной (например config('app', 'key', null))

#### ---------- Рекомендуемый способ установки --------------

composer require auto-code/app-config


#### ---------- Примеры использования --------------
```php

<?php

use AutoCode\AppConfig\Env;
use AutoCode\AppConfig\Config;

require_once 'vendor/autoload.php';

// Инициализация переменных окружения
$env = Env::getInstance('.env');

// Установка переменной окружения
$env->set('varName', 'value');

// Получение значения переменной окружения
$var = $env->get('varName');
// или через глобальный хелпер  
$var = env('varName');


// Инициализация конфигурации приложения
$config = Config::getInstance();

// Загрузка конфигурации приложения
$config->load('app.php', 'app');

// Установка значения конфигурации
$config->set('app', 'app_name', 'my app');

// Получение значения конфигурации
$conf = $config->get('app');
// или через глобальный хелпер  
$conf = config('app');


```