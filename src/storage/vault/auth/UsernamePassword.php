<?php

namespace lav45\settings\storage\vault\auth;

use lav45\settings\storage\vault\Client;

/**
 * Class UsernamePassword
 * @package lav45\settings\storage\vault\auth
 */
class UsernamePassword
{
    /** @var Client */
    private $client;

    /**
     * Create a new Sys service with an optional Client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Create a new user or update an existing user.
     * This path honors the distinction between the create and update capabilities inside ACL policies.
     * @param string $username
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/auth/userpass#create-update-user
     * @throws \yii\base\InvalidConfigException
     */
    public function setUser(string $username, array $data)
    {
        return $this->client->post('/v1/auth/userpass/users' . $username, $data);
    }

    /**
     * Reads the properties of an existing username.
     * @param string $username
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/auth/userpass#read-user
     * @throws \yii\base\InvalidConfigException
     */
    public function getUser(string $username)
    {
        return $this->client->get('/v1/auth/userpass/users' . $username);
    }

    /**
     * This endpoint deletes the user from the method.
     * @param string $username
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/auth/userpass#delete-user
     * @throws \yii\base\InvalidConfigException
     */
    public function deleteUser(string $username)
    {
        return $this->client->delete('/v1/auth/userpass/users' . $username);
    }

    /**
     * Update password for an existing user.
     * @param string $username
     * @param string $password
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/auth/userpass#update-password-on-user
     * @throws \yii\base\InvalidConfigException
     */
    public function updateUserPassword(string $username, string $password)
    {
        $data = [
            'password' => $password,
        ];

        return $this->client->post('/v1/auth/userpass/users' . $username . '/password', $data);
    }

    /**
     * Update policies for an existing user.
     * @param string $username
     * @param array|string $policies
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/auth/userpass#update-policies-on-user
     * @throws \yii\base\InvalidConfigException
     */
    public function updateUserPolicies(string $username, array|string $policies)
    {
        $data = [
            'token_policies' => $policies,
        ];

        return $this->client->post('/v1/auth/userpass/users' . $username . '/policies', $data);
    }

    /**
     * List available userpass users.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/auth/userpass#list-users
     * @throws \yii\base\InvalidConfigException
     */
    public function getUsers()
    {
        return $this->client->list('/v1/auth/userpass/users');
    }

    /**
     * Login with the username and password.
     * @param string $username
     * @param string $password
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/auth/userpass#login
     * @throws \yii\base\InvalidConfigException
     */
    public function login(string $username, string $password)
    {
        $data = [
            'password' => $password,
        ];

        return $this->client->post('/v1/auth/userpass/login' . $username, $data);
    }
}