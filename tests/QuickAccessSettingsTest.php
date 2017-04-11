<?php

namespace lav45\settings\tests;

use lav45\settings\Settings;
use lav45\settings\behaviors\CacheBehavior;
use lav45\settings\behaviors\QuickAccessBehavior;

/**
 * Class QuickAccessSettingsTest
 * @package tests
 */
class QuickAccessSettingsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Settings|CacheBehavior|QuickAccessBehavior
     */
    protected function getSettings()
    {
        /** @var Settings|CacheBehavior|QuickAccessBehavior $settings */
        $settings = new Settings([
            'storage' => [
                'class' => 'lav45\settings\tests\FakeStorage',
            ],
            'as cache' => [
                'class' => 'lav45\settings\behaviors\CacheBehavior',
            ],
            'as access' => [
                'class' => 'lav45\settings\behaviors\QuickAccessBehavior',
            ],
        ]);

        $settings->cache->flush();

        return $settings;
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
    
    public function testDefaultValueDisabledSerialize()
    {
        $settings = $this->getSettings();
        $settings->serializer = false;
        $settings->detachBehavior('cache');

        $settings->set('app-config', [
            'db_name' => 'test',
            'db_host' => 'localhost',
        ]);

        static::assertEquals($settings->get('app-config.user-list', []), []);
    }

    public function testSetValue()
    {
        $settings = $this->getSettings();

        $key = 'array';
        $data = [
            'options' => [
                'css' => ['bootstrap.css'],
                'js' => ['jquery', 'bootstrap.js']
            ]
        ];

        static::assertTrue($settings->set($key, $data));


        $expected = [
            'options' => [
                'css' => ['bootstrap.css'],
                'js' => ['jquery', 'bootstrap.js'],
                'img' => ['img.png', 'img.jpg']
            ]
        ];
        static::assertTrue($settings->replace($key, 'options.img', ['img.png', 'img.jpg']));
        static::assertEquals($expected, $settings->get($key));


        $expected = [
            'options' => [
                'css' => ['bootstrap.css'],
                'js' => ['jquery', 'bootstrap.js'],
                'img' => ['img.png']
            ]
        ];
        static::assertTrue($settings->replace($key, 'options.img', ['img.png']));
        static::assertEquals($expected, $settings->get($key));


        $expected = [
            'options' => [
                'css' => ['bootstrap.css'],
                'js' => ['jquery', 'bootstrap.js'],
                'img' => ['new.png']
            ]
        ];
        static::assertTrue($settings->replace($key, 'options.img.0', 'new.png'));
        static::assertEquals($expected, $settings->get($key));
    }
}