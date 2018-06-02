<?php

namespace lav45\settings\tests\storage;

use lav45\settings\storage\DbStorage;

class DbStorageTest extends TestCase
{
    protected function getStorage()
    {
        return new DbStorage();
    }
}