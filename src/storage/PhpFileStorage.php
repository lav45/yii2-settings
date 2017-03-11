<?php
/**
 * @link https://github.com/LAV45/yii2-settings
 * @copyright Copyright (c) 2016 LAV45
 * @author Alexey Loban <lav451@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\settings\storage;

use yii\helpers\VarDumper;

class PhpFileStorage extends FileStorage
{
    /**
     * @var string settings file suffix. Defaults to '.php'.
     */
    public $fileSuffix = '.php';

    /**
     * @param string $key
     * @return mixed
     */
    public function getValue($key)
    {
        $file = $this->getFile($key);
        if (file_exists($file)) {
            return include $file;
        }
        return false;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function setValue($key, $value)
    {
        $value = is_string($value) ? "'{$value}'" : VarDumper::export($value);
        $value = "<?php\nreturn {$value};\n";

        $result = parent::setValue($key, $value);

        if (function_exists('opcache_compile_file') && ini_get('opcache.enable')) {
            $file = $this->getFile($key);
            @opcache_compile_file($file);
        }

        return $result;
    }
}