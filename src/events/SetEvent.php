<?php
/**
 * @link https://github.com/lav45/yii2-settings
 * @copyright Copyright (c) 2016 LAV45
 * @author Aleksey Loban <lav451@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\settings\events;

use yii\base\Event;

/**
 * Class SetEvent
 * @package lav45\settings\events
 */
class SetEvent extends Event
{
    /**
     * @var string|array
     */
    public $key;
    /**
     * @var string
     */
    public $value;
}