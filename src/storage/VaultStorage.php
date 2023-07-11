<?php

namespace lav45\settings\storage;

use yii\di\Instance;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use lav45\settings\storage\vault\Client;
use lav45\settings\storage\vault\services\Sys;
use lav45\settings\storage\vault\services\KVv1;
use lav45\settings\storage\vault\services\KVv2;
use lav45\settings\storage\vault\services\KVInterface;

/**
 * Class VaultStorage
 * @package lav45\settings\storage
 */
class VaultStorage extends BaseObject implements StorageInterface
{
    /** @var string|array|Client */
    public $client = 'client';
    /** @var string|array|Sys */
    public $sys = 'sys';
    /** @var string|array|KVv1|KVv2 */
    public $kv = 'kv';

    /**
     * Initializes the application component.
     */
    public function init()
    {
        parent::init();

        $this->client = Instance::ensure($this->client, Client::class);
        $this->kv = Instance::ensure($this->kv, KVInterface::class);
    }

    /**
     * @param string $key
     * @return false|mixed|string|null
     */
    public function getValue($key)
    {
        [$key, $secret] = $this->getKeySecret($key);

        return $this->kv->get($secret)['data'][$key] ?? false;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getValues($key)
    {
        return $this->kv->list($key);
    }

    /**
     * @param string $key
     * @param string $value
     * @return bool|mixed
     */
    public function setValue($key, $value)
    {
        [$key, $secret] = $this->getKeySecret($key);

        $data = [$key => $value];

        return $this->kv->post($secret, $data);
    }

    /**
     * @param string $key
     * @return bool|mixed
     */
    public function deleteValue($key)
    {
        [$key, $secret] = $this->getKeySecret($key);

        return $this->kv->delete($secret);
    }

    /**
     * @param string $key
     * @return array
     */
    private function getKeySecret(string $key)
    {
        $data = explode('/', $key);

        return [
            array_pop($data),
            implode('/', $data),
        ];
    }
}