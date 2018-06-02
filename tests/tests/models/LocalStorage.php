<?php

namespace lav45\settings\tests\models;

use lav45\settings\storage\StorageInterface;

class LocalStorage implements StorageInterface
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

    public function count()
    {
        return count($this->data);
    }

    public function flush()
    {
        $this->data = [];
    }
}