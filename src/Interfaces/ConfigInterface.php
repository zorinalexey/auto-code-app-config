<?php

namespace AutoCode\AppConfig\Interfaces;

interface ConfigInterface
{
    public static function getInstance(): self;

    public function load(string $configPath, string $configName): self;

    public function set(string $configName, string $key, mixed $value): self;

    public function get(string|null $configName = null, string|null $key = null, mixed $default = null): mixed;
}