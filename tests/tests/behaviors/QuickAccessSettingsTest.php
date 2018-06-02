<?php

namespace lav45\settings\tests\behaviors;

use lav45\settings\Settings;
use lav45\settings\behaviors\CacheBehavior;
use lav45\settings\behaviors\QuickAccessBehavior;
use lav45\settings\tests\models\LocalStorage;
use PHPUnit\Framework\TestCase;

/**
 * Class QuickAccessSettingsTest
 * @package tests
 */
class QuickAccessSettingsTest extends TestCase
{
    /**
     * @return Settings|CacheBehavior|QuickAccessBehavior
     */
    protected function getSettings()
    {
        /** @var Settings|CacheBehavior|QuickAccessBehavior $settings */
        $settings = new Settings([
            'storage' => [
                'class' => LocalStorage::class,
            ],
            'as cache' => [
                'class' => CacheBehavior::class,
            ],
            'as access' => [
                'class' => QuickAccessBehavior::class,
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

        $this->assertTrue($settings->set('array', $data));
        // find in cache
        $this->assertEquals($settings->get('array.options.js'), $data['options']['js']);
        $this->assertEquals($settings->get('array.options.js.0'), $data['options']['js'][0]);
        $this->assertEquals($settings->get('array.options.css'), $data['options']['css']);
        $this->assertEquals($settings['array.options.css'], $data['options']['css']);

        $this->assertTrue($settings->cache->flush());
        // find in storage & cache again
        $this->assertEquals($settings->get('array.options.js'), $data['options']['js']);
        $this->assertEquals($settings->get('array.options.css'), $data['options']['css']);
        $this->assertEquals($settings->get('array'), $data);
    }

    public function testGetDefaultValue()
    {
        $settings = $this->getSettings();
        $this->assertNull($settings->get('array.options.img'));
        $this->assertEquals($settings->get('array.options.img', []), []);
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

        $this->assertEquals($settings->get('app-config.user-list', []), []);
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

        $this->assertTrue($settings->set($key, $data));


        $expected = [
            'options' => [
                'css' => ['bootstrap.css'],
                'js' => ['jquery', 'bootstrap.js'],
                'img' => ['img.png', 'img.jpg']
            ]
        ];
        $this->assertTrue($settings->replace($key, 'options.img', ['img.png', 'img.jpg']));
        $this->assertEquals($expected, $settings->get($key));


        $expected = [
            'options' => [
                'css' => ['bootstrap.css'],
                'js' => ['jquery', 'bootstrap.js'],
                'img' => ['img.png']
            ]
        ];
        $this->assertTrue($settings->replace($key, 'options.img', ['img.png']));
        $this->assertEquals($expected, $settings->get($key));


        $expected = [
            'options' => [
                'css' => ['bootstrap.css'],
                'js' => ['jquery', 'bootstrap.js'],
                'img' => ['new.png']
            ]
        ];
        $this->assertTrue($settings->replace($key, 'options.img.0', 'new.png'));
        $this->assertEquals($expected, $settings->get($key));
    }
}