<?php

namespace lav45\settings\tests;

use yii\helpers\Json;
use lav45\settings\Settings;
use PHPUnit\Framework\TestCase;

/**
 * Class SettingsTest
 * @package tests
 */
class SettingsTest extends TestCase
{
    /**
     * @return Settings
     */
    protected function getSettings()
    {
        return new Settings([
            'storage' => 'lav45\settings\tests\FakeStorage',
        ]);
    }

    public function testGetNotExistKey()
    {
        $settings = $this->getSettings();
        static::assertNull($settings->get('key'));
        static::assertEquals($settings->get('key', []), []);
    }

    /**
     * Data provider for [[testDisabledSerializer(), testSetData()]]
     * @return array test data
     */
    public function dataProviderExport()
    {
        return [
            [new \stdClass()],
            [['key' => 'value']],
            [123],
            [123.5],
            ['string'],
            [true],
            [false],
            [null],
            [''],
            [0],
        ];
    }

    /**
     * @dataProvider dataProviderExport
     *
     * @param mixed $value
     */
    public function testSetData($value)
    {
        $settings = $this->getSettings();

        $key = 'key';

        static::assertTrue($settings->set($key, $value));
        static::assertEquals($settings->get($key), $value);

        // test disabled serializer
        $settings->serializer = false;

        static::assertTrue($settings->set($key, $value));
        static::assertEquals($settings->get($key), $value);
    }

    public function testUsageAsArray()
    {
        $settings = $this->getSettings();
        $data = ['data'];
        $settings['key'] = $data;
        static::assertTrue(isset($settings['key']));
        static::assertEquals($settings['key'], $data);
        unset($settings['key']);
        static::assertFalse(isset($settings['key']));
    }

    public function testArrayAccess()
    {
        $settings = $this->getSettings();
        $data = ['data'];
        $key = ['key'];
        static::assertTrue($settings->set($key, $data));
    }

    public function testSerialize()
    {
        $settings = $this->getSettings();
        $settings->serializer = ['yii\helpers\Json::encode', 'yii\helpers\Json::decode'];

        $data = ['data' => ['json_encode', 'json_decode']];
        $key = 'key';
        static::assertTrue($settings->set($key, $data));
        static::assertEquals($settings->get($key), $data);

        $encodeData = $settings->storage->getValue($key);
        $expected = Json::encode($data);

        static::assertEquals($expected, $encodeData);
    }

    public function testKeyPrefix()
    {
        $settings = $this->getSettings();

        $key = md5('key');
        $data = ['data'];
        static::assertTrue($settings->set($key, $data));
        static::assertEquals($settings->get($key), $data);

        $settings->keyPrefix = md5('keyPrefix');
        static::assertNull($settings->get($key));
        $data2 = ['data2'];
        static::assertTrue($settings->set($key, $data2));
        static::assertEquals($settings->get($key), $data2);

        $settings->keyPrefix = null;
        static::assertEquals($settings->get($key), $data);
    }
}