<?php

namespace lav45\settings\tests\behaviors;

use lav45\settings\Settings;
use lav45\settings\behaviors\ContextBehavior;
use lav45\settings\tests\models\LocalStorage;
use PHPUnit\Framework\TestCase;

/**
 * Class ContextSettingsTest
 * @package tests
 */
class ContextSettingsTest extends TestCase
{
    /**
     * @return Settings|ContextBehavior
     */
    protected function getSettings()
    {
        return new Settings([
            'storage' => LocalStorage::class,
            'as access' => [
                'class' => ContextBehavior::class,
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
            $this->assertTrue($enSettings->set($key, 'en' . $data));
            $this->assertNull($ruSettings->get($key));
            $this->assertTrue($ruSettings->set($key, 'ru' . $data));
            $this->assertNotNull($ruSettings->get($key));
        }
        foreach ($items as $key => $data) {
            $this->assertEquals($enSettings->get($key), 'en' . $data);
            $this->assertEquals($ruSettings->get($key), 'ru' . $data);
        }
    }
}