<?php

namespace lav45\settings\tests;

use Yii;
use lav45\settings\Settings;
use lav45\settings\tests\models\LocalStorage;
use lav45\settings\tests\models\SettingsForm;
use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        Yii::$app->set('settings', [
            'class' => Settings::class,
            'storage' => LocalStorage::class,
        ]);
    }

    public function testLoadSaveFlushData()
    {
        $model = new SettingsForm();

        $data = [
            'title' => 'Mailing',
            'emails' => [
                'mail1@gmail.com',
                'mail2@gmail.com',
                'mail3@gmail.com',
            ],
            'ignore_attribute' => 100,
        ];

        $model->setAttributes($data, false);
        $this->assertEquals($data['title'], $model->title);
        $this->assertEquals($data['emails'], $model->emails);
        $this->assertEquals($data['ignore_attribute'], $model->ignore_attribute);

        $this->assertTrue($model->save());
        $model = SettingsForm::instance();
        $this->assertEquals($data['title'], $model->title);
        $this->assertEquals($data['emails'], $model->emails);
        $this->assertNull($model->ignore_attribute);

        $this->assertTrue($model->flush());
        $this->assertNull($model->title);
        $this->assertNull($model->emails);

        $model = SettingsForm::instance(false);
        $this->assertNull($model->title);
        $this->assertNull($model->emails);
    }
}