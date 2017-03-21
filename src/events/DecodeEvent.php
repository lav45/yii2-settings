<?php
/**
 * @link https://github.com/LAV45/yii2-settings
 * @copyright Copyright (c) 2016 LAV45
 * @author Alexey Loban <lav451@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\settings\events;

use yii\base\Event;

/**
 * Class DecodeEvent
 * @package lav45\settings\events
 */
class DecodeEvent extends Event
{
    /**
     * @var string|array
     */
    public $key;
    /**
     * @var mixed
     */
    public $value;
    /**
     * @var mixed
     */
    public $default;
}