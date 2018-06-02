<?php

namespace lav45\settings\tests\models;

use lav45\settings\Model;

class SettingsForm extends Model
{
    /**
     * @var string
     */
    public $title;
    /**
     * @var string[]
     */
    public $emails;
    /**
     * @var mixed
     */
    public $ignore_attribute;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'string'],
            [['emails'], 'each', 'rule' => ['email']],
        ];
    }
}