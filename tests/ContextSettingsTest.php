<?php

namespace lav45\settings\tests;

use Yii;
use yii\db\Query;

/**
 * Class ContextSettingsTest
 * @package tests
 */
class ContextSettingsTest extends \PHPUnit_Framework_TestCase
{
    protected function getSettings()
    {
        /** @var \lav45\settings\Settings|\lav45\settings\behaviors\ContextBehavior $object */
        $object = Yii::$app->get('settings');
        return $object;
    }

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        Yii::$app->set('settings', [
            'class' => 'lav45\settings\Settings',
            'as access' => [
                'class' => 'lav45\settings\behaviors\ContextBehavior',
            ],
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

        $rows = $this->clearStorage();
        static::assertEquals($rows, count($items) * 2);
    }
}