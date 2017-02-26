<?php
/**
 * Created by PhpStorm.
 * User: lav45
 * Date: 26.02.17
 * Time: 0:23
 */

namespace lav45\settings\tests;

use lav45\settings\storage\StorageInterface;

class FakeStorage implements StorageInterface
{
    private $data = [];

    public function getValue($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : false;
    }

    public function setValue($key, $value)
    {
        $this->data[$key] = $value;
        return true;
    }

    public function deleteValue($key)
    {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
            return true;
        }
        return false;
    }

    public function flushValues()
    {
        $count = count($this->data);
        $this->data = [];
        return $count;
    }
}