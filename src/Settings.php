<?php
/**
 * @link https://github.com/LAV45/yii2-settings
 * @copyright Copyright (c) 2016 LAV45
 * @author Alexey Loban <lav451@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\settings;

use yii\di\Instance;
use yii\base\Component;
use yii\helpers\StringHelper;
use lav45\settings\events\GetEvent;
use lav45\settings\events\SetEvent;
use lav45\settings\events\DeleteEvent;
use lav45\settings\storage\StorageInterface;

/**
 * Class Settings
 * @package lav45\settings
 */
class Settings extends Component implements \ArrayAccess
{
    const EVENT_BEFORE_GET = 'beforeGetValue';
    const EVENT_AFTER_GET = 'afterGetValue';
    const EVENT_AFTER_DECODE_VALUE = 'afterDecodeValue';
    const EVENT_BEFORE_SET = 'beforeSetValue';
    const EVENT_AFTER_SET = 'afterSetValue';
    const EVENT_BEFORE_DELETE = 'beforeDeleteValue';
    const EVENT_AFTER_DELETE = 'afterDeleteValue';
    /**
     * @var string
     */
    public $keyPrefix;
    /**
     * @var array|boolean
     */
    public $serializer;
    /**
     * @var StorageInterface|string|array|callable
     */
    public $storage = 'lav45\settings\storage\DbStorage';

    public function init()
    {
        parent::init();
        $this->storage = Instance::ensure($this->storage, 'lav45\settings\storage\StorageInterface');
    }

    public function buildKey($key)
    {
        if (is_string($key)) {
            $key = ctype_alnum($key) && StringHelper::byteLength($key) <= 32 ? $key : md5($key);
        } else {
            $key = md5(json_encode($key));
        }
        return $this->keyPrefix . $key;
    }

    public function encode($value)
    {
        if ($this->serializer === null) {
            return serialize($value);
        } elseif ($this->serializer !== false) {
            return call_user_func($this->serializer[0], $value);
        } else {
            return $value;
        }
    }

    public function decode($value)
    {
        if ($this->serializer === null) {
            return unserialize($value);
        } elseif ($this->serializer !== false) {
            return call_user_func($this->serializer[1], $value);
        } else {
            return $value;
        }
    }

    public function get($key, $default = null)
    {
        $value = $this->beforeGetValue($key);
        if ($value === null) {
            $key = $this->buildKey($key);
            $value = $this->storage->getValue($key);
            $value = $this->afterGetValue($key, $value);
        }
        if ($value === false) {
            return $default;
        }

        $value = $this->decode($value);
        $value = $this->afterDecodeValue($key, $value, $default);

        return $value;
    }

    public function set($key, $value)
    {
        $this->beforeSetValue($key, $value);

        $key = $this->buildKey($key);
        $value = $this->encode($value);
        $result = $this->storage->setValue($key, $value);

        $this->afterSetValue($key, $value);

        return $result;
    }

    public function delete($key)
    {
        $this->beforeDeleteValue($key);

        $key = $this->buildKey($key);
        $result = $this->storage->deleteValue($key);

        $this->afterDeleteValue($key);

        return $result;
    }

    protected function beforeGetValue(&$key)
    {
        $event = new GetEvent();
        $event->key = &$key;
        $this->trigger(self::EVENT_BEFORE_GET, $event);
        return $event->value;
    }

    protected function afterGetValue($key, $value)
    {
        $event = new GetEvent();
        $event->key = $key;
        $event->value = $value;
        $this->trigger(self::EVENT_AFTER_GET, $event);
        return $event->value;
    }

    protected function afterDecodeValue($key, $value, $default)
    {
        $event = new GetEvent();
        $event->key = $key;
        $event->value = $value;
        $event->default = $default;
        $this->trigger(self::EVENT_AFTER_DECODE_VALUE, $event);
        return $event->value;
    }

    protected function beforeSetValue($key, $value)
    {
        $event = new SetEvent();
        $event->key = $key;
        $event->value = $value;
        $this->trigger(self::EVENT_BEFORE_SET, $event);
    }

    protected function afterSetValue($key, $value)
    {
        $event = new SetEvent();
        $event->key = $key;
        $event->value = $value;
        $this->trigger(self::EVENT_AFTER_SET, $event);
    }

    protected function beforeDeleteValue($key)
    {
        $event = new DeleteEvent();
        $event->key = $key;
        $this->trigger(self::EVENT_BEFORE_DELETE, $event);
    }

    protected function afterDeleteValue($key)
    {
        $event = new DeleteEvent();
        $event->key = $key;
        $this->trigger(self::EVENT_AFTER_DELETE, $event);
    }

    public function offsetExists($key)
    {
        return $this->get($key) !== null;
    }

    public function offsetGet($key)
    {
        return $this->get($key);
    }

    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    public function offsetUnset($key)
    {
        $this->delete($key);
    }
}
