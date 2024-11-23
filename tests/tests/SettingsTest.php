<?php

namespace lav45\settings\tests;

use yii\helpers\Json;
use lav45\settings\Settings;
use lav45\settings\tests\models\LocalStorage;
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
            'storage' => LocalStorage::class,
        ]);
    }

    public function testGetNotExistKey()
    {
        $settings = $this->getSettings();
        $this->assertNull($settings->get('key'));
        $this->assertEquals($settings->get('key', []), []);
        $this->assertEquals($settings->get('key', function () { return true; }), true);
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

        $this->assertTrue($settings->set($key, $value));
        $this->assertEquals($settings->get($key), $value);

        // test disabled serializer
        $settings->serializer = false;

        $this->assertTrue($settings->set($key, $value));
        $this->assertEquals($settings->get($key), $value);
    }

    public function testUsageAsArray()
    {
        $settings = $this->getSettings();
        $data = ['data'];
        $settings['key'] = $data;
        $this->assertTrue(isset($settings['key']));
        $this->assertEquals($settings['key'], $data);
        unset($settings['key']);
        $this->assertFalse(isset($settings['key']));
    }

    public function testArrayAccess()
    {
        $settings = $this->getSettings();
        $data = ['data'];
        $key = ['key'];
        $this->assertTrue($settings->set($key, $data));
    }

    public function testSerialize()
    {
        $settings = $this->getSettings();
        $settings->serializer = ['yii\helpers\Json::encode', 'yii\helpers\Json::decode'];

        $data = ['data' => ['json_encode', 'json_decode']];
        $key = 'key';
        $this->assertTrue($settings->set($key, $data));
        $this->assertEquals($settings->get($key), $data);

        $encodeData = $settings->storage->getValue($key);
        $expected = Json::encode($data);

        $this->assertEquals($expected, $encodeData);
    }

    public function testKeyPrefix()
    {
        $settings = $this->getSettings();

        $key = md5('key');
        $data = ['data'];
        $this->assertTrue($settings->set($key, $data));
        $this->assertEquals($settings->get($key), $data);

        $settings->keyPrefix = md5('keyPrefix');
        $this->assertNull($settings->get($key));
        $data2 = ['data2'];
        $this->assertTrue($settings->set($key, $data2));
        $this->assertEquals($settings->get($key), $data2);

        $settings->keyPrefix = null;
        $this->assertEquals($settings->get($key), $data);
    }
}