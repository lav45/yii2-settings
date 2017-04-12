<?php

namespace console\controllers;

use yii\console\Controller;
use yii\helpers\Console;

/**
 * Class ConfigController
 * @package console\controllers
 *
 * ~$ php yii config/set db_name site-db
 *
 * ~$ php yii config
 *     db_name: site-db
 *
 * ~$ php yii config/get db_name
 * site-db
 *
 * ~$ php yii config/delete db_name
 */
class ConfigController extends Controller
{
    /**
     * Display all keys
     */
    public function actionIndex()
    {
        foreach ($this->get() as $key => $value) {
            $this->stdout("  {$key}", Console::FG_YELLOW);
            if (is_string($value)) {
                $this->stdout(": {$value}", Console::FG_GREEN);
            }
            $this->stdout("\n");
        }
        $this->stdout("\n");
    }

    /**
     * Show key value. Params: {key}
     * @param string $key
     */
    public function actionGet($key)
    {
        $data = $this->get();
        if (isset($data[$key])) {
            echo $data[$key] . "\n";
        }
    }

    /**
     * Set value by key. Params: {key} {value}
     * @param string $key
     * @param $value
     */
    public function actionSet($key, $value)
    {
        $data = $this->get();
        $data[$key] = $value;
        $this->set($data);
    }

    /**
     * Delete key. Params: {key}
     * @param string $key
     */
    public function actionDelete($key)
    {
        $data = $this->get();
        unset($data[$key]);
        $this->set($data);
    }

    private function get()
    {
        return settings()->get(APP_CONFIG, []);
    }

    private function set($values)
    {
        settings()->set(APP_CONFIG, $values);
    }
}