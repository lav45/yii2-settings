<?php
/**
 * @link https://github.com/lav45/yii2-settings
 * @copyright Copyright (c) 2016 LAV45
 * @author Alexey Loban <lav451@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\settings\behaviors;

use yii\base\Behavior;
use yii\helpers\ArrayHelper;
use lav45\settings\Settings;
use lav45\settings\events\GetEvent;
use lav45\settings\events\DecodeEvent;

/**
 * Class QuickAccessBehavior
 * @package lav45\settings\behaviors
 *
 * @property Settings $owner
 */
class QuickAccessBehavior extends Behavior
{
    /**
     * @var string
     */
    private $_originKey;

    /**
     * @param Settings $owner
     */
    public function attach($owner)
    {
        parent::attach($owner);
        $owner->on(Settings::EVENT_BEFORE_GET, [$this, 'beforeGetValue'], null, false);
        $owner->on(Settings::EVENT_AFTER_DECODE, [$this, 'afterDecodeValue']);
    }

    /**
     * @param GetEvent $event
     */
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

    /**
     * @param DecodeEvent $event
     */
    public function afterDecodeValue(DecodeEvent $event)
    {
        if ($this->_originKey === null) {
            return;
        }

        $key = substr($this->_originKey, strlen($event->key) + 1);
        if ($key !== false) {
            $event->value = ArrayHelper::getValue($event->value, $key, $event->default);
        }
    }

    /**
     * @param string|array $key
     * @param string $path
     * @param mixed $value
     * @return bool
     */
    public function replace($key, $path, $value)
    {
        $data = $this->owner->get($key, []);
        ArrayHelper::setValue($data, $path, $value);
        return $this->owner->set($key, $data);
    }
}