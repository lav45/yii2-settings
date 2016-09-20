<?php

namespace tests;

use Yii;
use yii\db\Query;
use yii\helpers\Json;

/**
 * Class SettingsTest
 * @package tests
 */
class SettingsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \lav45\settings\Settings
     */
    protected function getSettings()
    {
        return Yii::$app->get('settings');
    }

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        Yii::$app->set('settings', [
            'class' => 'lav45\settings\Settings'
        ]);
    }

    protected function setUp()
    {
        parent::setUp();
        $this->clearStorage();
    }

    protected function clearStorage()
    {
        /** @var \lav45\settings\storage\DbStorage $storage */
        $storage = $this->getSettings()->storage;
        return (new Query())
            ->createCommand()
            ->delete($storage->tableName)
            ->execute();
    }

    public function testGetNotExistKey()
    {
        $settings = $this->getSettings();
        static::assertNull($settings->get('key'));
        static::assertEquals($settings->get('key', []), []);
    }

    public function testSetData()
    {
        $items = [
            new \stdClass(),
            ['data'],
            123,
            123.5,
            'string',
            true,
            false,
            null,
            '',
            0
        ];

        $settings = $this->getSettings();

        foreach ($items as $data) {
            static::assertTrue($settings->set('key', $data));
            static::assertEquals($settings->get('key'), $data);
        }

        static::assertTrue($settings->delete('key'));
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

        $encodeData = $this->getOriginalData($key);
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

    public function testDisabledSerializer()
    {
        $settings = $this->getSettings();
        $settings->serializer = false;

        $key = 'key';
        $data = base64_encode(json_encode(['data']));

        static::assertTrue($settings->set($key, $data));
        $encodeData = $this->getOriginalData($key);
        static::assertEquals($settings->get($key), $encodeData);
    }

    protected function getOriginalData($key)
    {
        /** @var \lav45\settings\storage\DbStorage $storage */
        $storage = $this->getSettings()->storage;
        return (new Query())
            ->select(['data'])
            ->from($storage->tableName)
            ->where(['id' => $key])
            ->limit(1)
            ->createCommand()
            ->queryScalar();
    }
}