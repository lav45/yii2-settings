<?php
/**
 * @link https://github.com/LAV45/yii2-settings
 * @copyright Copyright (c) 2016 LAV45
 * @author Alexey Loban <lav451@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\settings\helpers;

/**
 * Class BaseArrayHelper
 * @package lav45\settings\helpers
 */
class BaseArrayHelper extends \yii\helpers\BaseArrayHelper
{
    /**
     * ```php
     *  $array = [
     *      'key' => [
     *          'in' => [
     *              'val1',
     *              'key' => 'val'
     *          ]
     *      ]
     *  ];
     * ```
     *
     * The result of `ArrayHelper::setValue($array, 'key.in.0', ['arr' => 'val']);` could be like the following:
     *
     * ```php
     *  [
     *      'key' => [
     *          'in' => [
     *              ['arr' => 'val'],
     *              'key' => 'val'
     *          ]
     *      ]
     *  ]
     *
     * ```
     * The result of `ArrayHelper::setValue($array, 'key.in', ['arr' => 'val']);` could be like the following:
     *
     * ```php
     *  [
     *      'key' => [
     *          'in' => [
     *              'arr' => 'val'
     *          ]
     *      ]
     *  ]
     * ```
     *
     * @param array $array
     * @param string $key
     * @param mixed $value
     * @return array
     */
    public static function setValue(array $array, $key, $value)
    {
        if (($pos = strpos($key, '.')) !== false) {
            $left_key = substr($key, 0, $pos);
            $right_key = substr($key, $pos + 1);

            if (isset($array[$left_key])) {
                $data = $array[$left_key];
                if (!is_array($data)) {
                    $data = [$data];
                }
            } else {
                $data = [];
            }

            $array[$left_key] = static::setValue($data, $right_key, $value);
        } else {
            $array[$key] = $value;
        }

        return $array;
    }
}