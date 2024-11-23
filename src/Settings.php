<?php
/**
 * @link https://github.com/lav45/yii2-settings
 * @copyright Copyright (c) 2016 LAV45
 * @author Alexey Loban <lav451@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\settings;

use lav45\settings\behaviors\CacheBehavior;
use lav45\settings\behaviors\QuickAccessBehavior;
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
 * @mixin CacheBehavior
 * @mixin QuickAccessBehavior
 */
class Settings extends Component implements SettingsInterface
{
    public const EVENT_BEFORE_GET = 'beforeGet';
    public const EVENT_AFTER_GET = 'afterGet';
    public const EVENT_AFTER_DECODE = 'afterDecode';
    public const EVENT_BEFORE_SET = 'beforeSet';
    public const EVENT_AFTER_SET = 'afterSet';
    public const EVENT_BEFORE_DELETE = 'beforeDelete';
    public const EVENT_AFTER_DELETE = 'afterDelete';

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
                $key = md5(json_encode($key, JSON_THROW_ON_ERROR));
            }
        }
        return $this->keyPrefix . $key;
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected function encode($value)
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
    protected function decode($value)
    {
        if ($this->serializer === null) {
            return @unserialize($value, ['allowed_classes' => true]);
        }
        if ($this->serializer !== false) {
            return call_user_func($this->serializer[1], $value);
        }
        return $value;
    }

    /**
     * @param string|array $key
     * @param mixed|\Closure $default
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
            if ($default instanceof \Closure) {
                return $default();
            }
            return $default;
        }
        $value = $this->decode($value);
        return $this->afterDecodeValue($key, $value, $default);
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
        $this->trigger(self::EVENT_AFTER_DECODE, $event);
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
     * @param string|array $key
     */
    protected function beforeDeleteValue($key)
    {
        $event = new DeleteEvent();
        $event->key = $key;
        $this->trigger(self::EVENT_BEFORE_DELETE, $event);
    }

    /**
     * @param string|array $key
     */
    protected function afterDeleteValue($key)
    {
        $event = new DeleteEvent();
        $event->key = $key;
        $this->trigger(self::EVENT_AFTER_DELETE, $event);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        return $this->get($offset) !== null;
    }

    /**
     * @inheritDoc
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset): void
    {
        $this->delete($offset);
    }
}