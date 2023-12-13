<?php

namespace AutoCode\AppConfig;

use AutoCode\AppConfig\Interfaces\ConfigInterface;

final class Config implements ConfigInterface
{
    private static self|null $instance = null;

    private static array $config = [];

    private function __construct()
    {

    }

    public static function getInstance(): ConfigInterface
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function load(string $configPath, string|null $configName = null): ConfigInterface
    {
        if (is_file($configPath)  && ($data = require $configPath) && is_array($data)) {
            self::$config[$this->getConfigName($configName)] = [
                ...$this->get(configName: $configName, default: []),
                ...$data
            ];
        }

        return $this;
    }

    private function getConfigName(string|null $configName = null): string
    {
        if (!$configName) {
            return 'app';
        }

        return str_replace(['\\', '/', '.php'], ['.', '.', ''], $configName);
    }

    public function get(string|null $configName = null, string|null $key = null, mixed $default = null): mixed
    {
        $configName = $this->getConfigName($configName);

        if ($key && isset(self::$config[$configName][$key])) {
            return self::$config[$configName][$key];
        }

        if (!$key && isset(self::$config[$configName])) {
            return self::$config[$configName];
        }

        return $default;
    }

    public function set(string $configName, string $key, mixed $value): ConfigInterface
    {
        self::$config[$this->getConfigName($configName)][$key] = $value;

        return $this;
    }

    private function __clone()
    {

    }
}