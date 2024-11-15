<?php

namespace lav45\settings\tests\behaviors;

use Yii;
use lav45\settings\Settings;
use lav45\settings\behaviors\CacheBehavior;
use lav45\settings\tests\models\LocalStorage;
use PHPUnit\Framework\TestCase;

/**
 * Class CacheSettingsTest
 * @package tests
 */
class CacheSettingsTest extends TestCase
{
    /**
     * @return null|object|Settings|CacheBehavior
     */
    protected function getSettings()
    {
        return Yii::$app->get('settings');
    }

    public static function setUpBeforeClass(): void
    {
        Yii::$app->set('settings', [
            'class' => Settings::class,
            'storage' => LocalStorage::class,
            'as cache' => [
                'class' => CacheBehavior::class,
            ]
        ]);
    }

    protected function tearDown(): void
    {
        /** @var LocalStorage $storage */
        $storage = $this->getSettings()->storage;
        $storage->flush();

        $this->getSettings()->cache->flush();
    }

    public function testGetDataInCache()
    {
        $items = [
            'object' => new \stdClass(),
            'array' => ['data'],
            'integer' => 123,
            'float' => 123.5,
            'string' => 'string',
            'bool true' => true,
            'bool false' => false,
            'null' => null,
            'empty string' => '',
            'zero' => 0
        ];

        $settings = $this->getSettings();

        foreach ($items as $key => $data) {
            $this->assertTrue($settings->set($key, $data));
        }

        /** @var LocalStorage $storage */
        $storage = $settings->storage;

        $this->assertCount($storage->count(), $items);
        $storage->flush();

        foreach ($items as $key => $data) {
            $this->assertEquals($settings->get($key), $data);
        }

        $this->assertFalse($settings->delete('object'));
        $this->assertNull($settings->get('object'));
    }

    public function testGetDataWithoutCache()
    {
        $settings = $this->getSettings();
        $key = 'key';
        $data = ['data'];

        $this->assertTrue($settings->set($key, $data));
        $this->assertTrue($settings->cache->flush());
        $this->assertEquals($settings->get($key), $data);
    }
}