<?php

namespace AutoCode\AppConfig;

use AutoCode\AppConfig\Interfaces\EnvInterface;
use RuntimeException;

final class Env implements EnvInterface
{
    private static array $instance = [];

    private array $env = [];

    private string|null $envFile = null;

    private function __clone()
    {

    }

    private function __construct()
    {

    }

    public static function getInstance(string|null $envFile = null): EnvInterface
    {
        if (!$envFile) {
            $env = '';
            $config = Config::getInstance()->get(
                configName: 'app',
                default: [
                    'app_path' => dirname(__DIR__, 4),
                    'app_env' => 'dev',
                ]
            );

            if ($config['app_env']) {
                $env = '.' . mb_strtolower($config['app_env']);
            }

            $envFile = dirname($config['app_path']) . DIRECTORY_SEPARATOR .
                basename($config['app_path']) . DIRECTORY_SEPARATOR . '.env' . $env;
        }

        $md5 = md5($envFile);

        if (!isset(self::$instance[$md5])) {
            self::$instance[$md5] = new self();
        }

        self::$instance[$md5]->envFile = $envFile;
        self::$instance[$md5]->loadFile();

        return self::$instance[$md5];
    }

    public function get(string $key, string|null $default = null): string|null
    {
        return $this->env[$key] ?? $default;
    }

    private function loadFile(): void
    {
        if (is_file($this->envFile) && ($fp = fopen($this->envFile, 'rb'))) {
            while (($string = fgets($fp, 4096)) !== false) {
                $this->parser($string);
            }
        }else{
            $dir = dirname($this->envFile);

            if(!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $dir));
            }

            file_put_contents($this->envFile, '');

            if(!is_file($this->envFile)){
                throw new RuntimeException(sprintf('File "%s" was not created', $this->envFile));
            }
        }
    }

    private function parser(bool|string $string): void
    {
        $key = '(?<key>\w+)(\s+)?=(\s+)?';
        $value = '(\'(.+)\'|"(.+)"|([\w\s\$\{\}]+))';
        $comment = '(\s+)?(#(\s+)?(.+))?';

        if (preg_match(sprintf('~%s%s%s~u', $key, $value, $comment), trim($string), $matches)) {
            if ($matches[7]) {
                $this->set($matches['key'], $matches[7]);
            }

            if ($matches[6]) {
                $this->set($matches['key'], $matches[6]);
            }

            if ($matches[5]) {
                $this->set($matches['key'], $matches[5]);
            }
        }
    }

    public function set(string $key, string $value): EnvInterface
    {
        $value = trim($value);

        if (
            preg_match('~\${(?<var>\w+)}~u', $value, $matches) &&
            $matches['var'] &&
            isset($this->env[$matches['var']])
        ) {
            $value = preg_replace('~\${' . $matches['var'] . '}~u', $this->env[$matches['var']], $value);
        }

        if ($value && putenv(sprintf('%s=%s', $key, $value))) {
            $this->env[$key] = $value;
        }

        return $this;
    }
}