<?php
/**
 * @link https://github.com/LAV45/yii2-settings
 * @copyright Copyright (c) 2016 LAV45
 * @author Alexey Loban <lav451@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace lav45\settings\storage;

use yii\helpers\VarDumper;

/**
 * Class PhpFileStorage
 * @package lav45\settings\storage
 */
class PhpFileStorage extends FileStorage
{
    /**
     * @var string settings file suffix.
     */
    public $fileSuffix = '.php';

    /**
     * @param string $key
     * @return mixed
     */
    public function getValue($key)
    {
        $fileName = $this->getFile($key);
        if (file_exists($fileName)) {
            return include $fileName;
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

        if ($result === true) {
            $fileName = $this->getFile($key);
            $this->forceScriptCache($fileName);
        }

        return $result;
    }

    /**
     * Forcibly caches data of this file in OPCache or APC.
     * @param string $fileName file name.
     * @since 1.0.4
     */
    protected function forceScriptCache($fileName)
    {
        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($fileName, true); // @codeCoverageIgnore
        }
        if (function_exists('opcache_compile_file')) {
            opcache_compile_file($fileName); // @codeCoverageIgnore
        }
    }
}
