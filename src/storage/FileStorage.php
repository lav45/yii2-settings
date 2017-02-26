<?php
/**
 * @link https://github.com/LAV45/yii2-settings
 * @copyright Copyright (c) 2016 LAV45
 * @author Alexey Loban <lav451@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\settings\storage;

use Yii;
use yii\base\Object;
use yii\helpers\FileHelper;

/**
 * Class FileStorage
 * @package lav45\settings\storage
 */
class FileStorage extends Object implements StorageInterface
{
    public $path = '@settings';

    public $fileMode;

    public $dirMode = 0755;

    public $fileSuffix = '.bin';

    public function init()
    {
        parent::init();
        $this->path = Yii::getAlias($this->path);
        FileHelper::createDirectory($this->path, $this->dirMode, true);
    }

    /**
     * @param string $key
     * @return false|null|string
     */
    public function getValue($key)
    {
        $file = $this->getFile($key);

        $fp = @fopen($file, 'r');
        if ($fp !== false) {
            @flock($fp, LOCK_SH);
            $value = @stream_get_contents($fp);
            @flock($fp, LOCK_UN);
            @fclose($fp);
            return $value;
        }

        return false;
    }

    /**
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function setValue($key, $value)
    {
        $file = $this->getFile($key);

        if (@file_put_contents($file, $value, LOCK_EX) !== false) {
            if ($this->fileMode !== null) {
                @chmod($file, $this->fileMode);
            }
            return true;
        } else {
            $error = error_get_last();
            Yii::warning("Unable to write file '{$file}': {$error['message']}", __METHOD__);
            return false;
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public function deleteValue($key)
    {
        $file = $this->getFile($key);

        return @unlink($file);
    }

    /**
     * Returns the storage file path given the key.
     * @param string $key
     * @return string the file path
     */
    protected function getFile($key)
    {
        return $this->path . DIRECTORY_SEPARATOR . $key . $this->fileSuffix;
    }
}