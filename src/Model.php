<?php
/**
 * @link https://github.com/lav45/yii2-settings
 * @copyright Copyright (c) 2016 LAV45
 * @author Alexey Loban <lav451@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\settings;

use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class Model represents settings model
 * @package lav45\settings
 */
class Model extends \yii\base\Model
{
    /**
     * @var Settings|string|array
     */
    private $settings = 'settings';

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setAttributes($this->getData(), false);
    }

    /**
     * @return Settings
     * @throws \yii\base\InvalidConfigException
     */
    public function getSettings()
    {
        if ($this->settings instanceof Settings === false) {
            $this->settings = Instance::ensure($this->settings, Settings::class);
        }
        return $this->settings;
    }

    /**
     * @param Settings|string|array $data
     */
    public function setSettings($data)
    {
        $this->settings = $data;
    }

    /**
     * @param bool $runValidation
     * @return bool
     */
    public function save($runValidation = true)
    {
        if ($runValidation === false || $this->validate()) {
            return $this->setData($this->getSaveAttributes($runValidation));
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
     * List of attributes to save
     * @param bool $safeOnly
     * @return string[]
     * @since 1.2.2
     */
    protected function getSaveAttributeList($safeOnly = false)
    {
        return $safeOnly ? $this->safeAttributes() : $this->attributes();
    }

    /**
     * @param bool $safeOnly
     * @return array
     */
    protected function getSaveAttributes($safeOnly = false)
    {
        $data = $this->getAttributes($this->getSaveAttributeList($safeOnly));
        return array_filter($data, static function ($val) {
            return $val !== '' || $val !== [] || $val !== null;
        });
    }

    /**
     * @return array
     */
    protected function getData()
    {
        return $this->getSettings()->get($this->getSettingsKey(), []);
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function setData($data)
    {
        return $this->getSettings()->set($this->getSettingsKey(), ArrayHelper::toArray($data));
    }

    /**
     * @return bool
     */
    public function flush()
    {
        $this->setAttributes(array_fill_keys($this->getSaveAttributeList(), null));
        return $this->getSettings()->delete($this->getSettingsKey());
    }
}
