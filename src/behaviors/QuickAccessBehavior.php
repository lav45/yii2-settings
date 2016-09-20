<?php
/**
 * @link https://github.com/LAV45/yii2-settings
 * @copyright Copyright (c) 2016 LAV45
 * @author Alexey Loban <lav451@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\settings\behaviors;

use yii\base\Behavior;
use yii\helpers\ArrayHelper;
use lav45\settings\Settings;
use lav45\settings\events\GetEvent;

/**
 * Class QuickAccessBehavior
 * @package lav45\settings\behaviors
 *
 * @property Settings $owner
 */
class QuickAccessBehavior extends Behavior
{
    /**
     * @var bool
     */
    private $_originKey;

    public function attach($owner)
    {
        parent::attach($owner);
        $owner->on(Settings::EVENT_BEFORE_GET, [$this, 'beforeGetValue'], null, false);
        $owner->on(Settings::EVENT_AFTER_DECODE_VALUE, [$this, 'afterDecodeValue']);
    }

    public function beforeGetValue(GetEvent $event)
    {
        $key = $event->key;
        if (($pos = strpos($key, '.')) === false) {
            $this->_originKey = null;
        } else {
            $this->_originKey = $key;
            $event->key = substr($key, 0, $pos);
        }
    }

    public function afterDecodeValue(GetEvent $event)
    {
        if ($this->_originKey !== null) {
            $key = substr($this->_originKey, strlen($event->key) + 1);
            $event->value = ArrayHelper::getValue($event->value, $key, $event->default);
        }
    }
}