<?php

namespace lav45\settings\tests\models;

use lav45\settings\storage\StorageInterface;

class LocalStorage implements StorageInterface
{
    private $data = [];

    public function getValue(string $key)
    {
        return array_key_exists($key, $this->data) ? $this->data[$key] : false;
    }

    public function setValue(string $key, $value): bool
    {
        $this->data[$key] = $value;
        return true;
    }

    public function deleteValue(string $key): bool
    {
        if (array_key_exists($key, $this->data)) {
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