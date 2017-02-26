<?php

namespace lav45\settings\tests\storage;

use lav45\settings\storage\FileStorage;

class FileStorageTest extends TestCase
{
    protected function getStorage()
    {
        return new FileStorage([
            'path' => '@runtime/settings',
            'fileMode' => 0644
        ]);
    }
}