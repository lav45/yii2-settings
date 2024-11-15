<?php

namespace lav45\settings\tests\storage;

use lav45\settings\storage\PhpFileStorage;

class PhpFileStorageTest extends TestCase
{
    protected function getStorage()
    {
        return new PhpFileStorage();
    }

    public function dataProviderExport()
    {
        $data = parent::dataProviderExport();

        $obj = new \StdClass();
        $obj->template = 'test';
        $data[] = [uniqid('', true), $obj];

        $value = ['key' => 'array', 1, false, null, ['a', 'b'], $obj];
        $data[] = [uniqid('', true), $value];

        return $data;
    }
}