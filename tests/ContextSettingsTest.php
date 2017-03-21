<?php

namespace lav45\settings\tests;

use lav45\settings\Settings;
use lav45\settings\behaviors\ContextBehavior;

/**
 * Class ContextSettingsTest
 * @package tests
 */
class ContextSettingsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Settings|ContextBehavior
     */
    protected function getSettings()
    {
        return new Settings([
            'storage' => 'lav45\settings\tests\FakeStorage',
            'as access' => [
                'class' => 'lav45\settings\behaviors\ContextBehavior',
            ],
        ]);
    }

    public function testUsageContext()
    {
        $enSettings = $this->getSettings()->context('en-US');
        $ruSettings = $this->getSettings()->context('ru-RU');

        $items = [
            uniqid(),
            uniqid(),
            uniqid(),
            uniqid(),
            uniqid(),
        ];

        foreach ($items as $key => $data) {
            static::assertTrue($enSettings->set($key, 'en' . $data));
            static::assertNull($ruSettings->get($key));
            static::assertTrue($ruSettings->set($key, 'ru' . $data));
            static::assertNotNull($ruSettings->get($key));
        }
        foreach ($items as $key => $data) {
            static::assertEquals($enSettings->get($key), 'en' . $data);
            static::assertEquals($ruSettings->get($key), 'ru' . $data);
        }
    }
}