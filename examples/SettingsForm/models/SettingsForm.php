<?php

namespace example\models;

use lav45\settings\Model;

class SettingsForm extends Model
{
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