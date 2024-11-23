<?php declare(strict_types=1);

namespace lav45\settings;

interface SettingsInterface extends \ArrayAccess
{
    /**
     * @param string|array $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * @param string|array $key
     * @param mixed $value
     * @return boolean
     */
    public function set($key, $value);

    /**
     * @param string $key
     * @return boolean
     */
    public function delete($key);
}