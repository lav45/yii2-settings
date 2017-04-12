<?php

namespace example\models;

use yii\base\Model;
use yii\di\Instance;
use lav45\settings\Settings;

class SettingsForm extends Model
{
    /**
     * Settings key
     * @var string
     */
    public $settingsKey = 'main.settings';
    /**
     * @var Settings|string|array
     */
    public $settings = 'settings';
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $meta_keywords;
    /**
     * @var string
     */
    public $meta_description;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->settings = Instance::ensure($this->settings, Settings::className());
        $this->setAttributes($this->settings->get($this->settingsKey));
    }

    /**
     * @return bool
     */
    public function save()
    {
        if ($this->validate() === true) {
            $attributes = $this->getAttributes($this->safeAttributes());
            return $this->settings->set($this->settingsKey, $attributes);
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'trim'],
            [['title'], 'string', 'max' => 128],

            [['meta_keywords'], 'trim'],
            [['meta_keywords'], 'string', 'max' => 128],

            [['meta_description'], 'trim'],
            [['meta_description'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Title',
            'meta_keywords' => 'Meta keywords',
            'meta_description' => 'Meta description',
        ];
    }
}