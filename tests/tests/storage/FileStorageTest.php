<?php

namespace lav45\settings\tests\storage;

use Yii;
use lav45\settings\storage\FileStorage;

class FileStorageTest extends TestCase
{
    protected function getStorage()
    {
        return new FileStorage([
            'fileMode' => 0644
        ]);
    }

    public function testGetError()
    {
        $storage = $this->getStorage();

        $key = md5(uniqid('', false));
        $value = 'a:1:{s:13:"template";s:1:"1";}';

        chmod($storage->path, 0444);

        $this->assertFalse($storage->setValue($key, $value));

        chmod($storage->path, $storage->dirMode);

        $message = array_pop(Yii::getLogger()->messages);

        $this->assertEquals('application', $message[2]);
        $this->assertStringEndsWith('Permission denied', $message[0]);
    }
}