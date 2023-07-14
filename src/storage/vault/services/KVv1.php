<?php

namespace lav45\settings\storage\vault\services;

use yii\di\Instance;
use yii\base\BaseObject;
use lav45\settings\storage\vault\Client;

/**
 * Class KVv1 - KV (Key Value) Secrets Engine - Version 1 (API)
 * @package lav45\settings\storage\vault\services
 */
class KVv1 extends BaseObject implements KVInterface
{
    /** @var string */
    public $path = '/kv';
    /** @var string|array|Client */
    public $client = 'vaultClient';

    /**
     * Initializes the application component.
     */
    public function init()
    {
        parent::init();

        $this->client = Instance::ensure($this->client, Client::class);
    }

    /**
     * @param string $path
     * @param array $data
     * @return bool|array
     */
    public function post(string $path, array $data = [])
    {
        $url = '/v1' . $this->path . $path;

        return $this->client->post($url, $data);
    }

    /**
     * @param string $path
     * @return bool|array|null|string
     */
    public function get(string $path)
    {
        $url = '/v1' . $this->path . $path;

        return $this->client->get($url);
    }

    /**
     * @param string $path
     * @return bool|array
     */
    public function delete(string $path)
    {
        $url = '/v1' . $this->path . $path;

        return $this->client->delete($url);
    }

    /**
     * @param string $path
     * @return array
     */
    public function list(string $path)
    {
        $url = '/v1' . $this->path . $path;

        return $this->client->list($url);
    }
}