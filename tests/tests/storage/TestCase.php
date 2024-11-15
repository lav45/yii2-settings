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
                md5(uniqid('', true)) . md5(uniqid('', true)), // key max length 64
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
        $this->assertTrue($storage->setValue($key, $value));

        // getValue
        $this->assertEquals($storage->getValue($key), $value);

        // deleteValue
        $this->assertTrue($storage->deleteValue($key));
        $this->assertFalse($storage->getValue($key));
    }
}