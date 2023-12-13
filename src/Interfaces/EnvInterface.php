<?php

namespace AutoCode\AppConfig\Interfaces;

interface EnvInterface
{
    public static function getInstance(string|null $envFile = null): self;

    public function set(string $key, string $value): self;

    public function get(string $key, string|null $default = null): string|null;
}