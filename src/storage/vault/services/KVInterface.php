<?php

namespace lav45\settings\storage\vault\services;

interface KVInterface
{
    /**
     * @param string $path
     * @param array $data
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function post(string $path, array $data = []);

    /**
     * @param string $path
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function get(string $path);

    /**
     * @param string $path
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function delete(string $path);

    /**
     * @param string $path
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function list(string $path);
}