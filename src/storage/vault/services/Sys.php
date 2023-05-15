<?php

namespace lav45\settings\storage\vault\services;

use lav45\settings\storage\vault\Client;

/**
 * Class Sys
 * @package lav45\settings\storage\vault\services
 */
class Sys
{
    public const METRIC_FORMAT_PROMETHEUS = 'prometheus';
    public const METRIC_FORMAT_JSON = 'json';
    public const LOG_LEVEL_INFO = 'info';
    public const LOG_LEVEL_DEBUG = 'debug';
    public const LOG_FORMAT_STANDARD = 'standard';
    public const LOG_FORMAT_JSON = 'json';

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
     * Endpoint is used to list audit devices
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/audit#list-enabled-audit-devices
     * @throws \yii\base\InvalidConfigException
     */
    public function getEnabledAuditDevices()
    {
        return $this->client->get('/v1/sys/audit');
    }

    /**
     * Endpoint is used to enable audit devices
     * @param string $path
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/audit#enable-audit-device
     * @throws \yii\base\InvalidConfigException
     */
    public function enableAuditDevice(string $path, array $data)
    {
        return $this->client->post('/v1/sys/audit' . $path, $data);
    }

    /**
     * Endpoint is used to disable audit devices
     * @param string $path
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/audit#disable-audit-device
     * @throws \yii\base\InvalidConfigException
     */
    public function disableAuditDevice(string $path)
    {
        return $this->client->delete('/v1/sys/audit' . $path);
    }

    /**
     * Endpoint is used to calculate the hash of the data used by an audit device's hash function and salt.
     * @param string $path
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/audit-hash#calculate-hash
     * @throws \yii\base\InvalidConfigException
     */
    public function calculateHash(string $path, array $data)
    {
        return $this->client->post('/v1/sys/audit-hash' . $path, $data);
    }

    /**
     * This endpoint lists all enabled auth methods.
     * @param string $path
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/auth#list-auth-methods
     * @see https://developer.hashicorp.com/vault/api-docs/system/auth#read-auth-method-configuration
     * @throws \yii\base\InvalidConfigException
     */
    public function getAuthMethods(string $path = '')
    {
        return $this->client->get('/v1/sys/auth' . $path);
    }

    /**
     * This endpoint enables a new auth method.
     * After enabling, the auth method can be accessed and configured via the auth path specified as part of the URL.
     * This auth path will be nested under the auth prefix.
     * @param string $path
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/auth#enable-auth-method
     * @throws \yii\base\InvalidConfigException
     */
    public function enableAuthMethod(string $path, array $data)
    {
        return $this->client->post('/v1/sys/auth' . $path, $data);
    }

    /**
     * This endpoint disables the auth method at the given auth path.
     * @param string $path
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/auth#disable-auth-method
     * @throws \yii\base\InvalidConfigException
     */
    public function disableAuthMethod(string $path)
    {
        return $this->client->delete('/v1/sys/auth' . $path);
    }

    /**
     * This endpoint reads the given auth path's configuration.
     * This endpoint requires sudo capability on the final path, but the same functionality can be achieved without sudo via sys/mounts/auth/[auth-path]/tune.
     * @param string $path
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/auth#read-auth-method-tuning
     * @throws \yii\base\InvalidConfigException
     */
    public function readAuthMethodTuning(string $path)
    {
        return $this->client->get('/v1/sys/auth' . $path . '/tune');
    }

    /**
     * Tune configuration parameters for a given auth path.
     * This endpoint requires sudo capability on the final path, but the same functionality can be achieved without sudo via sys/mounts/auth/[auth-path]/tune.
     * @param string $path
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/auth#tune-auth-method
     * @throws \yii\base\InvalidConfigException
     */
    public function tuneAuthMethod(string $path)
    {
        return $this->client->post('/v1/sys/auth' . $path . '/tune');
    }

    /**
     * Endpoint is used to fetch the capabilities of a token on the given paths.
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/capabilities#query-token-capabilities
     * @throws \yii\base\InvalidConfigException
     */
    public function queryTokenCapabilities(array $data)
    {
        return $this->client->post('/v1/sys/capabilities', $data);
    }

    /**
     * Endpoint is used to fetch the capabilities of the token associated with the given accessor.
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/capabilities-accessor#query-token-accessor-capabilities
     * @throws \yii\base\InvalidConfigException
     */
    public function queryTokenAccessorCapabilities(array $data)
    {
        return $this->client->post('/v1/sys/capabilities-accessor', $data);
    }

    /**
     * Endpoint is used to fetch the capabilities of the token used to make the API call, on the given paths.
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/capabilities-self#query-self-capabilities
     * @throws \yii\base\InvalidConfigException
     */
    public function querySelfCapabilities(array $data)
    {
        return $this->client->post('/v1/sys/capabilities-self', $data);
    }

    /**
     * This endpoint lists the request headers that are configured to be audited.
     * This endpoint lists the information for the given request header.
     * @param string $name
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-auditing#read-all-audited-request-headers
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-auditing#read-single-audit-request-header
     * @throws \yii\base\InvalidConfigException
     */
    public function getAuditedRequestHeaders(string $name = '')
    {
        return $this->client->get('/v1/sys/config/auditing/request-headers' . $name);
    }

    /**
     * This endpoint enables auditing of a header.
     * @param string $name
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-auditing#create-update-audit-request-header
     * @throws \yii\base\InvalidConfigException
     */
    public function setAuditRequestHeader(string $name, array $data)
    {
        return $this->client->post('/v1/sys/config/auditing/request-headers' . $name, $data);
    }

    /**
     * This endpoint disables auditing of the given request header.
     * @param string $name
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-auditing#delete-audit-request-header
     * @throws \yii\base\InvalidConfigException
     */
    public function deleteAuditRequestHeader(string $name)
    {
        return $this->client->delete('/v1/sys/config/auditing/request-headers' . $name);
    }

    /**
     * This endpoint returns the current CORS configuration.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-cors#read-cors-settings
     * @throws \yii\base\InvalidConfigException
     */
    public function getCORSSettings()
    {
        return $this->client->get('/v1/sys/config/cors');
    }

    /**
     * This endpoint allows configuring the origins that are permitted to make cross-origin requests, as well as headers that are allowed on cross-origin requests.
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-cors#configure-cors-settings
     * @throws \yii\base\InvalidConfigException
     */
    public function setCORSSettings(array $data)
    {
        return $this->client->post('/v1/sys/config/cors', $data);
    }

    /**
     * This endpoint removes any CORS configuration.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-cors#delete-cors-settings
     * @throws \yii\base\InvalidConfigException
     */
    public function deleteCORSSettings()
    {
        return $this->client->delete('/v1/sys/config/cors');
    }

    /**
     * This endpoint returns a sanitized version of the configuration state.
     * The configuration excludes certain fields and mappings in the configuration file that can potentially contain sensitive information,
     * which includes values from Storage.Config, HAStorage.Config, Seals.Config and the Telemetry.CirconusAPIToken value.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-state#get-sanitized-configuration-state
     * @throws \yii\base\InvalidConfigException
     */
    public function getSanitizedConfigurationState()
    {
        return $this->client->get('/v1/sys/config/state/sanitized');
    }

    /**
     * This endpoint returns the given UI header configuration.
     * @param string $name
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-ui#read-ui-settings
     * @throws \yii\base\InvalidConfigException
     */
    public function getUISettings(string $name)
    {
        return $this->client->get('/v1/sys/config/ui/headers' . $name);
    }

    /**
     * This endpoint allows configuring the values to be returned for the UI header.
     * @param string $name
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-ui#configure-ui-headers
     * @throws \yii\base\InvalidConfigException
     */
    public function setUIHeaders(string $name, array $data)
    {
        return $this->client->post('/v1/sys/config/ui/headers' . $name, $data);
    }

    /**
     * This endpoint removes a UI header.
     * @param string $name
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-ui#delete-a-ui-header
     * @throws \yii\base\InvalidConfigException
     */
    public function deleteUIHeader(string $name)
    {
        return $this->client->delete('/v1/sys/config/ui/headers' . $name);
    }

    /**
     * This endpoint returns a list of configured UI headers.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-ui#list-ui-headers
     * @throws \yii\base\InvalidConfigException
     */
    public function listUIHeaders()
    {
        return $this->client->list('/v1/sys/config/ui/headers');
    }

    /**
     * This endpoint returns the experiments available and enabled on the Vault node.
     * Experiments are per-node and cannot be changed while the node is running.
     * See the -experiment flag and the experiments config key documentation for details on enabling experiments.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/experiments#read-experiments
     * @throws \yii\base\InvalidConfigException
     */
    public function getExperiments()
    {
        return $this->client->get('/v1/sys/experiments');
    }

    /**
     * This endpoint reads the configuration and process of the current root generation attempt.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/generate-recovery-token#read-recovery-token-generation-progress
     * @throws \yii\base\InvalidConfigException
     */
    public function getRecoveryTokenGenerationProgress()
    {
        return $this->client->get('/v1/sys/generate-recovery-token/attempt');
    }

    /**
     * This endpoint initializes a new recovery token generation attempt. Only a single recovery token generation attempt can take place at a time.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/generate-recovery-token#start-recovery-token-generation
     * @throws \yii\base\InvalidConfigException
     */
    public function startRecoveryTokenGeneration()
    {
        return $this->client->post('/v1/sys/generate-recovery-token/attempt');
    }

    /**
     * This endpoint cancels any in-progress recovery token generation attempt. This clears any progress made. This must be called to change the OTP or PGP key being used.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/generate-recovery-token#cancel-recovery-token-generation
     * @throws \yii\base\InvalidConfigException
     */
    public function cancelRecoveryTokenGeneration()
    {
        return $this->client->delete('/v1/sys/generate-recovery-token/attempt');
    }

    /**
     * This endpoint is used to enter a single root key share to progress the recovery token generation attempt.
     * If the threshold number of root key shares is reached, Vault will complete the recovery token generation and issue the new token.
     * Otherwise, this API must be called multiple times until that threshold is met. The attempt nonce must be provided with each call.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/generate-recovery-token#provide-key-share-to-generate-recovery-token
     * @throws \yii\base\InvalidConfigException
     */
    public function setGenerateRecoveryToken(array $data)
    {
        return $this->client->post('/v1/sys/generate-recovery-token/update', $data);
    }

    /**
     * This endpoint reads the configuration and process of the current root generation attempt.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/generate-root#read-root-generation-progress
     * @throws \yii\base\InvalidConfigException
     */
    public function getRootGenerationProgress()
    {
        return $this->client->get('/v1/sys/generate-root/attempt');
    }

    /**
     * This endpoint initializes a new root generation attempt. Only a single root generation attempt can take place at a time.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/generate-root#start-root-token-generation
     * @throws \yii\base\InvalidConfigException
     */
    public function startRootTokenGeneration()
    {
        return $this->client->post('/v1/sys/generate-root/attempt');
    }

    /**
     * This endpoint cancels any in-progress root generation attempt. This clears any progress made. This must be called to change the OTP or PGP key being used.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/generate-root#cancel-root-generation
     * @throws \yii\base\InvalidConfigException
     */
    public function cancelRootTokenGeneration()
    {
        return $this->client->delete('/v1/sys/generate-root/attempt');
    }

    /**
     * This endpoint is used to enter a single root key share to progress the root generation attempt.
     * If the threshold number of root key shares is reached, Vault will complete the root generation and issue the new token.
     * Otherwise, this API must be called multiple times until that threshold is met. The attempt nonce must be provided with each call.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/generate-root#provide-key-share-to-generate-root
     * @throws \yii\base\InvalidConfigException
     */
    public function setRootTokenGeneratio(array $data)
    {
        return $this->client->post('/v1/sys/generate-root/update', $data);
    }

    /**
     * This endpoint returns the health status of Vault.
     * This matches the semantics of a Consul HTTP health check and provides a simple way to monitor the health of a Vault instance.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/health#read-health-information
     * @throws \yii\base\InvalidConfigException
     */
    public function getHealthInformation()
    {
        return $this->client->get('/v1/sys/health');
    }

    /**
     * This endpoint returns information about the host instance that the Vault server is running on.
     * The data returned includes CPU information, CPU times, disk usage, host info, and memory statistics.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/host-info#collect-host-information
     * @throws \yii\base\InvalidConfigException
     */
    public function getHostInformation()
    {
        return $this->client->get('/v1/sys/host-info');
    }

    /**
     * This endpoint returns the information about the in-flight requests.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/in-flight-req#collect-in-flight-request-information
     * @throws \yii\base\InvalidConfigException
     */
    public function getInFlightRequestInformation()
    {
        return $this->client->get('/v1/sys/in-flight-req');
    }

    /**
     * This endpoint returns the initialization status of Vault.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/init#read-initialization-status
     * @throws \yii\base\InvalidConfigException
     */
    public function getInitializationStatus()
    {
        return $this->client->get('/v1/sys/init');
    }

    /**
     * This endpoint initializes a new Vault. The Vault must not have been previously initialized.
     * The recovery options, as well as the stored shares option, are only available when using Auto Unseal.
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/init#start-initialization
     * @throws \yii\base\InvalidConfigException
     */
    public function startInitialization(array $data)
    {
        return $this->client->post('/v1/sys/init', $data);
    }

    /**
     * This endpoint returns the total number of Entities.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-counters#entities
     * @throws \yii\base\InvalidConfigException
     */
    public function getEntities()
    {
        return $this->client->get('/v1/sys/internal/counters/entities');
    }

    /**
     * This endpoint returns the total number of Tokens.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-counters#tokens
     * @throws \yii\base\InvalidConfigException
     */
    public function getTokens()
    {
        return $this->client->get('/v1/sys/internal/counters/tokens');
    }

    /**
     * This endpoint returns client activity information for a given billing period,
     * which is represented by the start_time and end_time parameters.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-counters#client-count
     * @throws \yii\base\InvalidConfigException
     */
    public function getClientCount(string $params = '')
    {
        return $this->client->get('/v1/sys/internal/counters/activity' . $params);
    }

    /**
     * This endpoint returns the client activity in the current month.
     * The response will have activity attributions per namespace, per mount within each namespaces, and new clients information.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-counters#partial-month-client-count
     * @throws \yii\base\InvalidConfigException
     */
    public function getPartialMonthClientCount()
    {
        return $this->client->get('/v1/sys/internal/counters/activity/monthly');
    }

    /**
     * Endpoint is used to configure logging of active clients
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-counters#update-the-client-count-configuration
     * @throws \yii\base\InvalidConfigException
     */
    public function updateClientCountConfiguration(array $data)
    {
        return $this->client->post('/v1/sys/internal/counters/config', $data);
    }

    /**
     * Reading the configuration shows the current settings, as well as a flag whether any data can be queried.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-counters#read-the-client-count-configuration
     * @throws \yii\base\InvalidConfigException
     */
    public function getClientCountConfiguration()
    {
        return $this->client->get('/v1/sys/internal/counters/config');
    }

    /**
     * This endpoint returns an export of the clients that had activity within the provided start and end times.
     * The returned set of client information will be deduplicated over the time window and will show the earliest activity logged for each client.
     * The output will be ordered chronologically by month of activity.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-counters#activity-export
     * @throws \yii\base\InvalidConfigException
     */
    public function getActivity()
    {
        return $this->client->get('/v1/sys/internal/counters/activity/export');
    }

    /**
     * Endpoint is used to generate an OpenAPI document of the mounted backends
     * @param string $params
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-specs-openapi#get-openapi-document
     * @throws \yii\base\InvalidConfigException
     */
    public function getOpenAPIDocument(string $params = '')
    {
        return $this->client->get('/v1/sys/internal/specs/openapi' . $params);
    }

    /**
     * Endpoint is used to expose feature flags to the UI so that it can change its behavior in response, even before a user logs in.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-ui-feature#get-enabled-feature-flags
     * @throws \yii\base\InvalidConfigException
     */
    public function getEnabledFeatureFlags()
    {
        return $this->client->get('/v1/sys/internal/ui/feature-flags');
    }

    /**
     * Endpoint is used to manage mount listing visibility.
     * The response generated by this endpoint is based on the listing_visibility value on the mount, which can be set during mount time or via mount tuning.
     * This is currently only being used internally, for the UI and for CLI preflight checks, and is an unauthenticated endpoint.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-ui-mounts#get-available-visible-mounts
     * @throws \yii\base\InvalidConfigException
     */
    public function getAvailableVisibleMounts()
    {
        return $this->client->get('/v1/sys/internal/ui/mounts');
    }

    /**
     * This endpoint lists details for a specific mount path.
     * This is an authenticated endpoint, and is currently only being used internally.
     * @param string $path
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-ui-mounts#get-single-mount-details
     * @throws \yii\base\InvalidConfigException
     */
    public function getSingleMountDetails(string $path)
    {
        return $this->client->get('/v1/sys/internal/ui/mounts' . $path);
    }

    /**
     * Endpoint is used to expose namespaces to the UI so that it can change its behavior in response, even before a user logs in.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-ui-namespaces#get-namespaces
     * @throws \yii\base\InvalidConfigException
     */
    public function getNamespaces()
    {
        return $this->client->get('/v1/sys/internal/ui/namespaces');
    }

    /**
     * This endpoint lists the resultant-acl relevant to the UI.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-ui-resultant-acl#get-resultant-acl
     * @throws \yii\base\InvalidConfigException
     */
    public function getResultantACL()
    {
        return $this->client->get('/v1/sys/internal/ui/resultant-acl');
    }

    /**
     * This endpoint returns information about the current encryption key used by Vault.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/key-status#get-encryption-key-status
     * @throws \yii\base\InvalidConfigException
     */
    public function getEncryptionKeyStatus()
    {
        return $this->client->get('/v1/sys/key-status');
    }

    /**
     * This endpoint returns the HA status of the Vault cluster.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/ha-status#ha-status
     * @throws \yii\base\InvalidConfigException
     */
    public function getHAStatus()
    {
        return $this->client->get('/v1/sys/ha-status');
    }

    /**
     * This endpoint returns the high availability status and current leader instance of Vault.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/leader#read-leader-status
     * @throws \yii\base\InvalidConfigException
     */
    public function getLeaderStatus()
    {
        return $this->client->get('/v1/sys/leader');
    }

    /**
     * This endpoint retrieve lease metadata.
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/leases#read-lease
     * @throws \yii\base\InvalidConfigException
     */
    public function readLease(array $data)
    {
        return $this->client->post('/v1/sys/leases/lookup', $data);
    }

    /**
     * This endpoint returns a list of lease ids.
     * @param string $prefix
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/leases#list-leases
     * @throws \yii\base\InvalidConfigException
     */
    public function listLeases(string $prefix)
    {
        return $this->client->list('/v1/sys/leases/lookup' . $prefix);
    }

    /**
     * This endpoint renews a lease, requesting to extend the lease.
     * Token leases cannot be renewed using this endpoint, use instead the auth/token/renew endpoint.
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/leases#renew-lease
     * @throws \yii\base\InvalidConfigException
     */
    public function renewLease(array $data)
    {
        return $this->client->post('/v1/sys/leases/renew', $data);
    }

    /**
     * This endpoint revokes a lease immediately.
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/leases#revoke-lease
     * @throws \yii\base\InvalidConfigException
     */
    public function revokeLease(array $data)
    {
        return $this->client->post('/v1/sys/leases/revoke', $data);
    }

    /**
     * This endpoint revokes all secrets or tokens generated under a given prefix immediately.
     * @param string $prefix
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     *@see https://developer.hashicorp.com/vault/api-docs/system/leases#revoke-force
     */
    public function revokeForceLease(string $prefix)
    {
        return $this->client->post('/v1/sys/leases/revoke-force' . $prefix);
    }

    /**
     * This endpoint revokes all secrets (via a lease ID prefix) or tokens (via the tokens' path property) generated under a given prefix immediately.
     * @param string $prefix
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     *@see https://developer.hashicorp.com/vault/api-docs/system/leases#revoke-prefix
     */
    public function revokePrefixLease(string $prefix)
    {
        return $this->client->post('/v1/sys/leases/revoke-prefix' . $prefix);
    }

    /**
     * This endpoint cleans up the dangling storage entries for leases: for each lease entry in storage,
     * Vault will verify that it has an associated valid non-expired token in storage, and if not, the lease will be revoked.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/leases#tidy-leases
     * @throws \yii\base\InvalidConfigException
     */
    public function setTidyLeases()
    {
        return $this->client->post('/v1/sys/leases/tidy');
    }

    /**
     * This endpoint returns the total count of a type of lease, as well as a count per mount point. Note that it currently only supports type "irrevocable".
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/leases#lease-counts
     * @throws \yii\base\InvalidConfigException
     */
    public function getLeaseCounts(array $data)
    {
        return $this->client->get('/v1/sys/leases/count', $data);
    }

    /**
     * This endpoint returns the total count of a type of lease, as well as a list of leases per mount point. Note that it currently only supports type "irrevocable".
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/leases#leases-list
     * @throws \yii\base\InvalidConfigException
     */
    public function getLeasesList(array $data)
    {
        return $this->client->get('/v1/sys/leases', $data);
    }

    /**
     * This endpoint lists the locked users information in Vault.
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/user-lockout#list-locked-users
     * @throws \yii\base\InvalidConfigException
     */
    public function getLockedUsers(array $data = [])
    {
        return $this->client->get('/v1/sys/locked-users', $data);
    }

    /**
     * This endpoint unlocks a locked user with provided mount_accessor and alias_identifier in namespace in which the request was made if locked.
     * This command is idempotent, meaning it succeeds even if user with the given mount_accessor and alias_identifier is not locked.
     * This endpoint was added in Vault 1.13.
     * @param string $accessor
     * @param string $identifier
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/user-lockout#unlock-user
     * @throws \yii\base\InvalidConfigException
     */
    public function unlockUser(string $accessor, string $identifier)
    {
        return $this->client->post('/v1/sys/locked-users' . $accessor . '/unlock' . $identifier);
    }

    /**
     * This endpoint returns the telemetry metrics for Vault.
     * It can be used by metrics collections systems like Prometheus that use a pull model for metrics collection.
     * @param string $format, value METRIC_FORMAT_JSON or METRIC_FORMAT_PROMETHEUS
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/metrics#read-telemetry-metrics
     * @throws \yii\base\InvalidConfigException
     */
    public function getMetrics(string $format = self::METRIC_FORMAT_PROMETHEUS)
    {
        return $this->client->get('/v1/sys/metrics?format=' . $format);
    }

    /**
     * This endpoint streams logs back to the client from Vault. Note that unlike most API endpoints in Vault, this one does not return JSON by default.
     * This will send back data in whatever log format Vault has been configured with. By default, this is text.
     * @param string $level, value LOG_LEVEL_INFO or LOG_LEVEL_DEBUG
     * @param string $format, value LOG_FORMAT_STANDARD or LOG_FORMAT_JSON
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     *@see https://developer.hashicorp.com/vault/api-docs/system/monitor#monitor-system-logs
     */
    public function getSystemLogs(string $level = self::LOG_LEVEL_INFO, string $format = self::LOG_FORMAT_STANDARD)
    {
        return $this->client->get('/v1/sys/monitor?log_level=' . $level . '&log_format=' . $format);
    }

    /**
     * These endpoints list all the mounted secrets engines.
     * @see https://developer.hashicorp.com/vault/api-docs/system/mounts#list-mounted-secrets-engines
     * @return mixed
     */
    public function getMountedSecretsEngines(string $path = '')
    {
        return $this->client->get('/v1/sys/mounts' . $path);
    }

    /**
     * This endpoint enables a new secrets engine at the given path.
     * @param string $path
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/mounts#enable-secrets-engine
     * @throws \yii\base\InvalidConfigException
     */
    public function enableSecretEngine(string $path, array $data)
    {
        return $this->client->post('/v1/sys/mounts' . $path, $data);
    }

    /**
     * This endpoint disables the mount point specified in the URL.
     * @param  string $name
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/mounts#disable-secrets-engine
     * @throws \yii\base\InvalidConfigException
     */
    public function disableSecretEngine(string $name)
    {
        return $this->client->delete('/v1/sys/mounts' . $name);
    }

    /**
     * This endpoint reads the given mount's configuration.
     * Unlike the mounts endpoint, this will return the current time in seconds for each TTL, which may be the system default or a mount-specific value.
     * @param string $path
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/mounts#read-mount-configuration
     * @throws \yii\base\InvalidConfigException
     */
    public function readMountConfiguration(string $path)
    {
        return $this->client->get('/v1/sys/mounts' . $path . '/tune');
    }

    /**
     * This endpoint tunes configuration parameters for a given mount point.
     * @param string $path
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/mounts#tune-mount-configuration
     * @throws \yii\base\InvalidConfigException
     */
    public function tuneMountConfiguration(string $path, array $data)
    {
        return $this->client->post('/v1/sys/mounts' . $path . '/tune', $data);
    }

    /**
     * This endpoint lists all the namespaces.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/namespaces#list-namespaces
     * @throws \yii\base\InvalidConfigException
     */
    public function listNamespaces()
    {
        return $this->client->list('/v1/sys/namespaces');
    }

    /**
     * This endpoint creates a namespace at the given path.
     * @param string $path
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/namespaces#create-namespace
     * @throws \yii\base\InvalidConfigException
     */
    public function createNamespace(string $path, array $data)
    {
        return $this->client->post('/v1/sys/namespaces' . $path, $data);
    }

    /**
     * This endpoint patches an existing namespace at the specified path.
     * @param string $path
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/namespaces#patch-namespace
     * @throws \yii\base\InvalidConfigException
     */
    public function updateNamespace(string $path, array $data)
    {
        return $this->client->patch('/v1/sys/namespaces' . $path, $data);
    }

    /**
     * This endpoint deletes a namespace at the specified path.
     * @param string $path
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/namespaces#delete-namespace
     * @throws \yii\base\InvalidConfigException
     */
    public function deleteNamespace(string $path)
    {
        return $this->client->delete('/v1/sys/namespaces' . $path);
    }

    /**
     * This endpoint gets the metadata for the given namespace path.
     * @param string $path
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/namespaces#read-namespace-information
     * @throws \yii\base\InvalidConfigException
     */
    public function getNamespaceInformation(string $path)
    {
        return $this->client->get('/v1/sys/namespaces' . $path);
    }

    /**
     * This endpoint locks the API for the current namespace path or optional subpath.
     * The behavior when interacting with Vault from a locked namespace is described in API Locked Response.
     * @param string $path
     * @param string $header
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/namespaces#lock-namespace
     * @throws \yii\base\InvalidConfigException
     */
    public function lockNamespace(string $path = '', string $header = '')
    {
        $headers = [];
        if ($header !== '') {
            $headers = [
                'X-Vault-Namespace' => $header,
            ];
        }

        return $this->client->post('/v1/sys/namespaces/api-lock/lock'. $path, [], $headers);
    }

    /**
     * This endpoint unlocks the api for the current namespace path or optional subpath.
     * @param string $path
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/namespaces#unlock-namespace
     * @throws \yii\base\InvalidConfigException
     */
    public function unlockNamespace(string $path = '', array $data = [])
    {
        return $this->client->post('/v1/sys/namespaces/api-lock/unlock'. $path, $data);
    }

    /**
     * This endpoint reloads mounted plugin backends.
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/plugins-reload-backend#reload-plugins
     * @throws \yii\base\InvalidConfigException
     */
    public function reloadPlugins(array $data)
    {
        return $this->client->post('/v1/sys/plugins/reload/backend', $data);
    }

    /**
     * This endpoint lists the plugins in the catalog by type.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/plugins-catalog#list-plugins
     * @throws \yii\base\InvalidConfigException
     */
    public function getPlugins()
    {
        return $this->client->get('/v1/sys/plugins/catalog');
    }

    /**
     * This endpoint lists the plugins in the catalog by type.
     * @param string $type
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/plugins-catalog#list-plugins-1
     * @throws \yii\base\InvalidConfigException
     */
    public function listPlugin(string $type)
    {
        return $this->client->list('/v1/sys/plugins/catalog' . $type);
    }

    /**
     * This endpoint registers a new plugin, or updates an existing one with the supplied name.
     * @param string $type
     * @param string $name
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/plugins-catalog#register-plugin
     * @throws \yii\base\InvalidConfigException
     */
    public function registerPlugin(string $type, string $name, array $data)
    {
        return $this->client->post('/v1/sys/plugins/catalog' . $type . $name, $data);
    }

    /**
     * This endpoint returns the configuration data for the plugin with the given name.
     * @param string $type
     * @param string $name
     * @param string $version
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/plugins-catalog#read-plugin
     * @throws \yii\base\InvalidConfigException
     */
    public function getPlugin(string $type, string $name, string $version = '')
    {
        if ($version !== '') {
            $version = '?version=' . $version;
        }

        return $this->client->get('/v1/sys/plugins/catalog' . $type . $name . $version);
    }

    /**
     * This endpoint removes the plugin with the given name.
     * @param string $type
     * @param string $name
     * @param string $version
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/plugins-catalog#remove-plugin-from-catalog
     * @throws \yii\base\InvalidConfigException
     */
    public function removePlugin(string $type, string $name, string $version = '')
    {
        if ($version !== '') {
            $version = '?version=' . $version;
        }

        return $this->client->delete('/v1/sys/plugins/catalog' . $type . $name . $version);
    }

    /**
     * This endpoint lists all configured policies.
     * This endpoint retrieve the policy body for the named policy.
     * @param  string $name
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/policy#list-policies
     * @see https://developer.hashicorp.com/vault/api-docs/system/policy#read-policy
     * @throws \yii\base\InvalidConfigException
     */
    public function policy(string $name = '')
    {
        return $this->client->get('/v1/sys/policy' . $name);
    }

    /**
     * This endpoint adds a new or updates an existing policy.
     * Once a policy is updated, it takes effect immediately to all associated users.
     * @param string $name
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/policy#create-update-policy
     * @throws \yii\base\InvalidConfigException
     */
    public function setPolicy(string $name, array $data)
    {
        return $this->client->post('/v1/sys/policy' . $name, $data);
    }

    /**
     * This endpoint deletes the policy with the given name.
     * This will immediately affect all users associated with this policy.
     * @param  string $name
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/policy#delete-policy
     * @throws \yii\base\InvalidConfigException
     */
    public function deletePolicy(string $name)
    {
        return $this->client->delete('/v1/sys/policy' . $name);
    }

    /**
     * This endpoint lists all configured ACL policies.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#list-acl-policies
     * @throws \yii\base\InvalidConfigException
     */
    public function listACLPolicies()
    {
        return $this->client->list('/v1/sys/policies/acl');
    }

    /**
     * This endpoint retrieves information about the named ACL policy.
     * @param string $name
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#read-acl-policy
     * @throws \yii\base\InvalidConfigException
     */
    public function getACLPolicy(string $name)
    {
        return $this->client->get('/v1/sys/policies/acl' . $name);
    }

    /**
     * This endpoint adds a new or updates an existing ACL policy.
     * Once a policy is updated, it takes effect immediately to all associated users.
     * @param string $name
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#create-update-acl-policy
     * @throws \yii\base\InvalidConfigException
     */
    public function setACLPolicy(string $name, array $data)
    {
        return $this->client->post('/v1/sys/policies/acl' . $name, $data);
    }

    /**
     * This endpoint deletes the ACL policy with the given name.
     * This will immediately affect all users associated with this policy.
     * (A deleted policy set on a token acts as an empty policy.)
     * @param string $name
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#delete-acl-policy
     * @throws \yii\base\InvalidConfigException
     */
    public function deleteACLPolicy(string $name)
    {
        return $this->client->delete('/v1/sys/policies/acl' . $name);
    }

    /**
     * This endpoint lists all configured RGP policies.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#list-rgp-policies
     * @throws \yii\base\InvalidConfigException
     */
    public function listRGPPolicies()
    {
        return $this->client->list('/v1/sys/policies/rgp');
    }

    /**
     * This endpoint retrieves information about the named RGP policy.
     * @param string $name
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#read-rgp-policy
     * @throws \yii\base\InvalidConfigException
     */
    public function getRGPPolicy(string $name)
    {
        return $this->client->get('/v1/sys/policies/rgp' . $name);
    }

    /**
     * This endpoint adds a new or updates an existing RGP policy.
     * Once a policy is updated, it takes effect immediately to all associated users.
     * @param string $name
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#create-update-rgp-policy
     * @throws \yii\base\InvalidConfigException
     */
    public function setRGPPolicy(string $name, array $data)
    {
        return $this->client->post('/v1/sys/policies/rgp' . $name, $data);
    }

    /**
     * This endpoint deletes the RGP policy with the given name.
     * This will immediately affect all users associated with this policy.
     * (A deleted policy set on a token acts as an empty policy.)
     * @param string $name
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#delete-rgp-policy
     * @throws \yii\base\InvalidConfigException
     */
    public function deleteRGPPolicy(string $name)
    {
        return $this->client->delete('/v1/sys/policies/rgp' . $name);
    }

    /**
     * This endpoint lists all configured EGP policies.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#list-egp-policies
     * @throws \yii\base\InvalidConfigException
     */
    public function listEGPPolicies()
    {
        return $this->client->list('/v1/sys/policies/egp');
    }

    /**
     * This endpoint retrieves information about the named EGP policy.
     * @param string $name
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#read-egp-policy
     * @throws \yii\base\InvalidConfigException
     */
    public function getEGPPolicy(string $name)
    {
        return $this->client->get('/v1/sys/policies/egp' . $name);
    }

    /**
     * This endpoint adds a new or updates an existing EGP policy.
     * Once a policy is updated, it takes effect immediately to all associated users.
     * @param string $name
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#create-update-egp-policy
     * @throws \yii\base\InvalidConfigException
     */
    public function setEGPPolicy(string $name, array $data)
    {
        return $this->client->post('/v1/sys/policies/egp' . $name, $data);
    }

    /**
     * This endpoint deletes the EGP policy with the given name from all paths on which it was configured.
     * @param string $name
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#delete-egp-policy
     * @throws \yii\base\InvalidConfigException
     */
    public function deleteEGPPolicy(string $name)
    {
        return $this->client->delete('/v1/sys/policies/egp' . $name);
    }

    /**
     * This endpoint adds a new or updates an existing password policy.
     * Once a policy is updated, it takes effect immediately to all associated secret engines.
     * @param string $name
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies-password#create-update-password-policy
     * @throws \yii\base\InvalidConfigException
     */
    public function setPasswordPolicy(string $name, array $data)
    {
        return $this->client->post('/v1/sys/policies/password' . $name, $data);
    }

    /**
     * This endpoint list the password policies.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies-password#list-password-policies
     * @throws \yii\base\InvalidConfigException
     */
    public function listPasswordPolicies()
    {
        return $this->client->list('/v1/sys/policies/password');
    }

    /**
     * This endpoint retrieves information about the named password policy.
     * @param string $name
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies-password#read-password-policy
     * @throws \yii\base\InvalidConfigException
     */
    public function getPasswordPolicy(string $name)
    {
        return $this->client->get('/v1/sys/policies/password' . $name);
    }

    /**
     * This endpoint deletes the password policy with the given name.
     * This does not check if any secret engines are using it prior to deletion,
     * so you should ensure that any engines that are utilizing this password policy are changed
     * to a different policy (or to that engines' default behavior).
     * @param string $name
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies-password#delete-password-policy
     * @throws \yii\base\InvalidConfigException
     */
    public function deletePasswordPolicy(string $name)
    {
        return $this->client->delete('/v1/sys/policies/password' . $name);
    }

    /**
     * This endpoint generates a password from the specified existing password policy.
     * @param string $name
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies-password#generate-password-from-password-policy
     * @throws \yii\base\InvalidConfigException
     */
    public function generatePasswordPolicy(string $name)
    {
        return $this->client->get('/v1/sys/policies/password' . $name . '/generate');
    }

    /**
     * This endpoint returns an HTML page listing the available profiles.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/pprof#index
     * @throws \yii\base\InvalidConfigException
     */
    public function getPprof()
    {
        return $this->client->get('/v1/sys/pprof');
    }

    /**
     * This endpoint returns a sampling of historical memory allocations over the life of the program.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/pprof#allocs
     * @throws \yii\base\InvalidConfigException
     */
    public function getAllocs()
    {
        return $this->client->get('/v1/sys/pprof/allocs');
    }

    /**
     * This endpoint returns a sampling of goroutines involved in blocking on synchronization primitives.
     * It is included for completeness, but since Vault doesn't normally enable collection of this data,
     * it won't return anything useful with the standard Vault binary.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/pprof#block
     * @throws \yii\base\InvalidConfigException
     */
    public function getBlock()
    {
        return $this->client->get('/v1/sys/pprof/block');
    }

    /**
     * This endpoint returns the running program's command line, with arguments separated by NUL bytes.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/pprof#cmdline
     * @throws \yii\base\InvalidConfigException
     */
    public function getCmdline()
    {
        return $this->client->get('/v1/sys/pprof/cmdline');
    }

    /**
     * This endpoint returns stack traces of all current goroutines.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/pprof#goroutine
     * @throws \yii\base\InvalidConfigException
     */
    public function getGoroutine()
    {
        return $this->client->get('/v1/sys/pprof/goroutine');
    }

    /**
     * This endpoint returns a sampling of memory allocations of live object.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/pprof#heap
     * @throws \yii\base\InvalidConfigException
     */
    public function getHeap()
    {
        return $this->client->get('/v1/sys/pprof/heap');
    }

    /**
     * This endpoint returns a sampling of goroutines holding contended mutexes.
     * It is included for completeness, but since Vault doesn't normally enable collection of this data,
     * it won't return anything useful with the standard Vault binary.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/pprof#mutex
     * @throws \yii\base\InvalidConfigException
     */
    public function getMutex()
    {
        return $this->client->get('/v1/sys/pprof/mutex');
    }

    /**
     * This endpoint returns a pprof-formatted cpu profile payload.
     * Profiling lasts for duration specified in seconds GET parameter, or for 30 seconds if not specified.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/pprof#profile
     * @throws \yii\base\InvalidConfigException
     */
    public function getProfile()
    {
        return $this->client->get('/v1/sys/pprof/profile');
    }

    /**
     * This endpoint returns the program counters listed in the request.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/pprof#symbol
     * @throws \yii\base\InvalidConfigException
     */
    public function getSymbol()
    {
        return $this->client->get('/v1/sys/pprof/symbol');
    }

    /**
     * This endpoint returns stack traces of goroutines that led to the creation of new OS threads.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/pprof#threadcreate
     * @throws \yii\base\InvalidConfigException
     */
    public function getThreadcreate()
    {
        return $this->client->get('/v1/sys/pprof/threadcreate');
    }

    /**
     * This endpoint returns the execution trace in binary form.
     * Tracing lasts for duration specified in seconds GET parameter, or for 1 second if not specified.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/pprof#trace
     * @throws \yii\base\InvalidConfigException
     */
    public function getTrace()
    {
        return $this->client->get('/v1/sys/pprof/trace');
    }

    /**
     * Endpoint is used to show rate limit quotas
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/quotas-config#get-the-rate-limit-configuration
     * @throws \yii\base\InvalidConfigException
     */
    public function getRateLimit()
    {
        return $this->client->get('/v1/sys/quotas/config');
    }

    /**
     * Endpoint is used to configure rate limit quotas
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/quotas-config#create-or-update-the-rate-limit-configuration
     * @throws \yii\base\InvalidConfigException
     */
    public function setRateLimit(array $data)
    {
        return $this->client->post('/v1/sys/quotas/config', $data);
    }

    /**
     * This endpoint reads the value of the key at the given path.
     * This is the raw path in the storage backend and not the logical path that is exposed via the mount system.
     * @param string $path
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/raw#read-raw
     * @throws \yii\base\InvalidConfigException
     */
    public function getRaw(string $path)
    {
        return $this->client->get('/v1/sys/raw' . $path);
    }

    /**
     * This endpoint updates the value of the key at the given path.
     * This is the raw path in the storage backend and not the logical path that is exposed via the mount system.
     * @param string $path
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/raw#create-update-raw
     * @throws \yii\base\InvalidConfigException
     */
    public function setRaw(string $path, array $data)
    {
        return $this->client->get('/v1/sys/raw' . $path, $data);
    }

    /**
     * This endpoint returns a list keys for a given path prefix.
     * @param string $prefix
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/raw#list-raw
     * @throws \yii\base\InvalidConfigException
     */
    public function listRaw(string $prefix)
    {
        return $this->client->list('/v1/sys/raw' . $prefix);
    }

    /**
     * This endpoint deletes the key with given path.
     * This is the raw path in the storage backend and not the logical path that is exposed via the mount system.
     * @param string $path
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/raw#delete-raw
     * @throws \yii\base\InvalidConfigException
     */
    public function deleteRaw(string $path)
    {
        return $this->client->delete('/v1/sys/raw' . $path);
    }

    /**
     * Endpoint moves an already-mounted backend to a new mount point. This process works for both secret engines and auth methods.
     * The remount operation returns a migration ID to the user. The user may utilize the migration ID to look up the status of the mount migration.
     * More details about the remount operation are described in Mount Migration.
     * @param  string $from
     * @param  string $to
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/remount#move-backend
     * @throws \yii\base\InvalidConfigException
     */
    public function moveBackend(string $from, string $to)
    {
        $data = [
            'from' => $from,
            'to' => $to,
        ];

        return $this->client->post('/v1/sys/remount', $data);
    }

    /**
     * This endpoint is used to monitor the status of a mount migration operation, using the ID returned in the response of the sys/remount call.
     * The response contains the passed-in ID, the source and target mounts, and a status field that displays in-progress, success or failure.
     * @param string $migration_id
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/remount#monitor-migration-status
     * @throws \yii\base\InvalidConfigException
     */
    public function monitorMigrationStatus(string $migration_id)
    {
        return $this->client->get('/v1/sys/remount/status' . $migration_id);
    }

    /**
     * This endpoint triggers a rotation of the backend encryption key.
     * This is the key that is used to encrypt data written to the storage backend, and is not provided to operators.
     * This operation is done online.
     * Future values are encrypted with the new key, while old values are decrypted with previous encryption keys.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/rotate#rotate-encryption-key
     * @throws \yii\base\InvalidConfigException
     */
    public function setRotate()
    {
        return $this->client->post('/v1/sys/rotate');
    }

    /**
     * This endpoint configures the automatic rotation of the backend encryption key.
     * By default, the key is rotated after just under 4 billion encryptions, to satisfy the recommendation of NIST SP 800-38D.
     * One can configure rotations after fewer encryptions or on a time based schedule.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/rotate-config#get-the-auto-rotation-configuration
     * @throws \yii\base\InvalidConfigException
     */
    public function getAutoRotation()
    {
        return $this->client->get('/v1/sys/rotate/config');
    }

    /**
     * This endpoint configures the automatic rotation of the backend encryption key.
     * By default, the key is rotated after just under 4 billion encryptions, to satisfy the recommendation of NIST SP 800-38D.
     * One can configure rotations after fewer encryptions or on a time based schedule.
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/rotate-config#create-or-update-the-auto-rotation-configuration
     * @throws \yii\base\InvalidConfigException
     */
    public function setAutoRotation(array $data)
    {
        return $this->client->post('/v1/sys/rotate/config', $data);
    }

    /**
     * This endpoint seals the Vault. In HA mode, only an active node can be sealed.
     * Standby nodes should be restarted to get the same effect.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/seal#seal
     * @throws \yii\base\InvalidConfigException
     */
    public function setSeal()
    {
        return $this->client->post('/v1/sys/seal');
    }

    /**
     * This endpoint returns the seal status of the Vault.
     * This is an unauthenticated endpoint.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/seal-status#seal-status
     * @throws \yii\base\InvalidConfigException
     */
    public function getSealStatus()
    {
        return $this->client->get('/v1/sys/seal-status');
    }

    /**
     * This endpoint forces the node to give up active status.
     * If the node does not have active status, this endpoint does nothing.
     * Note that the node will sleep for ten seconds before attempting to grab the active lock again,
     * but if no standby nodes grab the active lock in the interim, the same node may become the active node again.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/step-down#step-down-leader
     * @throws \yii\base\InvalidConfigException
     */
    public function stepDownLeader()
    {
        return $this->client->post('/v1/sys/step-down');
    }

    /**
     * This endpoint is used to enter a single root key share to progress the unsealing of the Vault.
     * If the threshold number of root key shares is reached, Vault will attempt to unseal the Vault.
     * Otherwise, this API must be called multiple times until that threshold is met.
     * @param array $data
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/unseal#submit-unseal-key
     * @throws \yii\base\InvalidConfigException
     */
    public function unseal(array $data)
    {
        return $this->client->post('/v1/sys/unseal', $data);
    }

    /**
     * This endpoint returns the version history of the Vault.
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/version-history#read-version-history
     * @throws \yii\base\InvalidConfigException
     */
    public function getVersionHistory()
    {
        return $this->client->get('/v1/sys/version-history');
    }

    /**
     * This endpoint looks up wrapping properties for the given token.
     * @param string $token
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/wrapping-lookup#wrapping-lookup
     * @throws \yii\base\InvalidConfigException
     */
    public function getWrappingLookup(string $token)
    {
        $data = [
            'token' => $token,
        ];

        return $this->client->post('/v1/sys/wrapping/lookup', $data);
    }

    /**
     * This endpoint rewraps a response-wrapped token. The new token will use the same creation TTL as the original token and contain the same response.
     * The old token will be invalidated. This can be used for long-term storage of a secret in a response-wrapped token when rotation is a requirement.
     * @param string $token
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/wrapping-rewrap#wrapping-rewrap
     * @throws \yii\base\InvalidConfigException
     */
    public function setWrappingRewrap(string $token)
    {
        $data = [
            'token' => $token,
        ];

        return $this->client->post('/v1/sys/wrapping/rewrap', $data);
    }

    /**
     * This endpoint returns the original response inside the given wrapping token.
     * Unlike simply reading cubbyhole/response (which is deprecated), this endpoint provides additional validation checks on the token,
     * returns the original value on the wire rather than a JSON string representation of it, and ensures that the response is properly audit-logged.
     * This endpoint can be used by using a wrapping token as the client token in the API call, in which case the token parameter is not required;
     * or, a different token with permissions to access this endpoint can make the call and pass in the wrapping token in the token parameter.
     * Do not use the wrapping token in both locations; this will cause the wrapping token to be revoked but the value to be unable to be looked up,
     * as it will basically be a double-use of the token!
     * @param string $token
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/wrapping-unwrap#wrapping-unwrap
     * @throws \yii\base\InvalidConfigException
     */
    public function getWrappingUnwrap(string $token)
    {
        $data = [
            'token' => $token,
        ];

        return $this->client->post('/v1/sys/wrapping/unwrap', $data);
    }

    /**
     * This endpoint wraps the given user-supplied data inside a response-wrapped token.
     * @param array $data
     * @param array $headers
     * @return mixed
     * @see https://developer.hashicorp.com/vault/api-docs/system/wrapping-wrap#wrapping-wrap
     * @throws \yii\base\InvalidConfigException
     */
    public function setWrappingWrap(array $data, array $headers = [])
    {
        return $this->client->post('/v1/sys/wrapping/wrap', $data, $headers);
    }
}