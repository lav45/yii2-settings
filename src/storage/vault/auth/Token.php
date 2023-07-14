<?php

namespace lav45\settings\storage\vault\auth;

use yii\di\Instance;
use yii\base\BaseObject;
use lav45\settings\storage\vault\Client;

/**
 * Class Token
 * @package lav45\settings\storage\vault\auth
 */
class Token extends BaseObject
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
     * This endpoint lists token accessor. This requires sudo capability,and access to it should be tightly controlled as
     * the accessors can be used to revoke very large numbers of tokens and their associated leases at once.
     * @return array
     * @see https://developer.hashicorp.com/vault/api-docs/auth/token#list-accessors
     */
    public function listAccessors()
    {
        return $this->client->list('/v1/auth/token/accessors');
    }

    /**
     * Creates a new token. Certain options are only available when called by a root token.
     * If used via the /auth/token/create-orphan endpoint, a root token is not required to create an orphan token (otherwise set with the no_parent option).
     * If used with a role name in the path, the token will be created against the specified role name; this may override options set during this call.
     * @param array $data
     * @param string $roleName
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/auth/token#create-token
     */
    public function createToken(array $data, string $roleName = '')
    {
        return $this->client->post('/v1/auth/token/create' . $roleName, $data);
    }

    /**
     * Creates a new token. Certain options are only available when called by a root token.
     * If used via the /auth/token/create-orphan endpoint, a root token is not required to create an orphan token (otherwise set with the no_parent option).
     * If used with a role name in the path, the token will be created against the specified role name; this may override options set during this call.
     * @param array $data
     * @param string $roleName
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/auth/token#create-token
     */
    public function createOrphanToken(array $data, string $roleName = '')
    {
        return $this->client->post('/v1/auth/token/create-orphan' . $roleName, $data);
    }

    /**
     * Returns information about the client token.
     * @param string $token
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/auth/token#lookup-a-token
     */
    public function getToken(string $token)
    {
        $data = [
            'token' => $token,
        ];

        return $this->client->post('/v1/auth/token/lookup', $data);
    }

    /**
     * Returns information about the current client token.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/auth/token#lookup-a-token-self
     */
    public function getSelfToken()
    {
        return $this->client->get('/v1/auth/token/lookup-self');
    }

    /**
     * Returns information about the client token from the accessor.
     * @param string $accessor
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/auth/token#lookup-a-token-accessor
     */
    public function getAccessorToken(string $accessor)
    {
        $data = [
            'accessor' => $accessor,
        ];

        return $this->client->post('/v1/auth/token/lookup-accessor', $data);
    }

    /**
     * Renews a lease associated with a token. This is used to prevent the expiration of a token, and the automatic revocation of it.
     * Token renewal is possible only if there is a lease associated with it.
     * @param string $token
     * @param string $increment
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/auth/token#renew-a-token
     */
    public function renewToken(string $token, string $increment = '')
    {
        $data = [
            'token' => $token,
            'increment' => $increment,
        ];

        return $this->client->post('/v1/auth/token/renew', $data);
    }

    /**
     * Renews a lease associated with the calling token. This is used to prevent the expiration of a token, and the automatic revocation of it.
     * Token renewal is possible only if there is a lease associated with it.
     * @param string $increment
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/auth/token#renew-a-token-self
     */
    public function renewSelfToken(string $increment)
    {
        $data = [
            'increment' => $increment,
        ];

        return $this->client->post('/v1/auth/token/renew-self', $data);
    }

    /**
     * Renews a lease associated with a token using its accessor. This is used to prevent the expiration of a token,
     * and the automatic revocation of it. Token renewal is possible only if there is a lease associated with it.
     * @param string $accessor
     * @param string $increment
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/auth/token#renew-a-token-accessor
     */
    public function renewAccessorToken(string $accessor, string $increment = '')
    {
        $data = [
            'accessor' => $accessor,
            'increment' => $increment,
        ];

        return $this->client->post('/v1/auth/token/renew-accessor', $data);
    }

    /**
     * Revokes a token and all child tokens. When the token is revoked, all dynamic secrets generated with it are also revoked.
     * @param string $token
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/auth/token#revoke-a-token
     */
    public function revokeToken(string $token)
    {
        $data = [
            'token' => $token,
        ];

        return $this->client->post('/v1/auth/token/revoke', $data);
    }

    /**
     * Revokes the token used to call it and all child tokens.
     * When the token is revoked, all dynamic secrets generated with it are also revoked.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/auth/token#revoke-a-token-self
     */
    public function revokeSelfToken()
    {
        return $this->client->post('/v1/auth/token/revoke-self');
    }

    /**
     * Revoke the token associated with the accessor and all the child tokens.
     * This is meant for purposes where there is no access to token ID but there is need to revoke a token and its children.
     * @param string $accessor
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/auth/token#revoke-a-token-accessor
     */
    public function revokeAccessorToken(string $accessor)
    {
        $data = [
            'accessor' => $accessor,
        ];

        return $this->client->post('/v1/auth/token/revoke-accessor', $data);
    }

    /**
     * Revokes a token but not its child tokens. When the token is revoked, all secrets generated with it are also revoked.
     * All child tokens are orphaned, but can be revoked sub-sequently using /auth/token/revoke/.
     * This is a root-protected endpoint.
     * @param string $token
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/auth/token#revoke-token-and-orphan-children
     */
    public function revokeOrphanToken(string $token)
    {
        $data = [
            'token' => $token,
        ];

        return $this->client->post('/v1/auth/token/revoke-orphan', $data);
    }

    /**
     * Fetches the named role configuration.
     * @param string $name
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/auth/token#read-token-role
     */
    public function getRole(string $name)
    {
        return $this->client->get('/v1/auth/token/roles' . $name);
    }

    /**
     * List available token roles.
     * @return array
     * @see https://developer.hashicorp.com/vault/api-docs/auth/token#list-token-roles
     */
    public function getRoles()
    {
        return $this->client->list('/v1/auth/token/roles');
    }

    /**
     * Creates (or replaces) the named role. Roles enforce specific behavior when creating tokens that allow token functionality
     * that is otherwise not available or would require sudo/root privileges to access. Role parameters, when set,
     * override any provided options to the create endpoints. The role name is also included in the token path,
     * allowing all tokens created against a role to be revoked using the /sys/leases/revoke-prefix endpoint.
     * @param string $name
     * @param array $data
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/auth/token#create-update-token-role
     */
    public function setRole(string $name, array $data)
    {
        return $this->client->post('/v1/auth/token/roles' . $name, $data);
    }

    /**
     * This endpoint deletes the named token role.
     * @param string $name
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/auth/token#delete-token-role
     */
    public function deleteRole(string $name)
    {
        return $this->client->delete('/v1/auth/token/roles' . $name);
    }

    /**
     * Performs some maintenance tasks to clean up invalid entries that may remain in the token store.
     * On Enterprise, Tidy will only impact the tokens in the specified namespace, or the root namespace if unspecified.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/auth/token#tidy-tokens
     */
    public function getTidyTokens()
    {
        return $this->client->post('/v1/auth/token/tidy');
    }
}