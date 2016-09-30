<?php

namespace example\models;

use yii\base\Model;
use yii\di\Instance;
use lav45\settings\Settings;

class SettingsForm extends Model
{
    /**
     * Settings key
     */
    const SETTINGS_KEY = 'main.settings';
    /**
     * @var Settings|string|array
     */
    protected $settings;
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
     * SettingsForm constructor.
     * @param Settings|string|array $settings
     * @param array $config
     */
    public function __construct($settings = 'settings', $config = [])
    {
        parent::__construct($config);
        $this->settings = Instance::ensure($settings, Settings::className());
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->setAttributes($this->settings->get(self::SETTINGS_KEY));
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

    /**
     * @param bool $validate
     * @return bool
     */
    public function save($validate = true)
    {
        if ($validate === true && $this->validate() === false) {
            return false;
        } else {
            return $this->settings->set(self::SETTINGS_KEY, $this->getAttributes());
        }
    }
}