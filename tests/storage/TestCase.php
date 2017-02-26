<?php

namespace lav45\settings\tests\storage;

use lav45\settings\storage\StorageInterface;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @return StorageInterface
     */
    abstract protected function getStorage();

    public function testStorage()
    {
        $storage = $this->getStorage();

        $key = md5(uniqid()) . md5(uniqid()); // key max length 64
        $value = 'a:1:{s:13:"template";s:1:"1";}';

        // setValue
        static::assertTrue($storage->setValue($key, $value));

        // getValue
        static::assertEquals($storage->getValue($key), $value);

        // deleteValue
        static::assertTrue($storage->deleteValue($key));
        static::assertFalse($storage->getValue($key));
    }
}