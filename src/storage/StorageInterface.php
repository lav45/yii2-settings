<?php
/**
 * @link https://github.com/lav45/yii2-settings
 * @copyright Copyright (c) 2016 LAV45
 * @author Aleksey Loban <lav451@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\settings\storage;

/**
 * Interface StorageInterface
 * @package lav45\settings\storage
 */
interface StorageInterface
{
    /**
     * @param string $key
     * @return false|null|string
     */
    public function getValue($key);

    /**
     * @param string $key
     * @param string $value
     * @return boolean
     */
    public function setValue($key, $value);

    /**
     * @param string $key
     * @return boolean
     */
    public function deleteValue($key);
}