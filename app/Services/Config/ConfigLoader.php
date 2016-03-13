<?php

namespace App\Services\Config;

/**
 * Class ConfigLoader
 *
 * @package    App\Services
 * @subpackage App\Services\ConfigLoader
 */
class ConfigLoader
{

    /**
     * @var string
     */
    protected $path;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var bool
     */
    protected $loaded = false;

    /**
     * Constructor.
     *
     * @param string $path
     */
    public function __construct($path = null)
    {
        $this->path = $path ?: realpath(__DIR__ . '/../../../config/config.php');
    }

    /**
     * @param null|string $path
     *
     * @return Config
     */
    public function load($path = null)
    {
        if ($this->loaded && $this->config instanceof Config) {
            return $this->config;
        }

        $path = $this->resolvePath($path);

        if (!file_exists($path)) {
            throw new \RuntimeException("Invalid config path: $path");
        }

        $config = require_once $path;
        $this->loaded = true;

        return $this->config = new Config($config);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function resolvePath($path)
    {
        if (!$path && $this->path) {
            $path = $this->path;
        }

        return $path;
    }
}
