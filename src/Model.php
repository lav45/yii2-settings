<?php
/**
 * @link https://github.com/LAV45/yii2-settings
 * @copyright Copyright (c) 2016 LAV45
 * @author Alexey Loban <lav451@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\settings;

use yii\di\Instance;

/**
 * Class Model represents settings model
 * @package lav45\settings
 */
class Model extends \yii\base\Model
{
    /**
     * @var Settings|string|array
     */
    public $settings = 'settings';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->settings = Instance::ensure($this->settings, Settings::class);
        $this->setAttributes($this->getData());
    }

    /**
     * @param bool $runValidation
     * @return bool
     */
    public function save($runValidation = true)
    {
        if ($runValidation === false || $this->validate()) {
            return $this->setData($this->getSaveAttributes());
        }
        return false;
    }

    /**
     * @return array|string|integer
     */
    protected function getSettingsKey()
    {
        return [static::class];
    }

    /**
     * @return array
     */
    protected function getSaveAttributes()
    {
        $data = $this->getAttributes($this->safeAttributes());
        return array_filter($data);
    }

    /**
     * @return array
     */
    protected function getData()
    {
        return $this->settings->get($this->getSettingsKey(), []);
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function setData($data)
    {
        return $this->settings->set($this->getSettingsKey(), $data);
    }

    /**
     * @return bool
     */
    public function flush()
    {
        $this->setAttributes(array_fill_keys($this->safeAttributes(), null));
        return $this->settings->delete($this->getSettingsKey());
    }
}
