<?php

namespace lav45\settings\storage\vault\services;

use yii\di\Instance;
use yii\base\BaseObject;
use lav45\settings\storage\vault\Client;

/**
 * Class KVv2 - KV (Key Value) Secrets Engine - Version 2 (API)
 * @package lav45\settings\storage\vault\services
 */
class KVv2 extends BaseObject implements KVInterface
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
     * This path configures backend level settings that are applied to every key in the key-value store.
     * @param int $max_versions
     * @param bool $cas_required
     * @param string $delete_version_after
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#configure-the-kv-engine
     */
    public function configureEngine(int $max_versions = 0, bool $cas_required = false, string $delete_version_after = '0s')
    {
        $url = '/v1' . $this->path . '/config';

        $data = [
            'max_versions' => $max_versions,
            'cas_required' => $cas_required,
            'delete_version_after' => $delete_version_after
        ];

        return $this->client->post($url, $data);
    }

    /**
     * This path retrieves the current configuration for the secrets backend at the given path.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#read-kv-engine-configuration
     */
    public function getEngineConfiguration()
    {
        $url = '/v1' . $this->path . '/config';

        return $this->client->get($url);
    }

    /**
     * This endpoint retrieves the secret at the specified location.
     * @param string $secret
     * @param int $version
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#read-secret-version
     */
    public function getSecretVersion(string $secret, int $version = 0)
    {
        $url = '/v1' . $this->path . '/data';

        $version = $version === 0 ? '' : '?version=' . $version;

        return $this->client->get($url . $secret . $version);
    }

    /**
     * This endpoint creates a new version of a secret at the specified location.
     * @param string $path
     * @param array $data
     * @return bool|array
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#create-update-secret
     */
    public function post(string $path, array $data = [])
    {
        $url = '/v1' . $this->path . '/data' . $path;

        return $this->client->post($url, $data);
    }

    /**
     * This endpoint retrieves the secret at the specified location.
     * @param string $path
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#read-secret-version
     */
    public function get(string $path)
    {
        $url = '/v1' . $this->path . '/data' . $path;

        return $this->client->get($url);
    }

    /**
     * This endpoint issues a soft delete of the secret's latest version at the specified location.
     * @param string $path
     * @return bool|array
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#delete-latest-version-of-secret
     */
    public function delete(string $path)
    {
        $url = '/v1' . $this->path . '/data' . $path;

        return $this->client->delete($url);
    }

    /**
     * This endpoint returns a list of key names at the specified location.
     * Folders are suffixed with /. The input must be a folder; list on a file will not return a value.
     * Note that no policy-based filtering is performed on keys; do not encode sensitive information in key names.
     * The values themselves are not accessible via this command.
     * @param string $path
     * @return array
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#list-secrets
     */
    public function list(string $path)
    {
        $url = '/v1' . $this->path . '/metadata' . $path;

        return $this->client->list($url);
    }

    /**
     * This endpoint provides the ability to patch an existing secret at the specified location.
     * @param string $secret
     * @param array $data example: ['data' => ['key' => 'value', ...], 'options' => ['key' => 'value', ...]]
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#patch-secret
     */
    public function patchSecret(string $secret, array $data)
    {
        $url = '/v1' . $this->path . '/data';

        return $this->client->patch($url . $secret, $data);
    }

    /**
     * This endpoint provides the subkeys within a secret entry that exists at the requested path.
     * @param string $secret
     * @param int $version
     * @param int $depth
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#read-secret-subkeys
     */
    public function getSecretSubkeys(string $secret, int $version = 0, int $depth = 0)
    {
        $url = '/v1' . $this->path . '/subkeys';

        $data = [];
        if ($version !== 0) {
            $data['version'] = $version;
        }
        if ($depth !== 0) {
            $data['depth'] = $depth;
        }

        $params = '';
        if ($data) {
            $params = '?' . http_build_query($data, '', '?');
        }

        return $this->client->get($url . $secret . $params);
    }

    /**
     * This endpoint issues a soft delete of the specified versions of the secret.
     * @param string $secret
     * @param array $versions
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#delete-secret-versions
     */
    public function deleteSecretVersions(string $secret, array $versions)
    {
        $url = '/v1' . $this->path . '/delete';

        $data = [
            'versions' => $versions,
        ];

        return $this->client->post($url . $secret, $data);
    }

    /**
     * Undeletes the data for the provided version and path in the key-value store. This restores the data, allowing it to be returned on get requests.
     * @param string $secret
     * @param array $versions
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#undelete-secret-versions
     */
    public function restoreSecretVersions(string $secret, array $versions)
    {
        $url = '/v1' . $this->path . '/undelete';

        $data = [
            'versions' => $versions,
        ];

        return $this->client->post($url . $secret, $data);
    }

    /**
     * Permanently removes the specified version data for the provided key and version numbers from the key-value store.
     * @param string $secret
     * @param array $versions
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#destroy-secret-versions
     */
    public function destroySecretVersions(string $secret, array $versions)
    {
        $url = '/v1' . $this->path . '/destroy';

        $data = [
            'versions' => $versions,
        ];

        return $this->client->put($url . $secret, $data);
    }

    /**
     * This endpoint retrieves the metadata and versions for the secret at the specified path. Metadata is version-agnostic.
     * @param string $secret
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#read-secret-metadata
     */
    public function getSecretMetadata(string $secret)
    {
        $url = '/v1' . $this->path . '/metadata';

        return $this->client->get($url . $secret);
    }

    /**
     * This endpoint creates or updates the metadata of a secret at the specified location. It does not create a new version.
     * @param string $secret
     * @param int $max_versions
     * @param bool $cas_required
     * @param string $delete_version_after
     * @param array $custom_metadata
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#create-update-metadata
     */
    public function setSecretMetadata(string $secret, int $max_versions = 0, bool $cas_required = false, string $delete_version_after = '0s', array $custom_metadata = [])
    {
        $url = '/v1' . $this->path . '/metadata';

        $data = [
            'max_versions' => $max_versions,
            'cas_required' => $cas_required,
            'delete_version_after' => $delete_version_after
        ];

        if ($custom_metadata) {
            $data['custom_metadata'] = $custom_metadata;
        }

        return $this->client->post($url . $secret, $data);
    }

    /**
     * This endpoint patches an existing metadata entry of a secret at the specified location.
     * The calling token must have an ACL policy granting the patch capability.
     * Currently, only JSON merge patch is supported and must be specified using a Content-Type header value of application/merge-patch+json.
     * It does not create a new version.
     * @param string $secret
     * @param int $max_versions
     * @param bool $cas_required
     * @param string $delete_version_after
     * @param array $custom_metadata
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#patch-metadata
     */
    public function patchSecretMetadata(string $secret, int $max_versions = 0, bool $cas_required = false, string $delete_version_after = '0s', array $custom_metadata = [])
    {
        $url = '/v1' . $this->path . '/metadata';

        $data = [];
        if ($max_versions !== 0) {
            $data['max_versions'] = $max_versions;
        }

        if ($cas_required !== false) {
            $data['cas_required'] = $cas_required;
        }

        if ($delete_version_after !== '0s') {
            $data['delete_version_after'] = $delete_version_after;
        }

        if ($custom_metadata) {
            $data['custom_metadata'] = $custom_metadata;
        }

        $headers = [
            'Content-Type' => 'application/merge-patch+json',
        ];

        return $this->client->patch($url . $secret, $data, $headers);
    }

    /**
     * This endpoint permanently deletes the key metadata and all version data for the specified key. All version history will be removed.
     * @param string $secret
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#delete-metadata-and-all-versions
     */
    public function deleteSecretMetadata(string $secret)
    {
        $url = '/v1' . $this->path . '/metadata';

        return $this->client->delete($url . $secret);
    }
}