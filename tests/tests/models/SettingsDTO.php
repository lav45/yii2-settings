<?php

namespace lav45\settings\tests\models;

use lav45\settings\Model;

class SettingsDTO extends Model
{
    /** @var string */
    public $title;
    /** @var string[] */
    public $emails;
}