<?php

namespace lav45\settings\storage\vault;

use Yii;
use yii\base\BaseObject;
use yii\httpclient\Client as HttpClient;
use lav45\settings\storage\vault\dto\ErrorDTO;

/**
 * Class Client
 * @package lav45\settings\storage\vault
 */
class Client extends BaseObject
{
    /** @var string */
    public $url = 'https://127.0.0.1:8200';
    /** @var string */
    public $token;
    /** @var HttpClient */
    private $http;

    public function init()
    {
        parent::init();

        $this->http = new HttpClient([
            'baseUrl' => $this->url,
            'requestConfig' => [
                'format' => HttpClient::FORMAT_JSON
            ],
            'responseConfig' => [
                'format' => HttpClient::FORMAT_JSON
            ],
        ]);
    }

    /**
     * Example returned data:
     *
     * Array
     * (
     *      [request_id] => 6d406c62-184d-a7b1-e20a-17483fcd34fc
     *      [lease_id] =>
     *      [renewable] =>
     *      [lease_duration] => 604800
     *      [data] => Array
     *      (
     *          [key1] => value123
     *          [key2] => value1234
     *          [key3] => value123456
     *      )
     *      [wrap_info] =>
     *      [warnings] =>
     *      [auth] =>
     * )
     *
     * In data array stored keys of secret
     * @param string $url
     * @param array $data
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function get(string $url, array $data = [])
    {
        return $this->request('GET', $url, $data);
    }

    /**
     * @param string $url
     * @param array $headers
     * @param array $data
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function post(string $url, array $data = [], array $headers = [])
    {
        return $this->request('POST', $url, $data, $headers);
    }

    /**
     * @param string $url
     * @param array $data
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function put(string $url, array $data = [])
    {
        return $this->request('PUT', $url, $data);
    }

    /**
     * @param string $url
     * @param array $data
     * @param array $headers
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function patch(string $url, array $data = [], array $headers = [])
    {
        return $this->request('PATCH', $url, $data, $headers);
    }

    /**
     * @param string $url
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function delete(string $url)
    {
        return $this->request('DELETE', $url);
    }

    /**
     * @param string $url
     * @param array $headers
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function list(string $url, array $headers = [])
    {
        return $this->request('LIST', $url, [], $headers);
    }

    /**
     * @param string $method
     * @param string|array $url
     * @param array $headers
     * @param array $data
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    private function request(string $method, $url, array $data = [], array $headers = [])
    {
        $request = $this->http->createRequest()
            ->setMethod($method)
            ->setUrl($url);

        $defaultHeaders = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'X-Vault-Token' => $this->token,
        ];

        if (empty($headers) === false) {
            $headers = array_merge($headers, $defaultHeaders);
        } else {
            $headers = $defaultHeaders;
        }

        $request->setHeaders($headers);

        if (empty($data) === false) {
            $request->setData($data);
        }

        try {
            $response = $request->send();
        } catch (\Throwable $exception) {
            $dto = $this->createError($exception, $exception->getCode(), $exception->getMessage(), $url);
            Yii::info($dto->toArray(), __METHOD__);
            return $dto;
        }

        if (400 <= $response->getStatusCode()) {
            $message = sprintf('Something went wrong when calling Hashicorp Vault (%s - %s).', $response->getStatusCode(), $this->getMessage($response->getContent()));

            $dto = $this->createError($response, $response->getCode(), $message, $request->getFullUrl());
            Yii::info($dto->toArray(), __METHOD__);
            return $dto;
        }

        return $response->getData();
    }

    /**
     * @param string $value
     * @return mixed
     */
    private function getMessage(string $value)
    {
        $data = json_decode($value);
        if (empty($data->errors)) {
            return 'Unknown error.';
        }

        return $data->errors[0];
    }

    /**
     * @param object $object
     * @param string $code
     * @param array|string $message
     * @param array|string $url
     * @return ErrorDTO
     */
    private function createError($object, string $code, $message, $url)
    {
        $dto = new ErrorDTO();
        $dto->type = get_class($object);
        $dto->code = $code;
        $dto->message = $message;
        $dto->request_url = $url;

        if ($object instanceof \Throwable) {
            $dto->trace = $object->getTrace();
        }

        return $dto;
    }
}