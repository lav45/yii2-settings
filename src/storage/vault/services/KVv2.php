<?php

namespace lav45\settings\storage\vault\services;

use lav45\settings\storage\vault\Client;

/**
 * Class KVv2 - KV (Key Value) Secrets Engine - Version 2 (API)
 * @package lav45\settings\storage\vault\services
 */
class KVv2
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
     * This path configures backend level settings that are applied to every key in the key-value store.
     * @param string $path
     * @param int $max_versions
     * @param bool $cas_required
     * @param string $delete_version_after
     * @return false|mixed
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#configure-the-kv-engine
     * @throws \yii\base\InvalidConfigException
     */
    public function configureEngine(string $path, int $max_versions = 0, bool $cas_required = false, string $delete_version_after = '0s')
    {
        $data = [
            'max_versions' => $max_versions,
            'cas_required' => $cas_required,
            'delete_version_after' => $delete_version_after
        ];

        return $this->client->post('/v1' . $path . '/config', $data);
    }

    /**
     * This path retrieves the current configuration for the secrets backend at the given path.
     * @param string $path
     * @return false|mixed
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#read-kv-engine-configuration
     * @throws \yii\base\InvalidConfigException
     */
    public function getEngineConfiguration(string $path)
    {
        return $this->client->get('/v1' . $path . '/config');
    }

    /**
     * This endpoint retrieves the secret at the specified location.
     * @param string $path
     * @param string $secret
     * @param int $version
     * @return false|mixed
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#read-secret-version
     * @throws \yii\base\InvalidConfigException
     */
    public function getSecretVersion(string $path, string $secret, int $version = 0)
    {
        $version = $version === 0 ? '' : '?version=' . $version;

        return $this->client->get('/v1' . $path . '/data' . $secret . $version);
    }

    /**
     * This endpoint creates a new version of a secret at the specified location.
     * @param string $path
     * @param string $secret
     * @param array $data
     * @param array $options
     * @return false|mixed
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#create-update-secret
     * @throws \yii\base\InvalidConfigException
     */
    public function setSecret(string $path, string $secret, array $data, array $options = [])
    {
        $result = [
            'data' => $data,
        ];

        if (empty($options) === false) {
            $result['options'] = $options;
        }

        return $this->client->post('/v1' . $path . '/data' . $secret, $result);
    }

    /**
     * This endpoint provides the ability to patch an existing secret at the specified location.
     * @param string $path
     * @param string $secret
     * @param array $data
     * @param array $options
     * @return false|mixed
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#patch-secret
     * @throws \yii\base\InvalidConfigException
     */
    public function patchSecret(string $path, string $secret, array $data, array $options = [])
    {
        $result = [
            'data' => $data,
        ];

        if (empty($options) === false) {
            $result['options'] = $options;
        }

        return $this->client->patch('/v1' . $path . '/data' . $secret, $result);
    }

    /**
     * This endpoint provides the subkeys within a secret entry that exists at the requested path.
     * @param string $path
     * @param string $secret
     * @param int $version
     * @param int $depth
     * @return false|mixed
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#read-secret-subkeys
     * @throws \yii\base\InvalidConfigException
     */
    public function getSecretSubkeys(string $path, string $secret, int $version = 0, int $depth = 0)
    {
        if ($version === 0) {
            $version = '';
            $depth = $depth === 0 ? '' : '?depth=' . $depth;
        } else {
            $version = '?version=' . $version;
            $depth = $depth === 0 ? '' : '&depth=' . $depth;
        }

        return $this->client->get('/v1' . $path . '/subkeys' . $secret . $version . $depth);
    }

    /**
     * This endpoint issues a soft delete of the secret's latest version at the specified location.
     * @param string $path
     * @param string $secret
     * @return false|mixed
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#delete-latest-version-of-secret
     * @throws \yii\base\InvalidConfigException
     */
    public function deleteSecretLatestVersion(string $path, string $secret)
    {
        return $this->client->delete('/v1' . $path . '/data' . $secret);
    }

    /**
     * This endpoint issues a soft delete of the specified versions of the secret.
     * @param string $path
     * @param string $secret
     * @return false|mixed
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#delete-secret-versions
     * @throws \yii\base\InvalidConfigException
     */
    public function deleteSecretVersions(string $path, string $secret, array $versions)
    {
        $data = [
            'versions' => $versions,
        ];

        return $this->client->post('/v1' . $path . '/delete' . $secret, $data);
    }

    /**
     * Undeletes the data for the provided version and path in the key-value store. This restores the data, allowing it to be returned on get requests.
     * @param string $path
     * @param string $secret
     * @param array $versions
     * @return false|mixed
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#undelete-secret-versions
     * @throws \yii\base\InvalidConfigException
     */
    public function restoreSecretVersions(string $path, string $secret, array $versions)
    {
        $data = [
            'versions' => $versions,
        ];

        return $this->client->post('/v1' . $path . '/undelete' . $secret, $data);
    }

    /**
     * Permanently removes the specified version data for the provided key and version numbers from the key-value store.
     * @param string $path
     * @param string $secret
     * @param array $versions
     * @return false|mixed
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#destroy-secret-versions
     * @throws \yii\base\InvalidConfigException
     */
    public function destroySecretVersions(string $path, string $secret, array $versions)
    {
        $data = [
            'versions' => $versions,
        ];

        return $this->client->put('/v1' . $path . '/destroy' . $secret, $data);
    }

    /**
     * This endpoint returns a list of key names at the specified location.
     * Folders are suffixed with /. The input must be a folder; list on a file will not return a value.
     * Note that no policy-based filtering is performed on keys; do not encode sensitive information in key names.
     * The values themselves are not accessible via this command.
     * @param string $path
     * @param string $secret
     * @return false|mixed
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#list-secrets
     * @throws \yii\base\InvalidConfigException
     */
    public function getSecrets(string $path, string $secret)
    {
        return $this->client->list('/v1' . $path . '/metadata' . $secret);
    }

    /**
     * This endpoint retrieves the metadata and versions for the secret at the specified path. Metadata is version-agnostic.
     * @param string $path
     * @param string $secret
     * @return false|mixed
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#read-secret-metadata
     * @throws \yii\base\InvalidConfigException
     */
    public function getSecretMetadata(string $path, string $secret)
    {
        return $this->client->get('/v1' . $path . '/metadata' . $secret);
    }

    /**
     * This endpoint creates or updates the metadata of a secret at the specified location. It does not create a new version.
     * @param string $path
     * @param string $secret
     * @param int $max_versions
     * @param bool $cas_required
     * @param string $delete_version_after
     * @param array $custom_metadata
     * @return false|mixed
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#create-update-metadata
     * @throws \yii\base\InvalidConfigException
     */
    public function setSecretMetadata(string $path, string $secret, int $max_versions = 0, bool $cas_required = false, string $delete_version_after = '0s', array $custom_metadata = [])
    {
        $data = [
            'max_versions' => $max_versions,
            'cas_required' => $cas_required,
            'delete_version_after' => $delete_version_after
        ];

        if (empty($custom_metadata) === false) {
            $data['custom_metadata'] = $custom_metadata;
        }

        return $this->client->post('/v1' . $path . '/metadata' . $secret, $data);
    }

    /**
     * This endpoint patches an existing metadata entry of a secret at the specified location.
     * The calling token must have an ACL policy granting the patch capability.
     * Currently, only JSON merge patch is supported and must be specified using a Content-Type header value of application/merge-patch+json.
     * It does not create a new version.
     * @param string $path
     * @param string $secret
     * @param int $max_versions
     * @param bool $cas_required
     * @param string $delete_version_after
     * @param array $custom_metadata
     * @return false|mixed
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#patch-metadata
     * @throws \yii\base\InvalidConfigException
     */
    public function patchSecretMetadata(string $path, string $secret, int $max_versions = 0, bool $cas_required = false, string $delete_version_after = '0s', array $custom_metadata = [])
    {
        if ($max_versions !== 0) {
            $data['max_versions'] = $max_versions;
        }

        if ($cas_required !== false) {
            $data['cas_required'] = $cas_required;
        }

        if ($delete_version_after !== '0s') {
            $data['delete_version_after'] = $delete_version_after;
        }

        if (empty($custom_metadata) === false) {
            $data['custom_metadata'] = $custom_metadata;
        }

        $headers = [
            'Content-Type' => 'application/merge-patch+json',
        ];

        return $this->client->patch('/v1' . $path . '/metadata' . $secret, $data, $headers);
    }

    /**
     * This endpoint permanently deletes the key metadata and all version data for the specified key. All version history will be removed.
     * @param string $path
     * @param string $secret
     * @return false|mixed
     * @see https://developer.hashicorp.com/vault/api-docs/secret/kv/kv-v2#delete-metadata-and-all-versions
     * @throws \yii\base\InvalidConfigException
     */
    public function deleteSecretMetadata(string $path, string $secret)
    {
        return $this->client->delete('/v1' . $path . '/metadata' . $secret);
    }
}