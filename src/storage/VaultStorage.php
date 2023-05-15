<?php

namespace lav45\settings\storage;

use lav45\settings\storage\vault\auth\Token;
use lav45\settings\storage\vault\auth\UsernamePassword;
use lav45\settings\storage\vault\services\Data;
use lav45\settings\storage\vault\services\KVv1;
use lav45\settings\storage\vault\services\Sys;
use yii\base\BaseObject;
use yii\di\Instance;
use lav45\settings\storage\vault\Client;
use yii\base\InvalidConfigException;

/**
 * Class VaultStorage
 * @package lav45\settings\storage
 */
class VaultStorage extends BaseObject implements StorageInterface
{
    /** @var string|array|Client */
    public $client = 'client';
    /** @var Sys */
    private $sys;
    /** @var KVv1|KVv2 */
    private $data;
    /** @var Token */
    private $authToken;
    /** @var UsernamePassword */
    private $authUsernamePassword;

    /**
     * Initializes the application component.
     */
    public function init()
    {
        parent::init();

        if (empty($this->token)) {
            throw new InvalidConfigException('Invalid configuration. Token is required!', 400);
        }

        $this->client = Instance::ensure($this->client, Client::class);
    }

    /**
     * @return Sys
     */
    public function getSys()
    {
        return $this->sys ??= new Sys($this->client);
    }

    /**
     * @return KVv1|KVv2
     */
    public function getData($version = 'v1')
    {
        if ($this->data !== null) {
            return $this->data;
        }

        return $this->data = $version === 'v1' ? new KVv1($this->client) : new KVv2($this->client);
    }

    /**
     * @return Token
     */
    public function getAuthToken()
    {
        return $this->authToken ??= new Token($this->client);
    }

    /**
     * @return UsernamePassword
     */
    public function getAuthUsernamePassword()
    {
        return $this->authUsernamePassword ??= new UsernamePassword($this->client);
    }

    /**
     * @param string $key
     * @return false|mixed|string|null
     */
    public function getValue($key)
    {
        [$key, $secret] = $this->getKeySecret($key);

        return $this->getData()->get($secret)['data'][$key];
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getValues($key)
    {
        return $this->getData()->list($key);
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

        return $this->getData()->post($secret, $data);
    }

    /**
     * @param string $key
     * @return bool|mixed
     */
    public function deleteValue($key)
    {
        [$key, $secret] = $this->getKeySecret($key);

        return $this->getData()->delete($secret);
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