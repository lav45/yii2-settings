<?php
/**
 * @link https://github.com/lav45/yii2-settings
 * @copyright Copyright (c) 2016 LAV45
 * @author Alexey Loban <lav451@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\settings;

interface SettingsInterface extends \ArrayAccess
{
    /**
     * @param string|array $key
     * @param mixed|\Closure $default
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
     * @param string|array $key
     * @return boolean
     */
    public function delete($key);
}