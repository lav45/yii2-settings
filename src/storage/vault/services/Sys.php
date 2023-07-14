<?php

namespace lav45\settings\storage\vault\services;

use yii\di\Instance;
use yii\base\BaseObject;
use lav45\settings\storage\vault\Client;

/**
 * Class Sys (API) - System settings
 * @package lav45\settings\storage\vault\services
 */
class Sys extends BaseObject
{
    const METRIC_FORMAT_PROMETHEUS = 'prometheus';
    const METRIC_FORMAT_JSON = 'json';
    const LOG_LEVEL_INFO = 'info';
    const LOG_LEVEL_DEBUG = 'debug';
    const LOG_FORMAT_STANDARD = 'standard';
    const LOG_FORMAT_JSON = 'json';

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
     * Endpoint is used to list audit devices
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/audit#list-enabled-audit-devices
     */
    public function getEnabledAuditDevices()
    {
        return $this->client->get('/v1/sys/audit');
    }

    /**
     * Endpoint is used to enable audit devices
     * @param string $path
     * @param array $data
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/audit#enable-audit-device
     */
    public function enableAuditDevice(string $path, array $data)
    {
        return $this->client->post('/v1/sys/audit' . $path, $data);
    }

    /**
     * Endpoint is used to disable audit devices
     * @param string $path
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/audit#disable-audit-device
     */
    public function disableAuditDevice(string $path)
    {
        return $this->client->delete('/v1/sys/audit' . $path);
    }

    /**
     * Endpoint is used to calculate the hash of the data used by an audit device's hash function and salt.
     * @param string $path
     * @param array $data
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/audit-hash#calculate-hash
     */
    public function calculateHash(string $path, array $data)
    {
        return $this->client->post('/v1/sys/audit-hash' . $path, $data);
    }

    /**
     * This endpoint lists all enabled auth methods.
     * @param string $path
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/auth#list-auth-methods
     * @see https://developer.hashicorp.com/vault/api-docs/system/auth#read-auth-method-configuration
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
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/auth#enable-auth-method
     */
    public function enableAuthMethod(string $path, array $data)
    {
        return $this->client->post('/v1/sys/auth' . $path, $data);
    }

    /**
     * This endpoint disables the auth method at the given auth path.
     * @param string $path
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/auth#disable-auth-method
     */
    public function disableAuthMethod(string $path)
    {
        return $this->client->delete('/v1/sys/auth' . $path);
    }

    /**
     * This endpoint reads the given auth path's configuration.
     * This endpoint requires sudo capability on the final path, but the same functionality can be achieved without sudo via sys/mounts/auth/[auth-path]/tune.
     * @param string $path
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/auth#read-auth-method-tuning
     */
    public function readAuthMethodTuning(string $path)
    {
        return $this->client->get('/v1/sys/auth' . $path . '/tune');
    }

    /**
     * Tune configuration parameters for a given auth path.
     * This endpoint requires sudo capability on the final path, but the same functionality can be achieved without sudo via sys/mounts/auth/[auth-path]/tune.
     * @param string $path
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/auth#tune-auth-method
     */
    public function tuneAuthMethod(string $path)
    {
        return $this->client->post('/v1/sys/auth' . $path . '/tune');
    }

    /**
     * Endpoint is used to fetch the capabilities of a token on the given paths.
     * @param array $data
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/capabilities#query-token-capabilities
     */
    public function queryTokenCapabilities(array $data)
    {
        return $this->client->post('/v1/sys/capabilities', $data);
    }

    /**
     * Endpoint is used to fetch the capabilities of the token associated with the given accessor.
     * @param array $data
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/capabilities-accessor#query-token-accessor-capabilities
     */
    public function queryTokenAccessorCapabilities(array $data)
    {
        return $this->client->post('/v1/sys/capabilities-accessor', $data);
    }

    /**
     * Endpoint is used to fetch the capabilities of the token used to make the API call, on the given paths.
     * @param array $data
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/capabilities-self#query-self-capabilities
     */
    public function querySelfCapabilities(array $data)
    {
        return $this->client->post('/v1/sys/capabilities-self', $data);
    }

    /**
     * This endpoint lists the request headers that are configured to be audited.
     * This endpoint lists the information for the given request header.
     * @param string $name
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-auditing#read-all-audited-request-headers
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-auditing#read-single-audit-request-header
     */
    public function getAuditedRequestHeaders(string $name = '')
    {
        return $this->client->get('/v1/sys/config/auditing/request-headers' . $name);
    }

    /**
     * This endpoint enables auditing of a header.
     * @param string $name
     * @param array $data
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-auditing#create-update-audit-request-header
     */
    public function setAuditRequestHeader(string $name, array $data)
    {
        return $this->client->post('/v1/sys/config/auditing/request-headers' . $name, $data);
    }

    /**
     * This endpoint disables auditing of the given request header.
     * @param string $name
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-auditing#delete-audit-request-header
     */
    public function deleteAuditRequestHeader(string $name)
    {
        return $this->client->delete('/v1/sys/config/auditing/request-headers' . $name);
    }

    /**
     * This endpoint returns the current CORS configuration.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-cors#read-cors-settings
     */
    public function getCORSSettings()
    {
        return $this->client->get('/v1/sys/config/cors');
    }

    /**
     * This endpoint allows configuring the origins that are permitted to make cross-origin requests, as well as headers that are allowed on cross-origin requests.
     * @param array $data
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-cors#configure-cors-settings
     */
    public function setCORSSettings(array $data)
    {
        return $this->client->post('/v1/sys/config/cors', $data);
    }

    /**
     * This endpoint removes any CORS configuration.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-cors#delete-cors-settings
     */
    public function deleteCORSSettings()
    {
        return $this->client->delete('/v1/sys/config/cors');
    }

    /**
     * This endpoint returns a sanitized version of the configuration state.
     * The configuration excludes certain fields and mappings in the configuration file that can potentially contain sensitive information,
     * which includes values from Storage.Config, HAStorage.Config, Seals.Config and the Telemetry.CirconusAPIToken value.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-state#get-sanitized-configuration-state
     */
    public function getSanitizedConfigurationState()
    {
        return $this->client->get('/v1/sys/config/state/sanitized');
    }

    /**
     * This endpoint returns the given UI header configuration.
     * @param string $name
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-ui#read-ui-settings
     */
    public function getUISettings(string $name)
    {
        return $this->client->get('/v1/sys/config/ui/headers' . $name);
    }

    /**
     * This endpoint allows configuring the values to be returned for the UI header.
     * @param string $name
     * @param array $data
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-ui#configure-ui-headers
     */
    public function setUIHeaders(string $name, array $data)
    {
        return $this->client->post('/v1/sys/config/ui/headers' . $name, $data);
    }

    /**
     * This endpoint removes a UI header.
     * @param string $name
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-ui#delete-a-ui-header
     */
    public function deleteUIHeader(string $name)
    {
        return $this->client->delete('/v1/sys/config/ui/headers' . $name);
    }

    /**
     * This endpoint returns a list of configured UI headers.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/config-ui#list-ui-headers
     */
    public function listUIHeaders()
    {
        return $this->client->list('/v1/sys/config/ui/headers');
    }

    /**
     * This endpoint returns the experiments available and enabled on the Vault node.
     * Experiments are per-node and cannot be changed while the node is running.
     * See the -experiment flag and the experiments config key documentation for details on enabling experiments.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/experiments#read-experiments
     */
    public function getExperiments()
    {
        return $this->client->get('/v1/sys/experiments');
    }

    /**
     * This endpoint reads the configuration and process of the current root generation attempt.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/generate-recovery-token#read-recovery-token-generation-progress
     */
    public function getRecoveryTokenGenerationProgress()
    {
        return $this->client->get('/v1/sys/generate-recovery-token/attempt');
    }

    /**
     * This endpoint initializes a new recovery token generation attempt. Only a single recovery token generation attempt can take place at a time.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/generate-recovery-token#start-recovery-token-generation
     */
    public function startRecoveryTokenGeneration()
    {
        return $this->client->post('/v1/sys/generate-recovery-token/attempt');
    }

    /**
     * This endpoint cancels any in-progress recovery token generation attempt. This clears any progress made. This must be called to change the OTP or PGP key being used.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/generate-recovery-token#cancel-recovery-token-generation
     */
    public function cancelRecoveryTokenGeneration()
    {
        return $this->client->delete('/v1/sys/generate-recovery-token/attempt');
    }

    /**
     * This endpoint is used to enter a single root key share to progress the recovery token generation attempt.
     * If the threshold number of root key shares is reached, Vault will complete the recovery token generation and issue the new token.
     * Otherwise, this API must be called multiple times until that threshold is met. The attempt nonce must be provided with each call.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/generate-recovery-token#provide-key-share-to-generate-recovery-token
     */
    public function setGenerateRecoveryToken(array $data)
    {
        return $this->client->post('/v1/sys/generate-recovery-token/update', $data);
    }

    /**
     * This endpoint reads the configuration and process of the current root generation attempt.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/generate-root#read-root-generation-progress
     */
    public function getRootGenerationProgress()
    {
        return $this->client->get('/v1/sys/generate-root/attempt');
    }

    /**
     * This endpoint initializes a new root generation attempt. Only a single root generation attempt can take place at a time.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/generate-root#start-root-token-generation
     */
    public function startRootTokenGeneration()
    {
        return $this->client->post('/v1/sys/generate-root/attempt');
    }

    /**
     * This endpoint cancels any in-progress root generation attempt. This clears any progress made. This must be called to change the OTP or PGP key being used.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/generate-root#cancel-root-generation
     */
    public function cancelRootTokenGeneration()
    {
        return $this->client->delete('/v1/sys/generate-root/attempt');
    }

    /**
     * This endpoint is used to enter a single root key share to progress the root generation attempt.
     * If the threshold number of root key shares is reached, Vault will complete the root generation and issue the new token.
     * Otherwise, this API must be called multiple times until that threshold is met. The attempt nonce must be provided with each call.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/generate-root#provide-key-share-to-generate-root
     */
    public function setRootTokenGeneratio(array $data)
    {
        return $this->client->post('/v1/sys/generate-root/update', $data);
    }

    /**
     * This endpoint returns the health status of Vault.
     * This matches the semantics of a Consul HTTP health check and provides a simple way to monitor the health of a Vault instance.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/health#read-health-information
     */
    public function getHealthInformation()
    {
        return $this->client->get('/v1/sys/health');
    }

    /**
     * This endpoint returns information about the host instance that the Vault server is running on.
     * The data returned includes CPU information, CPU times, disk usage, host info, and memory statistics.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/host-info#collect-host-information
     */
    public function getHostInformation()
    {
        return $this->client->get('/v1/sys/host-info');
    }

    /**
     * This endpoint returns the information about the in-flight requests.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/in-flight-req#collect-in-flight-request-information
     */
    public function getInFlightRequestInformation()
    {
        return $this->client->get('/v1/sys/in-flight-req');
    }

    /**
     * This endpoint returns the initialization status of Vault.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/init#read-initialization-status
     */
    public function getInitializationStatus()
    {
        return $this->client->get('/v1/sys/init');
    }

    /**
     * This endpoint initializes a new Vault. The Vault must not have been previously initialized.
     * The recovery options, as well as the stored shares option, are only available when using Auto Unseal.
     * @param array $data
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/init#start-initialization
     */
    public function startInitialization(array $data)
    {
        return $this->client->post('/v1/sys/init', $data);
    }

    /**
     * This endpoint returns the total number of Entities.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-counters#entities
     */
    public function getEntities()
    {
        return $this->client->get('/v1/sys/internal/counters/entities');
    }

    /**
     * This endpoint returns the total number of Tokens.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-counters#tokens
     */
    public function getTokens()
    {
        return $this->client->get('/v1/sys/internal/counters/tokens');
    }

    /**
     * This endpoint returns client activity information for a given billing period,
     * which is represented by the start_time and end_time parameters.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-counters#client-count
     */
    public function getClientCount(string $params = '')
    {
        return $this->client->get('/v1/sys/internal/counters/activity' . $params);
    }

    /**
     * This endpoint returns the client activity in the current month.
     * The response will have activity attributions per namespace, per mount within each namespaces, and new clients information.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-counters#partial-month-client-count
     */
    public function getPartialMonthClientCount()
    {
        return $this->client->get('/v1/sys/internal/counters/activity/monthly');
    }

    /**
     * Endpoint is used to configure logging of active clients
     * @param array $data
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-counters#update-the-client-count-configuration
     */
    public function updateClientCountConfiguration(array $data)
    {
        return $this->client->post('/v1/sys/internal/counters/config', $data);
    }

    /**
     * Reading the configuration shows the current settings, as well as a flag whether any data can be queried.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-counters#read-the-client-count-configuration
     */
    public function getClientCountConfiguration()
    {
        return $this->client->get('/v1/sys/internal/counters/config');
    }

    /**
     * This endpoint returns an export of the clients that had activity within the provided start and end times.
     * The returned set of client information will be deduplicated over the time window and will show the earliest activity logged for each client.
     * The output will be ordered chronologically by month of activity.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-counters#activity-export
     */
    public function getActivity()
    {
        return $this->client->get('/v1/sys/internal/counters/activity/export');
    }

    /**
     * Endpoint is used to generate an OpenAPI document of the mounted backends
     * @param string $params
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-specs-openapi#get-openapi-document
     */
    public function getOpenAPIDocument(string $params = '')
    {
        return $this->client->get('/v1/sys/internal/specs/openapi' . $params);
    }

    /**
     * Endpoint is used to expose feature flags to the UI so that it can change its behavior in response, even before a user logs in.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-ui-feature#get-enabled-feature-flags
     */
    public function getEnabledFeatureFlags()
    {
        return $this->client->get('/v1/sys/internal/ui/feature-flags');
    }

    /**
     * Endpoint is used to manage mount listing visibility.
     * The response generated by this endpoint is based on the listing_visibility value on the mount, which can be set during mount time or via mount tuning.
     * This is currently only being used internally, for the UI and for CLI preflight checks, and is an unauthenticated endpoint.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-ui-mounts#get-available-visible-mounts
     */
    public function getAvailableVisibleMounts()
    {
        return $this->client->get('/v1/sys/internal/ui/mounts');
    }

    /**
     * This endpoint lists details for a specific mount path.
     * This is an authenticated endpoint, and is currently only being used internally.
     * @param string $path
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-ui-mounts#get-single-mount-details
     */
    public function getSingleMountDetails(string $path)
    {
        return $this->client->get('/v1/sys/internal/ui/mounts' . $path);
    }

    /**
     * Endpoint is used to expose namespaces to the UI so that it can change its behavior in response, even before a user logs in.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-ui-namespaces#get-namespaces
     */
    public function getNamespaces()
    {
        return $this->client->get('/v1/sys/internal/ui/namespaces');
    }

    /**
     * This endpoint lists the resultant-acl relevant to the UI.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/internal-ui-resultant-acl#get-resultant-acl
     */
    public function getResultantACL()
    {
        return $this->client->get('/v1/sys/internal/ui/resultant-acl');
    }

    /**
     * This endpoint returns information about the current encryption key used by Vault.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/key-status#get-encryption-key-status
     */
    public function getEncryptionKeyStatus()
    {
        return $this->client->get('/v1/sys/key-status');
    }

    /**
     * This endpoint returns the HA status of the Vault cluster.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/ha-status#ha-status
     */
    public function getHAStatus()
    {
        return $this->client->get('/v1/sys/ha-status');
    }

    /**
     * This endpoint returns the high availability status and current leader instance of Vault.
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/leader#read-leader-status
     */
    public function getLeaderStatus()
    {
        return $this->client->get('/v1/sys/leader');
    }

    /**
     * This endpoint retrieve lease metadata.
     * @param array $data
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/leases#read-lease
     */
    public function readLease(array $data)
    {
        return $this->client->post('/v1/sys/leases/lookup', $data);
    }

    /**
     * This endpoint returns a list of lease ids.
     * @param string $prefix
     * @return array
     * @see https://developer.hashicorp.com/vault/api-docs/system/leases#list-leases
     */
    public function listLeases(string $prefix)
    {
        return $this->client->list('/v1/sys/leases/lookup' . $prefix);
    }

    /**
     * This endpoint renews a lease, requesting to extend the lease.
     * Token leases cannot be renewed using this endpoint, use instead the auth/token/renew endpoint.
     * @param array $data
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/leases#renew-lease
     */
    public function renewLease(array $data)
    {
        return $this->client->post('/v1/sys/leases/renew', $data);
    }

    /**
     * This endpoint revokes a lease immediately.
     * @param array $data
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/leases#revoke-lease
     */
    public function revokeLease(array $data)
    {
        return $this->client->post('/v1/sys/leases/revoke', $data);
    }

    /**
     * This endpoint revokes all secrets or tokens generated under a given prefix immediately.
     * @param string $prefix
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/leases#revoke-force
     */
    public function revokeForceLease(string $prefix)
    {
        return $this->client->post('/v1/sys/leases/revoke-force' . $prefix);
    }

    /**
     * This endpoint revokes all secrets (via a lease ID prefix) or tokens (via the tokens' path property) generated under a given prefix immediately.
     * @param string $prefix
     * @return bool|array|null|string
     * @see https://developer.hashicorp.com/vault/api-docs/system/leases#revoke-prefix
     */
    public function revokePrefixLease(string $prefix)
    {
        return $this->client->post('/v1/sys/leases/revoke-prefix' . $prefix);
    }

    /**
     * This endpoint cleans up the dangling storage entries for leases: for each lease entry in storage,
     * Vault will verify that it has an associated valid non-expired token in storage, and if not, the lease will be revoked.
     * @return bool|array|null|string
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/leases#tidy-leases
     */
    public function setTidyLeases()
    {
        return $this->client->post('/v1/sys/leases/tidy');
    }

    /**
     * This endpoint returns the total count of a type of lease, as well as a count per mount point. Note that it currently only supports type "irrevocable".
     * @param array $data
     * @return bool|array|null|string
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/leases#lease-counts
     */
    public function getLeaseCounts(array $data)
    {
        return $this->client->get('/v1/sys/leases/count', $data);
    }

    /**
     * This endpoint returns the total count of a type of lease, as well as a list of leases per mount point. Note that it currently only supports type "irrevocable".
     * @param array $data
     * @return bool|array|null|string
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/leases#leases-list
     */
    public function getLeasesList(array $data)
    {
        return $this->client->get('/v1/sys/leases', $data);
    }

    /**
     * This endpoint lists the locked users information in Vault.
     * @param array $data
     * @return bool|array|null|string
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/user-lockout#list-locked-users
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
     * @return bool|array|null|string
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/user-lockout#unlock-user
     */
    public function unlockUser(string $accessor, string $identifier)
    {
        return $this->client->post('/v1/sys/locked-users' . $accessor . '/unlock' . $identifier);
    }

    /**
     * This endpoint returns the telemetry metrics for Vault.
     * It can be used by metrics collections systems like Prometheus that use a pull model for metrics collection.
     * @param string $format , value METRIC_FORMAT_JSON or METRIC_FORMAT_PROMETHEUS
     * @return bool|array|null|string
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/metrics#read-telemetry-metrics
     */
    public function getMetrics(string $format = self::METRIC_FORMAT_PROMETHEUS)
    {
        return $this->client->get('/v1/sys/metrics?format=' . $format);
    }

    /**
     * This endpoint streams logs back to the client from Vault. Note that unlike most API endpoints in Vault, this one does not return JSON by default.
     * This will send back data in whatever log format Vault has been configured with. By default, this is text.
     * @param string $level , value LOG_LEVEL_INFO or LOG_LEVEL_DEBUG
     * @param string $format , value LOG_FORMAT_STANDARD or LOG_FORMAT_JSON
     * @return bool|array|null|string
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/monitor#monitor-system-logs
     */
    public function getSystemLogs(string $level = self::LOG_LEVEL_INFO, string $format = self::LOG_FORMAT_STANDARD)
    {
        return $this->client->get('/v1/sys/monitor?log_level=' . $level . '&log_format=' . $format);
    }

    /**
     * These endpoints list all the mounted secrets engines.
     * @see https://developer.hashicorp.com/vault/api-docs/system/mounts#list-mounted-secrets-engines
     * @return bool|array|null|string
     */
    public function getMountedSecretsEngines(string $path = '')
    {
        return $this->client->get('/v1/sys/mounts' . $path);
    }

    /**
     * This endpoint enables a new secrets engine at the given path.
     * @param string $path
     * @param array $data
     * @return bool|array|null|string
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/mounts#enable-secrets-engine
     */
    public function enableSecretEngine(string $path, array $data)
    {
        return $this->client->post('/v1/sys/mounts' . $path, $data);
    }

    /**
     * This endpoint disables the mount point specified in the URL.
     * @param string $name
     * @return bool|array|null|string
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/mounts#disable-secrets-engine
     */
    public function disableSecretEngine(string $name)
    {
        return $this->client->delete('/v1/sys/mounts' . $name);
    }

    /**
     * This endpoint reads the given mount's configuration.
     * Unlike the mounts endpoint, this will return the current time in seconds for each TTL, which may be the system default or a mount-specific value.
     * @param string $path
     * @return bool|array|null|string
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/mounts#read-mount-configuration
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/mounts#tune-mount-configuration
     */
    public function tuneMountConfiguration(string $path, array $data)
    {
        return $this->client->post('/v1/sys/mounts' . $path . '/tune', $data);
    }

    /**
     * This endpoint lists all the namespaces.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/namespaces#list-namespaces
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/namespaces#create-namespace
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/namespaces#patch-namespace
     */
    public function updateNamespace(string $path, array $data)
    {
        return $this->client->patch('/v1/sys/namespaces' . $path, $data);
    }

    /**
     * This endpoint deletes a namespace at the specified path.
     * @param string $path
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/namespaces#delete-namespace
     */
    public function deleteNamespace(string $path)
    {
        return $this->client->delete('/v1/sys/namespaces' . $path);
    }

    /**
     * This endpoint gets the metadata for the given namespace path.
     * @param string $path
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/namespaces#read-namespace-information
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/namespaces#lock-namespace
     */
    public function lockNamespace(string $path = '', string $header = '')
    {
        $namespace = [];
        if ($header !== '') {
            $namespace = [
                'X-Vault-Namespace' => $header,
            ];
        }

        return $this->client->post('/v1/sys/namespaces/api-lock/lock' . $path, [], $namespace);
    }

    /**
     * This endpoint unlocks the api for the current namespace path or optional subpath.
     * @param string $path
     * @param array $data
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/namespaces#unlock-namespace
     */
    public function unlockNamespace(string $path = '', array $data = [])
    {
        return $this->client->post('/v1/sys/namespaces/api-lock/unlock' . $path, $data);
    }

    /**
     * This endpoint reloads mounted plugin backends.
     * @param array $data
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/plugins-reload-backend#reload-plugins
     */
    public function reloadPlugins(array $data)
    {
        return $this->client->post('/v1/sys/plugins/reload/backend', $data);
    }

    /**
     * This endpoint lists the plugins in the catalog by type.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/plugins-catalog#list-plugins
     */
    public function getPlugins()
    {
        return $this->client->get('/v1/sys/plugins/catalog');
    }

    /**
     * This endpoint lists the plugins in the catalog by type.
     * @param string $type
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/plugins-catalog#list-plugins-1
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/plugins-catalog#register-plugin
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/plugins-catalog#read-plugin
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/plugins-catalog#remove-plugin-from-catalog
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
     * @param string $name
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/policy#read-policy
     * @see https://developer.hashicorp.com/vault/api-docs/system/policy#list-policies
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/policy#create-update-policy
     */
    public function setPolicy(string $name, array $data)
    {
        return $this->client->post('/v1/sys/policy' . $name, $data);
    }

    /**
     * This endpoint deletes the policy with the given name.
     * This will immediately affect all users associated with this policy.
     * @param string $name
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/policy#delete-policy
     */
    public function deletePolicy(string $name)
    {
        return $this->client->delete('/v1/sys/policy' . $name);
    }

    /**
     * This endpoint lists all configured ACL policies.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#list-acl-policies
     */
    public function listACLPolicies()
    {
        return $this->client->list('/v1/sys/policies/acl');
    }

    /**
     * This endpoint retrieves information about the named ACL policy.
     * @param string $name
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#read-acl-policy
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#create-update-acl-policy
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#delete-acl-policy
     */
    public function deleteACLPolicy(string $name)
    {
        return $this->client->delete('/v1/sys/policies/acl' . $name);
    }

    /**
     * This endpoint lists all configured RGP policies.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#list-rgp-policies
     */
    public function listRGPPolicies()
    {
        return $this->client->list('/v1/sys/policies/rgp');
    }

    /**
     * This endpoint retrieves information about the named RGP policy.
     * @param string $name
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#read-rgp-policy
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#create-update-rgp-policy
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#delete-rgp-policy
     */
    public function deleteRGPPolicy(string $name)
    {
        return $this->client->delete('/v1/sys/policies/rgp' . $name);
    }

    /**
     * This endpoint lists all configured EGP policies.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#list-egp-policies
     */
    public function listEGPPolicies()
    {
        return $this->client->list('/v1/sys/policies/egp');
    }

    /**
     * This endpoint retrieves information about the named EGP policy.
     * @param string $name
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#read-egp-policy
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#create-update-egp-policy
     */
    public function setEGPPolicy(string $name, array $data)
    {
        return $this->client->post('/v1/sys/policies/egp' . $name, $data);
    }

    /**
     * This endpoint deletes the EGP policy with the given name from all paths on which it was configured.
     * @param string $name
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies#delete-egp-policy
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies-password#create-update-password-policy
     */
    public function setPasswordPolicy(string $name, array $data)
    {
        return $this->client->post('/v1/sys/policies/password' . $name, $data);
    }

    /**
     * This endpoint list the password policies.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies-password#list-password-policies
     */
    public function listPasswordPolicies()
    {
        return $this->client->list('/v1/sys/policies/password');
    }

    /**
     * This endpoint retrieves information about the named password policy.
     * @param string $name
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies-password#read-password-policy
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies-password#delete-password-policy
     */
    public function deletePasswordPolicy(string $name)
    {
        return $this->client->delete('/v1/sys/policies/password' . $name);
    }

    /**
     * This endpoint generates a password from the specified existing password policy.
     * @param string $name
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/policies-password#generate-password-from-password-policy
     */
    public function generatePasswordPolicy(string $name)
    {
        return $this->client->get('/v1/sys/policies/password' . $name . '/generate');
    }

    /**
     * This endpoint returns an HTML page listing the available profiles.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/pprof#index
     */
    public function getPprof()
    {
        return $this->client->get('/v1/sys/pprof');
    }

    /**
     * This endpoint returns a sampling of historical memory allocations over the life of the program.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/pprof#allocs
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/pprof#block
     */
    public function getBlock()
    {
        return $this->client->get('/v1/sys/pprof/block');
    }

    /**
     * This endpoint returns the running program's command line, with arguments separated by NUL bytes.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/pprof#cmdline
     */
    public function getCmdline()
    {
        return $this->client->get('/v1/sys/pprof/cmdline');
    }

    /**
     * This endpoint returns stack traces of all current goroutines.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/pprof#goroutine
     */
    public function getGoroutine()
    {
        return $this->client->get('/v1/sys/pprof/goroutine');
    }

    /**
     * This endpoint returns a sampling of memory allocations of live object.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/pprof#heap
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/pprof#mutex
     */
    public function getMutex()
    {
        return $this->client->get('/v1/sys/pprof/mutex');
    }

    /**
     * This endpoint returns a pprof-formatted cpu profile payload.
     * Profiling lasts for duration specified in seconds GET parameter, or for 30 seconds if not specified.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/pprof#profile
     */
    public function getProfile()
    {
        return $this->client->get('/v1/sys/pprof/profile');
    }

    /**
     * This endpoint returns the program counters listed in the request.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/pprof#symbol
     */
    public function getSymbol()
    {
        return $this->client->get('/v1/sys/pprof/symbol');
    }

    /**
     * This endpoint returns stack traces of goroutines that led to the creation of new OS threads.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/pprof#threadcreate
     */
    public function getThreadcreate()
    {
        return $this->client->get('/v1/sys/pprof/threadcreate');
    }

    /**
     * This endpoint returns the execution trace in binary form.
     * Tracing lasts for duration specified in seconds GET parameter, or for 1 second if not specified.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/pprof#trace
     */
    public function getTrace()
    {
        return $this->client->get('/v1/sys/pprof/trace');
    }

    /**
     * Endpoint is used to show rate limit quotas
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/quotas-config#get-the-rate-limit-configuration
     */
    public function getRateLimit()
    {
        return $this->client->get('/v1/sys/quotas/config');
    }

    /**
     * Endpoint is used to configure rate limit quotas
     * @param array $data
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/quotas-config#create-or-update-the-rate-limit-configuration
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/raw#read-raw
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/raw#create-update-raw
     */
    public function setRaw(string $path, array $data)
    {
        return $this->client->get('/v1/sys/raw' . $path, $data);
    }

    /**
     * This endpoint returns a list keys for a given path prefix.
     * @param string $prefix
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/raw#list-raw
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/raw#delete-raw
     */
    public function deleteRaw(string $path)
    {
        return $this->client->delete('/v1/sys/raw' . $path);
    }

    /**
     * Endpoint moves an already-mounted backend to a new mount point. This process works for both secret engines and auth methods.
     * The remount operation returns a migration ID to the user. The user may utilize the migration ID to look up the status of the mount migration.
     * More details about the remount operation are described in Mount Migration.
     * @param string $from
     * @param string $to
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/remount#move-backend
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/remount#monitor-migration-status
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/rotate#rotate-encryption-key
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/rotate-config#get-the-auto-rotation-configuration
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/rotate-config#create-or-update-the-auto-rotation-configuration
     */
    public function setAutoRotation(array $data)
    {
        return $this->client->post('/v1/sys/rotate/config', $data);
    }

    /**
     * This endpoint seals the Vault. In HA mode, only an active node can be sealed.
     * Standby nodes should be restarted to get the same effect.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/seal#seal
     */
    public function setSeal()
    {
        return $this->client->post('/v1/sys/seal');
    }

    /**
     * This endpoint returns the seal status of the Vault.
     * This is an unauthenticated endpoint.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/seal-status#seal-status
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/step-down#step-down-leader
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/unseal#submit-unseal-key
     */
    public function unseal(array $data)
    {
        return $this->client->post('/v1/sys/unseal', $data);
    }

    /**
     * This endpoint returns the version history of the Vault.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/version-history#read-version-history
     */
    public function getVersionHistory()
    {
        return $this->client->get('/v1/sys/version-history');
    }

    /**
     * This endpoint looks up wrapping properties for the given token.
     * @param string $token
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/wrapping-lookup#wrapping-lookup
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/wrapping-rewrap#wrapping-rewrap
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/wrapping-unwrap#wrapping-unwrap
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
     * @throws \yii\base\InvalidConfigException
     * @see https://developer.hashicorp.com/vault/api-docs/system/wrapping-wrap#wrapping-wrap
     */
    public function setWrappingWrap(array $data, array $headers = [])
    {
        return $this->client->post('/v1/sys/wrapping/wrap', $data, $headers);
    }
}