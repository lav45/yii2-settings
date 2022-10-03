<?php
/**
 * @link https://github.com/LAV45/yii2-settings
 * @copyright Copyright (c) 2016 LAV45
 * @author Alexey Loban <lav451@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\settings;

use lav45\settings\events\DecodeEvent;
use lav45\settings\events\DeleteEvent;
use lav45\settings\events\GetEvent;
use lav45\settings\events\SetEvent;
use lav45\settings\storage\DbStorage;
use lav45\settings\storage\StorageInterface;
use yii\base\Component;
use yii\di\Instance;
use yii\helpers\StringHelper;

/**
 * Class Settings
 * @package lav45\settings
 * @mixin \lav45\settings\behaviors\CacheBehavior
 * @mixin \lav45\settings\behaviors\QuickAccessBehavior
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
     * @var bool
     */
    public $buildKey = true;
    /**
     * @var array|boolean
     */
    public $serializer;
    /**
     * @var StorageInterface|string|array
     */
    public $storage = DbStorage::class;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->storage = Instance::ensure($this->storage, StorageInterface::class);
    }

    /**
     * @param string|array $key
     * @return string
     */
    public function buildKey($key)
    {
        if ($this->buildKey === true) {
            if (is_string($key)) {
                $key = StringHelper::byteLength($key) <= 32 ? $key : md5($key);
            } else {
                $key = md5(json_encode($key));
            }
        }
        return $this->keyPrefix . $key;
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function encode($value)
    {
        if ($this->serializer === null) {
            return serialize($value);
        }
        if ($this->serializer !== false) {
            return call_user_func($this->serializer[0], $value);
        }
        return $value;
    }

    /**
     * @param string $value
     * @return mixed
     */
    public function decode($value)
    {
        if ($this->serializer === null) {
            return @unserialize($value);
        }
        if ($this->serializer !== false) {
            return call_user_func($this->serializer[1], $value);
        }
        return $value;
    }

    /**
     * @param string|array $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $key = $this->buildKey($key);
        $value = $this->beforeGetValue($key);
        if ($value === null) {
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

    /**
     * @param string|array $key
     * @param mixed $value
     * @return boolean
     */
    public function set($key, $value)
    {
        $value = $this->beforeSetValue($key, $value);

        $key = $this->buildKey($key);
        $value = $this->encode($value);
        $result = $this->storage->setValue($key, $value);

        $this->afterSetValue($key, $value);

        return $result;
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function delete($key)
    {
        $this->beforeDeleteValue($key);

        $key = $this->buildKey($key);
        $result = $this->storage->deleteValue($key);

        $this->afterDeleteValue($key);

        return $result;
    }

    /**
     * @param string $key
     * @return string
     */
    protected function beforeGetValue(&$key)
    {
        $event = new GetEvent();
        $event->key = &$key;
        $this->trigger(self::EVENT_BEFORE_GET, $event);
        return $event->value;
    }

    /**
     * @param string $key
     * @param string $value
     * @return string
     */
    protected function afterGetValue($key, $value)
    {
        $event = new GetEvent();
        $event->key = $key;
        $event->value = $value;
        $this->trigger(self::EVENT_AFTER_GET, $event);
        return $event->value;
    }

    /**
     * @param string|array $key
     * @param mixed $value
     * @param mixed $default
     * @return mixed
     */
    protected function afterDecodeValue($key, $value, $default)
    {
        $event = new DecodeEvent();
        $event->key = $key;
        $event->value = $value;
        $event->default = $default;
        $this->trigger(self::EVENT_AFTER_DECODE_VALUE, $event);
        return $event->value;
    }

    /**
     * @param string|array $key
     * @param mixed $value
     * @return mixed
     */
    protected function beforeSetValue($key, $value)
    {
        $event = new SetEvent();
        $event->key = $key;
        $event->value = $value;
        $this->trigger(self::EVENT_BEFORE_SET, $event);
        return $event->value;
    }

    /**
     * @param string $key
     * @param string $value
     */
    protected function afterSetValue($key, $value)
    {
        $event = new SetEvent();
        $event->key = $key;
        $event->value = $value;
        $this->trigger(self::EVENT_AFTER_SET, $event);
    }

    /**
     * @param string $key
     */
    protected function beforeDeleteValue($key)
    {
        $event = new DeleteEvent();
        $event->key = $key;
        $this->trigger(self::EVENT_BEFORE_DELETE, $event);
    }

    /**
     * @param string $key
     */
    protected function afterDeleteValue($key)
    {
        $event = new DeleteEvent();
        $event->key = $key;
        $this->trigger(self::EVENT_AFTER_DELETE, $event);
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function offsetExists($key): bool
    {
        return $this->get($key) !== null;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function offsetGet($key): mixed
    {
        return $this->get($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function offsetSet($key, $value): void
    {
        $this->set($key, $value);
    }

    /**
     * @param string $key
     */
    public function offsetUnset($key): void
    {
        $this->delete($key);
    }
}