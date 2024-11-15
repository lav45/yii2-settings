<?php
/**
 * @link https://github.com/lav45/yii2-settings
 * @copyright Copyright (c) 2016 LAV45
 * @author Alexey Loban <lav451@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\settings\behaviors;

use yii\di\Instance;
use yii\base\Behavior;
use yii\caching\Cache;
use lav45\settings\Settings;
use lav45\settings\events\GetEvent;
use lav45\settings\events\SetEvent;
use lav45\settings\events\DeleteEvent;

/**
 * Class CacheBehavior
 * @package lav45\settings\behaviors
 *
 * @property Settings $owner
 */
class CacheBehavior extends Behavior
{
    /**
     * @var Cache|string|array
     */
    public $cache = 'cache';
    /**
     * @var integer
     */
    public $cacheDuration = 3600;
    /**
     * @var \yii\caching\Dependency
     */
    public $cacheDependency;

    public function init()
    {
        parent::init();
        $this->cache = clone Instance::ensure($this->cache, Cache::class);
        $this->cache->serializer = false;
    }

    public function events()
    {
        return [
            Settings::EVENT_BEFORE_GET => 'beforeGetValue',
            Settings::EVENT_AFTER_GET => 'afterGetValue',
            Settings::EVENT_AFTER_SET => 'afterSetValue',
            Settings::EVENT_AFTER_DELETE => 'afterDeleteValue',
        ];
    }

    public function beforeGetValue(GetEvent $event)
    {
        $key = $this->owner->buildKey($event->key);
        $value = $this->cache->get($key);
        if ($value !== false) {
            $event->value = $value;
        }
    }

    public function afterGetValue(GetEvent $event)
    {
        $this->setValue($event->key, $event->value);
    }

    public function afterSetValue(SetEvent $event)
    {
        $this->setValue($event->key, $event->value);
    }

    protected function setValue($key, $value)
    {
        $this->cache->set($key, $value, $this->cacheDuration, $this->cacheDependency);
    }

    public function afterDeleteValue(DeleteEvent $event)
    {
        $this->cache->delete($event->key);
    }
}