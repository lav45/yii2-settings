<?php

namespace lav45\settings\tests;

use Yii;

/**
 * Class QuickAccessSettingsTest
 * @package tests
 */
class QuickAccessSettingsTest extends \PHPUnit_Framework_TestCase
{
    protected function getSettings()
    {
        /** @var \lav45\settings\Settings|\lav45\settings\behaviors\CacheBehavior $object */
        $object = Yii::$app->get('settings');
        return $object;
    }

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        Yii::$app->set('settings', [
            'class' => 'lav45\settings\Settings',
            'storage' => 'lav45\settings\tests\FakeStorage',
            'as cache' => [
                'class' => 'lav45\settings\behaviors\CacheBehavior',
            ],
            'as access' => [
                'class' => 'lav45\settings\behaviors\QuickAccessBehavior',
            ],
        ]);
    }

    protected function setUp()
    {
        parent::setUp();
        $this->clearStorage();
        $this->getSettings()->cache->flush();
    }

    protected function clearStorage()
    {
        /** @var \lav45\settings\tests\FakeStorage $storage */
        $storage = $this->getSettings()->storage;
        $storage->flushValues();
    }

    public function testGetValue()
    {
        $settings = $this->getSettings();

        $data = [
            'options' => [
                'css' => ['bootstrap.css'],
                'js' => ['jquery', 'bootstrap.js']
            ]
        ];

        static::assertTrue($settings->set('array', $data));
        // find in cache
        static::assertEquals($settings->get('array.options.js'), $data['options']['js']);
        static::assertEquals($settings->get('array.options.js.0'), $data['options']['js'][0]);
        static::assertEquals($settings->get('array.options.css'), $data['options']['css']);
        static::assertEquals($settings['array.options.css'], $data['options']['css']);

        static::assertTrue($settings->cache->flush());
        // find in storage & cache again
        static::assertEquals($settings->get('array.options.js'), $data['options']['js']);
        static::assertEquals($settings->get('array.options.css'), $data['options']['css']);
        static::assertEquals($settings->get('array'), $data);
    }

    public function testGetDefaultValue()
    {
        $settings = $this->getSettings();
        static::assertNull($settings->get('array.options.img'));
        static::assertEquals($settings->get('array.options.img', []), []);
    }
}