<?php
/**
 * @link https://github.com/lav45/yii2-settings
 * @copyright Copyright (c) 2016 LAV45
 * @author Aleksey Loban <lav451@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\settings\storage;

use Yii;
use yii\base\BaseObject;
use yii\helpers\FileHelper;

/**
 * Class FileStorage
 * @package lav45\settings\storage
 */
class FileStorage extends BaseObject implements StorageInterface
{
    /**
     * @var string the directory to store settings files. You may use path alias here.
     * If not set, it will use the "settings" subdirectory under the application runtime path.
     */
    public $path = '@runtime/settings';
    /**
     * @var int the permission to be set for newly created cache files.
     * This value will be used by PHP chmod() function. No umask will be applied.
     * If not set, the permission will be determined by the current environment.
     */
    public $fileMode;
    /**
     * @var int the permission to be set for newly created directories.
     * This value will be used by PHP chmod() function. No umask will be applied.
     * Defaults to 0775, meaning the directory is read-writable by owner, but read-only for other users.
     */
    public $dirMode = 0755;
    /**
     * @var string settings file suffix. Defaults to '.bin'.
     */
    public $fileSuffix = '.bin';

    /**
     * Initializes this component by ensuring the existence of the settings path.
     */
    public function init()
    {
        parent::init();
        $this->path = Yii::getAlias($this->path);
        FileHelper::createDirectory($this->path, $this->dirMode);
    }

    /**
     * @return false|null|string
     */
    public function getValue(string $key)
    {
        $file = $this->getFile($key);

        $fp = @fopen($file, 'rb');
        if ($fp !== false) {
            @flock($fp, LOCK_SH);
            $value = @stream_get_contents($fp);
            @flock($fp, LOCK_UN);
            @fclose($fp);
            return $value;
        }

        return false;
    }

    public function setValue(string $key, $value): bool
    {
        $file = $this->getFile($key);

        if (@file_put_contents($file, $value, LOCK_EX) !== false) {
            if ($this->fileMode !== null) {
                @chmod($file, $this->fileMode);
            }
            return true;
        }
        $error = error_get_last();
        Yii::error("Unable to write file '{$file}': {$error['message']}");
        return false;
    }

    public function deleteValue(string $key): bool
    {
        return @unlink($this->getFile($key));
    }

    /**
     * Returns the storage file path given the key.
     */
    protected function getFile(string $key): string
    {
        return $this->path . DIRECTORY_SEPARATOR . $key . $this->fileSuffix;
    }
}