<?php

namespace lav45\settings\tests\storage;

use lav45\settings\storage\StorageInterface;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @return StorageInterface
     */
    abstract protected function getStorage();

    /**
     * Data provider for [[testStorage()]]
     * @return array test data
     */
    public function dataProviderExport()
    {
        return [
            [
                md5(uniqid()) . md5(uniqid()), // key max length 64
                'a:1:{s:13:"template";s:1:"1";}'
            ]
        ];
    }

    /**
     * @dataProvider dataProviderExport
     *
     * @param string $key
     * @param mixed $value
     */
    public function testStorage($key, $value)
    {
        $storage = $this->getStorage();

        // setValue
        static::assertTrue($storage->setValue($key, $value));

        // getValue
        static::assertEquals($storage->getValue($key), $value);

        // deleteValue
        static::assertTrue($storage->deleteValue($key));
        static::assertFalse($storage->getValue($key));
    }
}