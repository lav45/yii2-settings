<?php

namespace lav45\settings\storage\vault\services;

use lav45\settings\storage\vault\Client;

/**
 * Class KVv1 - KV (Key Value) Secrets Engine - Version 1 (API)
 * @package lav45\settings\storage\vault\services
 */
class KVv1
{
    /** @var Client */
    private $client;

    /**
     * Create a new Data service with an optional Client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $path
     * @param array $data
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function post(string $path, array $data = [])
    {
        return $this->client->post('/v1' . $path, $data);
    }

    /**
     * @param string $path
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function get(string $path)
    {
        return $this->client->get('/v1' . $path);
    }

    /**
     * @param string $path
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function delete(string $path)
    {
        return $this->client->delete('/v1' . $path);
    }

    /**
     * @param string $path
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function list(string $path)
    {
        return $this->client->list('/v1' . $path);
    }
}