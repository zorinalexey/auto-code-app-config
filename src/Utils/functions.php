<?php

use AutoCode\AppConfig\Config;
use AutoCode\AppConfig\Env;

function config(string|null $configName = null, string|null $key = null, mixed $default = null): mixed
{
    return Config::getInstance()->get(configName: $configName, key: $key, default: $default);
}

function env(string $key, string|null $default = null): string|null
{
    return Env::getInstance()->get($key, $default);
}