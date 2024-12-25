<?php
/**
 * @link https://github.com/lav45/yii2-settings
 * @copyright Copyright (c) 2016 LAV45
 * @author Aleksey Loban <lav451@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\settings\behaviors;

use yii\base\Behavior;

/**
 * Class ContextBehavior
 * @package lav45\settings\behaviors
 *
 * @property \lav45\settings\Settings $owner
 */
class ContextBehavior extends Behavior
{
    /**
     * @var array
     */
    private $_models = [];

    public function context($data)
    {
        $key = md5(serialize($data));
        if (!isset($this->_models[$key])) {
            $settings = clone $this->owner;
            $settings->keyPrefix = $key;
            $this->_models[$key] = $settings;
        }
        return $this->_models[$key];
    }
}