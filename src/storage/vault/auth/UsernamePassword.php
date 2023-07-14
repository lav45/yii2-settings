<?php

namespace lav45\settings\storage\vault\auth;

use yii\di\Instance;
use yii\base\BaseObject;
use lav45\settings\storage\vault\Client;

/**
 * Class UsernamePassword
 * @package lav45\settings\storage\vault\auth
 */
class UsernamePassword
{
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
     * Create a new user or update an existing user.
     * This path honors the distinction between the create and update capabilities inside ACL policies.
     * @param string $username
     * @param array $data
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/auth/userpass#create-update-user
     */
    public function setUser(string $username, array $data)
    {
        return $this->client->post('/v1/auth/userpass/users' . $username, $data);
    }

    /**
     * Reads the properties of an existing username.
     * @param string $username
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/auth/userpass#read-user
     */
    public function getUser(string $username)
    {
        return $this->client->get('/v1/auth/userpass/users' . $username);
    }

    /**
     * This endpoint deletes the user from the method.
     * @param string $username
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/auth/userpass#delete-user
     */
    public function deleteUser(string $username)
    {
        return $this->client->delete('/v1/auth/userpass/users' . $username);
    }

    /**
     * Update password for an existing user.
     * @param string $username
     * @param string $password
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/auth/userpass#update-password-on-user
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
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/auth/userpass#update-policies-on-user
     */
    public function updateUserPolicies(string $username, $policies)
    {
        $data = [
            'token_policies' => $policies,
        ];

        return $this->client->post('/v1/auth/userpass/users' . $username . '/policies', $data);
    }

    /**
     * List available userpass users.
     * @return array
     * @see https://developer.hashicorp.com/vault/api-docs/auth/userpass#list-users
     */
    public function getUsers()
    {
        return $this->client->list('/v1/auth/userpass/users');
    }

    /**
     * Login with the username and password.
     * @param string $username
     * @param string $password
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/auth/userpass#login
     */
    public function login(string $username, string $password)
    {
        $data = [
            'password' => $password,
        ];

        return $this->client->post('/v1/auth/userpass/login' . $username, $data);
    }
}